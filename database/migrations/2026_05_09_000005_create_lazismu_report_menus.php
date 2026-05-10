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

        $parentId = DB::table('menus')->where('name', 'Laporan')->whereNull('parent_id')->value('id');

        if (!$parentId) {
            $parentId = DB::table('menus')->insertGetId([
                'name' => 'Laporan',
                'link' => null,
                'parent_id' => null,
                'role' => ';superadmin;admin;laporan;keuangan;direktur;manager;',
                'seq' => 4,
                'icon' => 'bi bi-file-earmark-bar-graph',
                'module' => 'laporan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $menus = [
            ['Laporan Cashflow', 'lazismu.laporan.cashflow', 1, 'bi bi-cash-stack'],
            ['Laporan Program', 'lazismu.laporan.program', 2, 'bi bi-bar-chart'],
            ['Laporan Infaq', 'lazismu.laporan.infaq', 3, 'bi bi-box-seam'],
            ['Laporan Zakat', 'lazismu.laporan.zakat', 4, 'bi bi-journal-text'],
        ];

        foreach ($menus as [$name, $link, $seq, $icon]) {
            DB::table('menus')->updateOrInsert(
                ['link' => $link],
                [
                    'name' => $name,
                    'parent_id' => $parentId,
                    'role' => ';superadmin;admin;laporan;keuangan;direktur;manager;',
                    'seq' => $seq,
                    'icon' => $icon,
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

        DB::table('menus')->whereIn('link', [
            'lazismu.laporan.cashflow',
            'lazismu.laporan.program',
            'lazismu.laporan.infaq',
            'lazismu.laporan.zakat',
        ])->delete();
    }
};
