<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = [
        'nama_bumdes',
        'alamat_bumdes',
        'no_wa_stok',
        'nama_ketua',
    ];

    public static function getAktif(): self
    {
        return self::firstOrCreate([], [
            'nama_bumdes'   => 'BUMDes Payungsari',
            'alamat_bumdes' => 'Desa Payungsari, Kecamatan Pabuaran, Kabupaten Subang',
            'no_wa_stok'    => '6281234567890',
            'nama_ketua'    => 'Ketua BUMDes',
        ]);
    }

    public function getNormalizedNoWaAttribute(): string
    {
        $no = preg_replace('/\D/', '', $this->no_wa_stok);
        if (str_starts_with($no, '0')) {
            $no = '62' . substr($no, 1);
        }
        return $no;
    }
}
