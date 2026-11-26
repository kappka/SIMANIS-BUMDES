<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    protected $fillable = [
        'no_nota',
        'tanggal',
        'nama_pembeli',
        'no_hp_pembeli',
        'komoditas_id',
        'berat_kg',
        'harga_jual_satuan',
        'hpp_satuan',
        'total_nominal',
        'status_bayar',
        'catatan',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'berat_kg'         => 'decimal:2',
        'harga_jual_satuan'=> 'decimal:2',
        'hpp_satuan'       => 'decimal:2',
        'total_nominal'    => 'decimal:2',
    ];

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function getLabaKotorAttribute(): float
    {
        return ($this->harga_jual_satuan - $this->hpp_satuan) * $this->berat_kg;
    }

    public function getLabelStatusAttribute(): string
    {
        return $this->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas';
    }
}
