<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('muzaki', function (Blueprint $table) {
            if (!Schema::hasColumn('muzaki', 'ranting')) {
                $table->string('ranting')->nullable()->after('tgl_lahir');
            }

            if (!Schema::hasColumn('muzaki', 'aum')) {
                $table->string('aum')->nullable()->after('ranting');
            }

            if (Schema::hasColumn('muzaki', 'email')) {
                $table->string('email', 100)->nullable()->change();
            }

            if (Schema::hasColumn('muzaki', 'target_setoran')) {
                $table->dropColumn('target_setoran');
            }
        });

        if (!Schema::hasTable('target_setoran_program')) {
            Schema::create('target_setoran_program', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('idprogram')->nullable();
                $table->unsignedBigInteger('idmuzaki')->nullable();
                $table->double('target', 10, 2)->default(0);
            });
        } else {
            Schema::table('target_setoran_program', function (Blueprint $table) {
                if (!Schema::hasColumn('target_setoran_program', 'idprogram')) {
                    $table->unsignedBigInteger('idprogram')->nullable()->after('id');
                }

                if (!Schema::hasColumn('target_setoran_program', 'idmuzaki')) {
                    $table->unsignedBigInteger('idmuzaki')->nullable()->after('idprogram');
                }

                if (!Schema::hasColumn('target_setoran_program', 'target')) {
                    $table->double('target', 10, 2)->default(0)->after('idmuzaki');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('target_setoran_program');

        Schema::table('muzaki', function (Blueprint $table) {
            if (!Schema::hasColumn('muzaki', 'target_setoran')) {
                $table->decimal('target_setoran', 15, 2)->default(0)->after('email');
            }

            foreach (['aum', 'ranting'] as $column) {
                if (Schema::hasColumn('muzaki', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
