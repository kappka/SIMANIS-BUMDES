<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiayaOperasional extends Model
{
    protected $fillable = [
        'tanggal',
        'kategori',
        'keterangan',
        'nominal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function getLabelKategoriAttribute(): string
    {
        return match ($this->kategori) {
            'lahan_bumdes'   => 'Lahan BUMDes',
            'angkut_timbang' => 'Angkut & Timbang',
            'kemasan_karung' => 'Kemasan & Karung',
            default          => 'Lainnya',
        };
    }
}
