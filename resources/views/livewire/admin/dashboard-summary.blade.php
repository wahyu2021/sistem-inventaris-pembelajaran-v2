<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Statistik Utama --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Item --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Total Item</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $totalItems }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Kuantitas Item --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Total Kuantitas</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $totalItemQuantity }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Laporan Kerusakan Terbuka --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-yellow-500"> {{-- Warna berbeda untuk penekanan --}}
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan Rusak Terbuka</p>
                                <p class="text-3xl font-bold text-yellow-700">{{ $openDamageReports }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total Kategori --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-blue-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wider">Total Kategori</p>
                                <p class="text-3xl font-bold text-blue-700">{{ $totalCategories }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Detail Kondisi Item dan Pengguna --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Kondisi Item --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4">Kondisi Item Inventaris</h3>
                        <ul class="space-y-2">
                            @foreach ($itemsByCondition as $condition => $count)
                                <li class="flex justify-between items-center text-sm">
                                    <span
                                        class="text-gray-600">{{ Str::title(str_replace('_', ' ', $condition)) }}:</span>
                                    <span
                                        class="font-medium text-gray-800 py-1 px-2 rounded
                                    @if ($condition == 'baik') bg-green-100 text-green-700
                                    @elseif($condition == 'rusak_ringan') bg-yellow-100 text-yellow-700
                                    @elseif($condition == 'rusak_berat') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                        {{ $count }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{-- Jumlah Pengguna --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4">Ringkasan Pengguna</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex justify-between items-center"><span class="text-gray-600">Total
                                    Pengguna:</span> <span class="font-medium text-gray-800">{{ $totalUsers }}</span>
                            </li>
                            <li class="flex justify-between items-center"><span class="text-gray-600">Admin:</span>
                                <span class="font-medium text-gray-800">{{ $totalAdminUsers }}</span></li>
                            <li class="flex justify-between items-center"><span class="text-gray-600">Mahasiswa:</span>
                                <span class="font-medium text-gray-800">{{ $totalMahasiswaUsers }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Daftar Item Terbaru dan Laporan Kerusakan Terbaru --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Item Terbaru --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4">5 Item Terbaru Ditambahkan</h3>
                        @if ($recentItems->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($recentItems as $item)
                                    <li class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $item->name }}</p>
                                            <p class="text-xs text-gray-500">Kategori:
                                                {{ $item->category->name ?? 'N/A' }} | Jumlah: {{ $item->quantity }}
                                            </p>
                                        </div>
                                        <span
                                            class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada item baru.</p>
                        @endif
                    </div>
                </div>

                {{-- Laporan Kerusakan Terbaru --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4">5 Laporan Kerusakan Terbaru</h3>
                        @if ($recentDamageReports->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($recentDamageReports as $report)
                                    <li class="py-3">
                                        <div class="flex justify-between items-center">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ Str::limit($report->item->name ?? 'Item tidak diketahui', 25) }} -
                                                <span
                                                    class="font-normal">{{ Str::limit($report->description, 30) }}</span>
                                            </p>
                                            <span
                                                class="text-xs text-gray-500">{{ $report->reported_at ? $report->reported_at->diffForHumans() : $report->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500">Pelapor:
                                            {{ $report->reporter->name ?? 'N/A' }} | Status: <span
                                                class="font-semibold
                                    @if ($report->status == 'dilaporkan') text-yellow-600
                                    @elseif($report->status == 'diverifikasi') text-blue-600
                                    @elseif($report->status == 'dalam_perbaikan') text-indigo-600
                                    @elseif($report->status == 'selesai_diperbaiki') text-green-600
                                    @elseif($report->status == 'dihapuskan') text-red-600
                                    @else text-gray-600 @endif">
                                                {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                            </span></p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada laporan kerusakan baru.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
