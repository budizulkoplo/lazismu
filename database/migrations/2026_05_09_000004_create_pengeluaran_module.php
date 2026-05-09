<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notas')) {
            Schema::create('notas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nota_no', 50)->nullable();
                $table->string('namatransaksi', 150)->nullable();
                $table->date('tanggal');
                $table->enum('is_zakat', ['1', '0'])->nullable()->default('0');
                $table->enum('id_infaq', ['1', '0'])->nullable()->default('0');
                $table->integer('idprogram')->nullable();
                $table->integer('idkodetransaksi')->nullable();
                $table->decimal('total', 20, 2)->nullable();
                $table->enum('status', ['open', 'paid', 'partial', 'cancel'])->nullable()->default('open');
                $table->text('deskripsi')->nullable();
                $table->string('bukti_nota')->nullable();
                $table->string('userid', 11)->nullable();
                $table->string('namauser')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } elseif (!Schema::hasColumn('notas', 'idkodetransaksi')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->integer('idkodetransaksi')->nullable()->after('idprogram');
            });
        }

        if (Schema::hasTable('menus')) {
            $transaksiId = DB::table('menus')
                ->where('name', 'Transaksi')
                ->whereNull('parent_id')
                ->value('id');

            DB::table('menus')->updateOrInsert(
                ['link' => 'lazismu.pengeluaran.index'],
                [
                    'name' => 'Pengeluaran',
                    'parent_id' => $transaksiId,
                    'role' => ';superadmin;admin;operator;keuangan;direktur;manager;',
                    'seq' => 2,
                    'icon' => 'bi bi-box-arrow-up-right',
                    'module' => 'transaksi',
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
                ->where('link', 'lazismu.pengeluaran.index')
                ->delete();
        }

        if (Schema::hasTable('notas') && Schema::hasColumn('notas', 'idkodetransaksi')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->dropColumn('idkodetransaksi');
            });
        }
    }
};
