<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('muzaki') && Schema::hasColumn('muzaki', 'nik') && in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE muzaki MODIFY nik VARCHAR(20) NULL');
        }

        if (!Schema::hasTable('menus')) {
            return;
        }

        $parentId = DB::table('menus')->where('name', 'Laporan')->whereNull('parent_id')->value('id');

        if (!$parentId) {
            return;
        }

        DB::table('menus')->updateOrInsert(
            ['link' => 'lazismu.laporan.muzaki'],
            [
                'name' => 'Laporan per Muzaki',
                'parent_id' => $parentId,
                'role' => ';superadmin;admin;laporan;keuangan;direktur;manager;',
                'seq' => 5,
                'icon' => 'bi bi-person-lines-fill',
                'module' => 'laporan',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        );
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')->where('link', 'lazismu.laporan.muzaki')->delete();
        }
    }
};
