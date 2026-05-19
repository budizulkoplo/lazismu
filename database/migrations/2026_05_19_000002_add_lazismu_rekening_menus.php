<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $masterId = DB::table('menus')->where('name', 'Master')->whereNull('parent_id')->value('id');
        $laporanId = DB::table('menus')->where('name', 'Laporan')->whereNull('parent_id')->value('id');

        if ($masterId) {
            DB::table('menus')->updateOrInsert(
                ['link' => 'lazismu.rekening.index'],
                [
                    'name' => 'Rekening',
                    'parent_id' => $masterId,
                    'role' => ';superadmin;admin;operator;laporan;keuangan;direktur;manager;',
                    'seq' => 3,
                    'icon' => 'bi bi-wallet2',
                    'module' => 'master',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }

        if ($laporanId) {
            DB::table('menus')->updateOrInsert(
                ['link' => 'lazismu.laporan.rekening'],
                [
                    'name' => 'Rekening',
                    'parent_id' => $laporanId,
                    'role' => ';superadmin;admin;operator;laporan;keuangan;direktur;manager;',
                    'seq' => 5,
                    'icon' => 'bi bi-wallet2',
                    'module' => 'laporan',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        DB::table('menus')
            ->whereIn('link', ['lazismu.rekening.index', 'lazismu.laporan.rekening'])
            ->delete();
    }
};
