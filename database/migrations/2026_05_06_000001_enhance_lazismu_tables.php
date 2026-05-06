<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('muzaki', function (Blueprint $table) {
            if (!Schema::hasColumn('muzaki', 'nomor_induk_muzaki')) {
                $table->string('nomor_induk_muzaki', 30)->nullable()->unique()->after('nik');
            }
            if (!Schema::hasColumn('muzaki', 'jenis_muzaki')) {
                $table->string('jenis_muzaki', 20)->default('pribadi')->after('nomor_induk_muzaki');
            }
            if (!Schema::hasColumn('muzaki', 'target_setoran')) {
                $table->decimal('target_setoran', 15, 2)->default(0)->after('email');
            }
        });

        Schema::table('program', function (Blueprint $table) {
            if (!Schema::hasColumn('program', 'target')) {
                $table->decimal('target', 15, 2)->default(0)->after('tgl_selesai');
            }
            if (!Schema::hasColumn('program', 'banner_path')) {
                $table->string('banner_path')->nullable()->after('terkumpul');
            }
        });

        Schema::table('setoran', function (Blueprint $table) {
            if (!Schema::hasColumn('setoran', 'idrekening')) {
                $table->unsignedBigInteger('idrekening')->nullable()->after('idprogram');
            }
            if (!Schema::hasColumn('setoran', 'nominal_digunakan')) {
                $table->decimal('nominal_digunakan', 15, 2)->default(0)->after('nominal');
            }
            if (!Schema::hasColumn('setoran', 'nominal_pdm')) {
                $table->decimal('nominal_pdm', 15, 2)->default(0)->after('nominal_digunakan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('setoran', function (Blueprint $table) {
            foreach (['idrekening', 'nominal_digunakan', 'nominal_pdm'] as $column) {
                if (Schema::hasColumn('setoran', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('program', function (Blueprint $table) {
            foreach (['target', 'banner_path'] as $column) {
                if (Schema::hasColumn('program', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('muzaki', function (Blueprint $table) {
            foreach (['nomor_induk_muzaki', 'jenis_muzaki', 'target_setoran'] as $column) {
                if (Schema::hasColumn('muzaki', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
