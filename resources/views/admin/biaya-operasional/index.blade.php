@extends('layouts.admin')
@section('title', 'Biaya Operasional')
@section('page-title', 'Biaya Operasional')
@section('content')

<div class="grid lg:grid-cols-5 gap-6">
    {{-- Form Input --}}
    <div class="lg:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-200">
        <h3 class="font-semibold text-gray-700 mb-4">Catat Biaya Operasional</h3>
        <form method="POST" action="{{ route('admin.biaya-operasional.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="lahan_bumdes">Lahan BUMDes (pupuk, benih, traktor, buruh)</option>
                    <option value="angkut_timbang">Angkut & Timbang</option>
                    <option value="kemasan_karung">Kemasan & Karung</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Keterangan <span class="text-red-500">*</span></label>
                <input type="text" name="keterangan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="nominal" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" min="0" step="1000" required>
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg text-sm font-medium">Simpan Biaya</button>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">Riwayat Biaya Operasional</h3>
            <span class="text-sm text-gray-500">Total: <strong class="text-orange-600">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($biayas as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2.5">{{ $b->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-2.5">
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">{{ $b->label_kategori }}</span>
                            </td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $b->keterangan }}</td>
                            <td class="px-4 py-2.5 text-right font-medium">{{ number_format($b->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-2.5">
                                <form method="POST" action="{{ route('admin.biaya-operasional.destroy', $b) }}" class="inline"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400">Belum ada data biaya.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-100">{{ $biayas->links() }}</div>
    </div>
</div>
@endsection
