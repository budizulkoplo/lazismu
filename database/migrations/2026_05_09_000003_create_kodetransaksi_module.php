<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kodetransaksi_hdr')) {
            Schema::create('kodetransaksi_hdr', function (Blueprint $table) {
                $table->id();
                $table->string('keterangan');
                $table->timestamp('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
            });
        }

        if (!Schema::hasTable('kodetransaksi')) {
            Schema::create('kodetransaksi', function (Blueprint $table) {
                $table->id();
                $table->string('kodetransaksi');
                $table->string('transaksi');
                $table->integer('idheader')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (Schema::hasTable('menus')) {
            $masterId = DB::table('menus')
                ->where('name', 'Master')
                ->whereNull('parent_id')
                ->value('id');

            DB::table('menus')->updateOrInsert(
                ['link' => 'lazismu.kodetransaksi.index'],
                [
                    'name' => 'Kode Transaksi',
                    'parent_id' => $masterId,
                    'role' => ';superadmin;admin;operator;keuangan;direktur;manager;',
                    'seq' => 3,
                    'icon' => 'bi bi-receipt-cutoff',
                    'module' => 'master',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')
                ->where('link', 'lazismu.kodetransaksi.index')
                ->delete();
        }
    }
};
