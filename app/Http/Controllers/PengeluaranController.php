<?php

namespace App\Http\Controllers;

use App\Models\Kodetransaksi;
use App\Models\Nota;
use App\Models\Program;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Image\Image;

class PengeluaranController extends Controller
{
    private const BUKTI_DIRECTORY = 'lazismu/notas';
    private const EDITOR_DIRECTORY = 'lazismu/notas/editor';
    private const IMAGE_MAX_WIDTH = 1600;
    private const EDITOR_IMAGE_MAX_WIDTH = 1200;
    private const IMAGE_QUALITY = 82;

    public function index()
    {
        $notas = Nota::with(['program', 'kodeTransaksi'])
            ->latest('tanggal')
            ->latest('id')
            ->get();
        $programs = Program::orderBy('nama_program')->get();
        $kodeTransaksis = Kodetransaksi::with('header')
            ->orderBy('kodetransaksi')
            ->get();

        return view('lazismu.pengeluaran.index', compact('notas', 'programs', 'kodeTransaksis'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateNota($request);
        $validated['nota_no'] = $validated['nota_no'] ?: $this->generateNotaNo($validated['tanggal']);

        if ($request->hasFile('bukti_nota')) {
            $validated['bukti_nota'] = $this->storeBukti($request);
        }

        $validated = array_merge($validated, $this->kelompokFlags($validated['kelompok'], $validated['idprogram'] ?? null));
        unset($validated['kelompok']);

        $validated['userid'] = (string) Auth::id();
        $validated['namauser'] = Auth::user()->name ?? 'System';
        $validated['status'] = 'paid';

        Nota::create($validated);

        return redirect()->route('lazismu.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, Nota $pengeluaran)
    {
        $validated = $this->validateNota($request, $pengeluaran);
        $validated['nota_no'] = $validated['nota_no'] ?: $pengeluaran->nota_no;

        if ($request->hasFile('bukti_nota')) {
            if ($pengeluaran->bukti_nota) {
                Storage::disk('public')->delete($pengeluaran->bukti_nota);
            }
            $validated['bukti_nota'] = $this->storeBukti($request);
        }

        $validated = array_merge($validated, $this->kelompokFlags($validated['kelompok'], $validated['idprogram'] ?? null));
        unset($validated['kelompok']);
        $validated['status'] = 'paid';

        $pengeluaran->update($validated);

        return redirect()->route('lazismu.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Nota $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('lazismu.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function uploadEditorImage(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $path = $this->storeOptimizedImage($validated['image'], self::EDITOR_DIRECTORY, self::EDITOR_IMAGE_MAX_WIDTH);

        return response()->json([
            'url' => '/storage/' . ltrim($path, '/'),
        ]);
    }

    private function validateNota(Request $request, ?Nota $nota = null): array
    {
        return $request->validate([
            'nota_no' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('notas', 'nota_no')->ignore($nota?->id)->whereNull('deleted_at'),
            ],
            'namatransaksi' => ['required', 'string', 'max:150'],
            'tanggal' => ['required', 'date'],
            'kelompok' => ['required', Rule::in(['zakat', 'infaq', 'program'])],
            'idprogram' => ['nullable', 'required_if:kelompok,program', 'integer', 'exists:program,id'],
            'idkodetransaksi' => ['required', 'integer', 'exists:kodetransaksi,id'],
            'total' => ['required', 'numeric', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'bukti_nota' => [$nota ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,pdf,webp'],
        ]);
    }

    private function kelompokFlags(string $kelompok, mixed $idprogram): array
    {
        return [
            'is_zakat' => $kelompok === 'zakat' ? '1' : '0',
            'id_infaq' => $kelompok === 'infaq' ? '1' : '0',
            'idprogram' => $kelompok === 'program' ? $idprogram : null,
        ];
    }

    private function storeBukti(Request $request): string
    {
        Storage::disk('public')->makeDirectory(self::BUKTI_DIRECTORY);

        $file = $request->file('bukti_nota');

        if (str_starts_with((string) $file->getMimeType(), 'image/')) {
            return $this->storeOptimizedImage($file, self::BUKTI_DIRECTORY, self::IMAGE_MAX_WIDTH);
        }

        $filename = now()->format('YmdHis') . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';

        return $file->storeAs(self::BUKTI_DIRECTORY, $filename, 'public');
    }

    private function storeOptimizedImage(UploadedFile $file, string $directory, int $maxWidth): string
    {
        Storage::disk('public')->makeDirectory($directory);

        $filename = now()->format('YmdHis') . '-' . Str::random(8) . '.webp';
        $relativePath = $directory . '/' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);

        $image = Image::load($file->path());
        $dimensions = getimagesize($file->path());

        if (($dimensions[0] ?? 0) > $maxWidth) {
            $image->width($maxWidth);
        }

        $image
            ->format('webp')
            ->quality(self::IMAGE_QUALITY)
            ->save($absolutePath);

        return $relativePath;
    }

    private function generateNotaNo(string $tanggal): string
    {
        $date = date('Ymd', strtotime($tanggal));
        $prefix = 'PGL-' . $date . '-';
        $lastNumber = Nota::where('nota_no', 'like', $prefix . '%')
            ->orderByDesc('nota_no')
            ->value('nota_no');
        $sequence = $lastNumber ? ((int) Str::afterLast($lastNumber, '-') + 1) : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
