<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Selamat Datang --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-8">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-medium text-gray-900">
                        Selamat Datang Kembali, {{ Auth::user()->name }}!
                    </h1>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Ini adalah ringkasan aktivitas dan informasi terkait penggunaan sistem inventaris pembelajaran.
                    </p>
                </div>
            </div>

            {{-- Kartu Statistik Laporan Anda --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-blue-700">
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Total Laporan Anda</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $totalMyReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-yellow-500">
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan Terbuka</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $myOpenReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 border-b-4 border-green-500">
                        <p class="text-sm text-gray-500 uppercase tracking-wider">Laporan Selesai</p>
                        <p class="text-3xl font-bold text-green-600">{{ $myResolvedReports }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Cepat --}}
            <div class="mb-8 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('mahasiswa.items.index') }}"
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    Lihat Semua Barang
                </a>
                <a href="{{ route('mahasiswa.damages.report') }}"
                    class="w-full sm:w-auto justify-center inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m-6-3a9 9 0 1118 0 9 9 0 01-18 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Laporkan Kerusakan Baru
                </a>
            </div>

            {{-- Daftar Laporan Kerusakan Terbaru Anda --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <h3 class="text-xl font-semibold text-blue-700 mb-4">Laporan Kerusakan Terbaru Anda</h3>
                    @if ($recentDamageReports && $recentDamageReports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Barang</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe Kerusakan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deskripsi Singkat</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Lapor</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($recentDamageReports as $report)
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $report->item->name ?? 'Item tidak tersedia' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($report->severity == 'ringan') bg-green-100 text-green-800
                                                    @elseif($report->severity == 'sedang') bg-yellow-100 text-yellow-800
                                                    @elseif($report->severity == 'berat') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ Str::title($report->severity) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 break-words">
                                                {{ Str::limit($report->description, 50) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $report->reported_at ? $report->reported_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800
                                                    @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-700
                                                    @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800
                                                    @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800
                                                    @elseif($report->status == 'dihapuskan') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($totalMyReports > $recentDamageReports->count())
                            <div class="mt-4 text-sm">
                                <a href="#" class="text-blue-700 hover:text-blue-900 hover:underline">
                                    Lihat semua laporan Anda ({{ $totalMyReports }}) &rarr;
                                </a>
                                {{-- Ganti href="#" dengan route('mahasiswa.damages.my') jika Anda membuat halaman tersebut --}}
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-gray-500">Anda belum pernah membuat laporan kerusakan.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
