<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas');
            $table->decimal('stok_kg', 12, 2)->default(0);
            $table->decimal('harga_beli_aktif', 12, 2)->default(0);
            $table->decimal('harga_jual_aktif', 12, 2)->default(0);
            $table->decimal('harga_modal_rata2', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};
