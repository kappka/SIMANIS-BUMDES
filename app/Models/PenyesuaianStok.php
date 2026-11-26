<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyesuaianStok extends Model
{
    protected $fillable = [
        'tanggal',
        'komoditas_id',
        'jenis',
        'berat_kg',
        'alasan',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'berat_kg' => 'decimal:2',
    ];

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class);
    }

    public function getLabelJenisAttribute(): string
    {
        return $this->jenis === 'susut' ? 'Penyusutan' : 'Koreksi Tambah';
    }
}
