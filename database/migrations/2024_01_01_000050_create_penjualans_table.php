<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota')->unique();
            $table->date('tanggal');
            $table->string('nama_pembeli');
            $table->string('no_hp_pembeli', 20)->nullable();
            $table->foreignId('komoditas_id')->constrained('komoditas')->restrictOnDelete();
            $table->decimal('berat_kg', 12, 2);
            $table->decimal('harga_jual_satuan', 12, 2);
            $table->decimal('hpp_satuan', 12, 2);
            $table->decimal('total_nominal', 15, 2);
            $table->enum('status_bayar', ['lunas', 'belum_lunas'])->default('lunas');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
