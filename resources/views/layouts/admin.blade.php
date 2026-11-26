<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Simanis BUMDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" @click="sidebarOpen=false"
     class="fixed inset-0 bg-black/40 z-20 lg:hidden" x-cloak></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-green-800 flex flex-col transition-transform lg:translate-x-0">
    <div class="px-6 py-5 border-b border-green-700">
        <span class="text-white font-bold text-lg block leading-tight">🌾 Simanis</span>
        <span class="text-green-300 text-xs">BUMDes Payungsari</span>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            📊 Dashboard
        </a>
        <a href="{{ route('admin.komoditas.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.komoditas.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            🌾 Stok & Harga
        </a>
        <a href="{{ route('admin.petani.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.petani.*','admin.lahan.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            👨‍🌾 Petani & Lahan
        </a>
        <a href="{{ route('admin.barang-masuk.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.barang-masuk.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            📥 Barang Masuk
        </a>
        <a href="{{ route('admin.penjualan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.penjualan.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            📤 Penjualan
        </a>
        <div class="pt-2 pb-1 px-4 text-xs text-green-400 uppercase tracking-wider">Keuangan</div>
        <a href="{{ route('admin.biaya-operasional.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.biaya-operasional.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            💰 Biaya Operasional
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.laporan.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            📋 Laporan LPJ
        </a>
        <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pengaturan.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700/60' }}">
            ⚙️ Pengaturan
        </a>
    </nav>
    <div class="p-4 border-t border-green-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left text-green-300 hover:text-white text-sm px-2 py-1">
                🚪 Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main area --}}
<div class="lg:pl-64 min-h-screen flex flex-col">
    {{-- Topbar --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="flex items-center justify-between px-4 py-3">
            <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                ☰
            </button>
            <div class="text-sm font-semibold text-gray-700">@yield('page-title', 'Admin BUMDes')</div>
            <div class="text-sm text-gray-500">{{ auth()->user()->name }}</div>
        </div>
    </header>

    <main class="flex-1 p-4 lg:p-6">
        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                ❌ @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>
