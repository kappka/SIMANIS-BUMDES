<?php

namespace App\Exports;

use App\Models\BiayaOperasional;
use App\Models\Penjualan;
use Shuchkin\SimpleXLSXGen;

class LaporanExport
{
    public function __construct(
        private string $dari,
        private string $sampai,
        private ?string $komId
    ) {}

    public function download(string $filename)
    {
        $penjualans = Penjualan::with('komoditas')
            ->whereBetween('tanggal', [$this->dari, $this->sampai])
            ->when($this->komId, fn ($q) => $q->where('komoditas_id', $this->komId))
            ->orderBy('tanggal')
            ->get();

        $rowsPenjualan = [
            ['<b>No. Nota</b>', '<b>Tanggal</b>', '<b>Pembeli</b>', '<b>Komoditas</b>', '<b>Berat (Kg)</b>', '<b>Harga Jual/Kg</b>', '<b>HPP/Kg</b>', '<b>Total (Rp)</b>', '<b>Laba Kotor (Rp)</b>', '<b>Status</b>'],
        ];

        foreach ($penjualans as $p) {
            $rowsPenjualan[] = [
                $p->no_nota,
                $p->tanggal->format('d/m/Y'),
                $p->nama_pembeli,
                $p->komoditas->nama_komoditas,
                (float) $p->berat_kg,
                (float) $p->harga_jual_satuan,
                (float) $p->hpp_satuan,
                (float) $p->total_nominal,
                (float) (($p->harga_jual_satuan - $p->hpp_satuan) * $p->berat_kg),
                $p->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas',
            ];
        }

        $biayas = BiayaOperasional::whereBetween('tanggal', [$this->dari, $this->sampai])
            ->orderBy('tanggal')
            ->get();

        $rowsBiaya = [
            ['<b>Tanggal</b>', '<b>Kategori</b>', '<b>Keterangan</b>', '<b>Nominal (Rp)</b>'],
        ];

        foreach ($biayas as $b) {
            $rowsBiaya[] = [
                $b->tanggal->format('d/m/Y'),
                $b->label_kategori,
                $b->keterangan,
                (float) $b->nominal,
            ];
        }

        $xlsx = SimpleXLSXGen::fromArray($rowsPenjualan, 'Data Penjualan');
        $xlsx->addSheet($rowsBiaya, 'Biaya Operasional');

        return response()->streamDownload(function () use ($xlsx) {
            echo $xlsx;
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
