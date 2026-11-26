<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $fillable = [
        'no_nota',
        'tanggal',
        'sumber',
        'petani_id',
        'lahan_id',
        'komoditas_id',
        'berat_kg',
        'harga_satuan',
        'total_nominal',
        'status_bayar',
        'catatan',
    ];

    protected $casts = [
        'tanggal'       => 'date',
        'berat_kg'      => 'decimal:2',
        'harga_satuan'  => 'decimal:2',
        'total_nominal' => 'decimal:2',
    ];

    public function petani(): BelongsTo
    {
        return $this->belongsTo(Petani::class);
    }

    public function lahan(): BelongsTo
    {
        return $this->belongsTo(Lahan::class);
    }

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function getLabelSumberAttribute(): string
    {
        return $this->sumber === 'beli_petani' ? 'Beli dari Petani' : 'Panen Lahan BUMDes';
    }

    public function getLabelStatusAttribute(): string
    {
        return $this->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas';
    }
}
