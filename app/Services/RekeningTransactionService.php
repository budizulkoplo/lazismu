<?php

namespace App\Services;

use App\Models\Program;
use App\Models\Rekening;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekeningTransactionService
{
    public function ensureRekening(string $name): Rekening
    {
        $name = trim($name);

        return Rekening::firstOrCreate(
            ['namarek' => $name],
            ['saldo' => 0]
        );
    }

    public function ensureProgramRekening(Program $program): Rekening
    {
        if ($program->idrek) {
            $rekening = Rekening::find($program->idrek);
            if ($rekening) {
                return $rekening;
            }
        }

        $rekening = $this->ensureRekening('Program - ' . $program->nama_program);
        $program->forceFill(['idrek' => $rekening->getKey()])->save();

        return $rekening;
    }

    public function rekeningForKelompok(string $kelompok, ?Program $program = null): Rekening
    {
        $kelompok = strtolower(trim($kelompok));

        if ($kelompok === 'program' && $program) {
            return $this->ensureProgramRekening($program);
        }

        return $this->ensureRekening(match ($kelompok) {
            'zakat' => 'Zakat',
            'infaq' => 'Infaq',
            'wakaf' => 'Wakaf',
            default => ucfirst($kelompok),
        });
    }

    public function record(Model $source, int $rekeningId, string $tanggal, string $jenis, float $nominal, ?string $keterangan = null): Transaksi
    {
        return DB::transaction(function () use ($source, $rekeningId, $tanggal, $jenis, $nominal, $keterangan) {
            $rekening = Rekening::whereKey($rekeningId)->lockForUpdate()->firstOrFail();
            $saldoAwal = (float) $rekening->saldo;
            $saldoAkhir = $jenis === 'in'
                ? $saldoAwal + $nominal
                : $saldoAwal - $nominal;

            $rekening->update(['saldo' => $saldoAkhir]);

            return Transaksi::create([
                'idrek' => $rekening->getKey(),
                'source_type' => $source::class,
                'source_id' => $source->getKey(),
                'tanggal' => $tanggal,
                'jenis' => $jenis,
                'nominal' => $nominal,
                'saldo_awal' => $saldoAwal,
                'saldo_akhir' => $saldoAkhir,
                'keterangan' => $keterangan,
            ]);
        });
    }

    public function reverse(Model $source): void
    {
        DB::transaction(function () use ($source) {
            $entries = Transaksi::where('source_type', $source::class)
                ->where('source_id', $source->getKey())
                ->orderByDesc('id')
                ->get();

            foreach ($entries as $entry) {
                $rekening = Rekening::whereKey($entry->idrek)->lockForUpdate()->first();
                if ($rekening) {
                    $rekening->update([
                        'saldo' => $entry->jenis === 'in'
                            ? (float) $rekening->saldo - (float) $entry->nominal
                            : (float) $rekening->saldo + (float) $entry->nominal,
                    ]);
                }

                $entry->delete();
            }
        });
    }
}
