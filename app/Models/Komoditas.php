<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komoditas extends Model
{
    protected $table = 'komoditas';

    protected $fillable = [
        'nama_komoditas',
        'stok_kg',
        'harga_beli_aktif',
        'harga_jual_aktif',
        'harga_modal_rata2',
    ];

    protected $casts = [
        'stok_kg'           => 'decimal:2',
        'harga_beli_aktif'  => 'decimal:2',
        'harga_jual_aktif'  => 'decimal:2',
        'harga_modal_rata2' => 'decimal:2',
    ];

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }

    public function penyesuaianStoks(): HasMany
    {
        return $this->hasMany(PenyesuaianStok::class);
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok_kg <= 0) return 'Habis';
        if ($this->stok_kg < 100) return 'Menipis';
        return 'Tersedia';
    }

    public function getStatusStokColorAttribute(): string
    {
        if ($this->stok_kg <= 0) return 'red';
        if ($this->stok_kg < 100) return 'yellow';
        return 'green';
    }
}
