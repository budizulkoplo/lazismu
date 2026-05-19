<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('idrek');
                $table->string('source_type', 120)->nullable();
                $table->unsignedBigInteger('source_id')->nullable();
                $table->date('tanggal');
                $table->enum('jenis', ['in', 'out']);
                $table->decimal('nominal', 15, 2)->default(0);
                $table->decimal('saldo_awal', 15, 2)->default(0);
                $table->decimal('saldo_akhir', 15, 2)->default(0);
                $table->text('keterangan')->nullable();
                $table->timestamps();

                $table->index(['idrek', 'tanggal']);
                $table->index(['source_type', 'source_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
