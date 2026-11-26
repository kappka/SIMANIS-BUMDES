<?php

namespace Tests\Feature;

use App\Models\BarangMasuk;
use App\Models\BiayaOperasional;
use App\Models\Komoditas;
use App\Models\Lahan;
use App\Models\Pengaturan;
use App\Models\Penjualan;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SimanisBlueprintTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin BUMDes',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Pengaturan::create([
            'nama_bumdes' => 'BUMDes Payungsari',
            'alamat_bumdes' => 'Desa Payungsari',
            'no_wa_stok' => '6281234567890',
            'nama_ketua' => 'Ketua BUMDes',
        ]);
    }

    public function test_public_page_loads_and_shows_komoditas_and_whatsapp_link(): void
    {
        Komoditas::create([
            'nama_komoditas' => 'Gabah Kering Panen',
            'stok_kg' => 500,
            'harga_beli_aktif' => 5500,
            'harga_jual_aktif' => 6000,
            'harga_modal_rata2' => 5500,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Gabah Kering Panen');
        $response->assertSee('https://wa.me/6281234567890');
    }

    public function test_role_middleware_isolates_admin_and_petani_zones(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/petani/dashboard')->assertRedirect('/login');

        $petaniUser = User::create([
            'name' => 'Pak Budi',
            'username' => '08111222333',
            'password' => Hash::make('08111222333'),
            'role' => 'petani',
        ]);
        Petani::create([
            'user_id' => $petaniUser->id,
            'nik' => '321300000001',
            'nama' => 'Pak Budi',
            'no_hp' => '08111222333',
            'alamat_dusun' => 'Dusun Krajan',
        ]);

        // Petani tidak bisa akses /admin/*
        $this->actingAs($petaniUser)->get('/admin/dashboard')->assertRedirect('/petani/dashboard');
        $this->actingAs($petaniUser)->get('/petani/dashboard')->assertStatus(200);

        // Admin tidak bisa akses /petani/*
        $this->actingAs($this->admin)->get('/petani/dashboard')->assertRedirect('/admin/dashboard');
        $this->actingAs($this->admin)->get('/admin/dashboard')->assertStatus(200);
    }

    public function test_petani_can_login_with_nik_or_no_hp(): void
    {
        $petaniUser = User::create([
            'name' => 'Pak Slamet',
            'username' => '08555666777',
            'password' => Hash::make('08555666777'),
            'role' => 'petani',
        ]);
        Petani::create([
            'user_id' => $petaniUser->id,
            'nik' => '321399998888',
            'nama' => 'Pak Slamet',
            'no_hp' => '08555666777',
            'alamat_dusun' => 'Dusun Sukamaju',
        ]);

        // Login via NIK
        $this->post('/login', [
            'username' => '321399998888',
            'password' => '08555666777',
        ])->assertRedirect('/petani/dashboard');

        $this->post('/logout');

        // Login via No. HP
        $this->post('/login', [
            'username' => '08555666777',
            'password' => '08555666777',
        ])->assertRedirect('/petani/dashboard');
    }

    public function test_barang_masuk_penjualan_hpp_stock_opname_and_exports(): void
    {
        $this->actingAs($this->admin);

        // 1. Tambah komoditas & update harga
        $this->post('/admin/komoditas', [
            'nama_komoditas' => 'Beras Medium',
            'harga_beli_aktif' => 10000,
            'harga_jual_aktif' => 12000,
        ])->assertRedirect();

        $komoditas = Komoditas::where('nama_komoditas', 'Beras Medium')->firstOrFail();

        $this->put("/admin/komoditas/{$komoditas->id}", [
            'harga_beli_aktif' => 10500,
            'harga_jual_aktif' => 12500,
        ])->assertRedirect();
        $this->assertEquals(10500, $komoditas->fresh()->harga_beli_aktif);

        // 2. Tambah petani & lahan BUMDes
        $this->post('/admin/petani', [
            'nama' => 'Bu Siti',
            'nik' => '321311112222',
            'no_hp' => '081299990000',
            'alamat_dusun' => 'Dusun Barat',
        ])->assertRedirect();
        $petani = Petani::where('nama', 'Bu Siti')->firstOrFail();

        $this->post('/admin/lahan', [
            'petani_id' => null,
            'nama_blok' => 'Blok Sawah BUMDes A',
            'luas_hektar' => 2.5,
            'komoditas_tanam' => 'Padi',
        ])->assertRedirect();
        $lahanBumdes = Lahan::whereNull('petani_id')->firstOrFail();

        // 3. Barang Masuk: Beli dari Petani (100 Kg @ Rp 10.000, belum lunas)
        $this->post('/admin/barang-masuk', [
            'tanggal' => '2026-11-26',
            'sumber' => 'beli_petani',
            'komoditas_id' => $komoditas->id,
            'petani_id' => $petani->id,
            'berat_kg' => 100,
            'harga_satuan' => 10000,
            'status_bayar' => 'belum_lunas',
        ])->assertRedirect();

        $komoditas->refresh();
        $this->assertEquals(100, (float) $komoditas->stok_kg);
        $this->assertEquals(10000, (float) $komoditas->harga_modal_rata2);

        $bm1 = BarangMasuk::firstOrFail();
        $this->assertStringStartsWith('BM-20261126-', $bm1->no_nota);

        // Tandai lunas 1 klik
        $this->patch("/admin/barang-masuk/{$bm1->id}/lunas")->assertRedirect();
        $this->assertEquals('lunas', $bm1->fresh()->status_bayar);

        // 4. Barang Masuk: Panen Lahan BUMDes (100 Kg -> harga otomatis 0, HPP rata2 menjadi 5000)
        $this->post('/admin/barang-masuk', [
            'tanggal' => '2026-11-26',
            'sumber' => 'panen_bumdes',
            'komoditas_id' => $komoditas->id,
            'lahan_id' => $lahanBumdes->id,
            'berat_kg' => 100,
            'harga_satuan' => 99999, // harus diabaikan/terkunci ke 0
            'status_bayar' => 'belum_lunas', // harus terkunci ke lunas
        ])->assertRedirect();

        $komoditas->refresh();
        $this->assertEquals(200, (float) $komoditas->stok_kg);
        $this->assertEquals(5000, (float) $komoditas->harga_modal_rata2);

        // 5. Validasi anti-stok minus saat penjualan
        $this->post('/admin/penjualan', [
            'tanggal' => '2026-11-26',
            'nama_pembeli' => 'Tengkulak Jaya',
            'komoditas_id' => $komoditas->id,
            'berat_kg' => 250,
            'harga_jual_satuan' => 12000,
            'status_bayar' => 'lunas',
        ])->assertSessionHasErrors('berat_kg');

        // 6. Penjualan valid (50 Kg @ Rp 12.000, HPP terkunci 5000)
        $this->post('/admin/penjualan', [
            'tanggal' => '2026-11-26',
            'nama_pembeli' => 'Tengkulak Jaya',
            'komoditas_id' => $komoditas->id,
            'berat_kg' => 50,
            'harga_jual_satuan' => 12000,
            'status_bayar' => 'belum_lunas',
        ])->assertRedirect();

        $komoditas->refresh();
        $this->assertEquals(150, (float) $komoditas->stok_kg);

        $pj = Penjualan::firstOrFail();
        $this->assertStringStartsWith('PJ-20261126-', $pj->no_nota);
        $this->assertEquals(5000, (float) $pj->hpp_satuan);
        $this->assertEquals(350000, (float) $pj->laba_kotor); // (12000 - 5000) * 50

        // 7. Stock Opname (susut 10 Kg)
        $this->post('/admin/penyesuaian-stok', [
            'tanggal' => '2026-11-26',
            'komoditas_id' => $komoditas->id,
            'jenis' => 'susut',
            'berat_kg' => 10,
            'alasan' => 'Susut pengeringan',
        ])->assertRedirect();
        $this->assertEquals(140, (float) $komoditas->fresh()->stok_kg);

        // 8. Biaya Operasional & Pengaturan WA normalization
        $this->post('/admin/biaya-operasional', [
            'tanggal' => '2026-11-26',
            'kategori' => 'lahan_bumdes',
            'keterangan' => 'Pupuk urea',
            'nominal' => 100000,
        ])->assertRedirect();

        $this->put('/admin/pengaturan', [
            'nama_bumdes' => 'BUMDes Payungsari',
            'alamat_bumdes' => 'Desa Payungsari',
            'no_wa_stok' => '081234567899',
            'nama_ketua' => 'Bapak Ketua',
        ])->assertRedirect();
        $this->assertEquals('6281234567899', Pengaturan::first()->no_wa_stok);

        // 9. Proteksi hapus komoditas yang punya transaksi
        $this->delete("/admin/komoditas/{$komoditas->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('komoditas', ['id' => $komoditas->id]);

        // 10. Cek PDF Nota & Laporan LPJ (PDF + Excel)
        $this->get("/admin/barang-masuk/{$bm1->id}/pdf")->assertStatus(200);
        $this->get("/admin/penjualan/{$pj->id}/pdf")->assertStatus(200);
        $this->get('/admin/laporan')->assertStatus(200)->assertSee('250.000'); // Laba bersih: 350.000 - 100.000
        $this->get('/admin/laporan/export-pdf')->assertStatus(200);
        $this->get('/admin/laporan/export-excel')->assertStatus(200);
    }
}
