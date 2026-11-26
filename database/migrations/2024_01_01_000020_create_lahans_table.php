<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->nullable()->cascadeOnDelete()->constrained('petanis');
            $table->string('nama_blok');
            $table->decimal('luas_hektar', 8, 2);
            $table->string('komoditas_tanam')->nullable();
            $table->date('estimasi_panen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lahans');
    }
};
