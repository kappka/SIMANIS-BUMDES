<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lahan extends Model
{
    protected $fillable = [
        'petani_id',
        'nama_blok',
        'luas_hektar',
        'komoditas_tanam',
        'estimasi_panen',
    ];

    protected $casts = [
        'estimasi_panen' => 'date',
    ];

    public function petani(): BelongsTo
    {
        return $this->belongsTo(Petani::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function isMilikBumdes(): bool
    {
        return is_null($this->petani_id);
    }
}
