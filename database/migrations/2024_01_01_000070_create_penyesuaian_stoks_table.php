<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyesuaian_stoks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('komoditas_id')->constrained('komoditas')->cascadeOnDelete();
            $table->enum('jenis', ['susut', 'koreksi_tambah']);
            $table->decimal('berat_kg', 12, 2);
            $table->string('alasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_stoks');
    }
};
