<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Admin BUMDes Payungsari',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Data default pengaturan
        Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'nama_bumdes'   => 'BUMDes Payungsari',
                'alamat_bumdes' => 'Desa Payungsari, Kecamatan Pabuaran, Kabupaten Subang, Jawa Barat',
                'no_wa_stok'    => '6281234567890',
                'nama_ketua'    => 'Kepala BUMDes Payungsari',
            ]
        );

        // Contoh komoditas awal
        $komoditas = [
            ['nama_komoditas' => 'Gabah Kering Panen', 'harga_beli_aktif' => 5500, 'harga_jual_aktif' => 6000],
            ['nama_komoditas' => 'Gabah Kering Giling', 'harga_beli_aktif' => 6200, 'harga_jual_aktif' => 6800],
            ['nama_komoditas' => 'Beras Medium',        'harga_beli_aktif' => 10000, 'harga_jual_aktif' => 11500],
            ['nama_komoditas' => 'Beras Premium',       'harga_beli_aktif' => 13000, 'harga_jual_aktif' => 14500],
            ['nama_komoditas' => 'Jagung Pipilan',      'harga_beli_aktif' => 4500, 'harga_jual_aktif' => 5000],
        ];

        foreach ($komoditas as $k) {
            \App\Models\Komoditas::firstOrCreate(
                ['nama_komoditas' => $k['nama_komoditas']],
                array_merge($k, ['stok_kg' => 0, 'harga_modal_rata2' => 0])
            );
        }
    }
}
