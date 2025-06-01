<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Selamat Datang di SISINPEM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(90deg, rgba(59, 130, 246, 1) 0%, rgba(37, 99, 235, 1) 100%);
            /* Gradasi biru */
        }
    </style>
</head>

<body class="antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <img class="h-24 w-auto mr-2" src="{{ asset('images/icon-web.png') }}" alt="SISINPEM Logo">
                        </a>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="text-sm font-medium text-gray-700 hover:text-blue-700">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-md transition duration-150 ease-in-out">Login</a>
                                {{-- Tombol Daftar Dihapus Dari Sini --}}
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <header class="hero-gradient text-white flex-grow flex items-center">
            <div class="container mx-auto px-6 py-16 text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold leading-tight mb-4">
                    Selamat Datang di SISINPEM
                </h1>
                <p class="text-lg sm:text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Sistem Informasi Inventaris Pembelajaran yang membantu Anda mengelola aset dan sumber daya
                    pembelajaran dengan lebih efisien, transparan, dan mudah.
                </p>
                <div class="flex justify-center space-x-4">
                    @if (Route::has('login'))
                        @guest
                            <a href="{{ route('login') }}"
                                class="bg-white text-blue-700 font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-blue-50 transition duration-300 ease-in-out text-lg">
                                Login Sekarang
                            </a>
                            {{-- Tombol Buat Akun Baru Dihapus Dari Sini --}}
                        @else
                            <a href="{{ route('dashboard') }}"
                                class="bg-white text-blue-700 font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-blue-50 transition duration-300 ease-in-out text-lg">
                                Masuk ke Dashboard
                            </a>
                        @endguest
                    @endif
                </div>
            </div>
        </header>

        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Fitur Unggulan SISINPEM</h2>
                <p class="text-center text-gray-600 mb-12 max-w-xl mx-auto">Beberapa kemudahan yang akan Anda dapatkan
                    untuk pengelolaan inventaris yang lebih baik.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="text-blue-700 mb-4 flex justify-center">
                            {{-- Ikon untuk Pencatatan Akurat --}}
                            <x-heroicon-o-clipboard-document-check class="w-16 h-16" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Pencatatan Akurat</h3>
                        <p class="text-gray-600">Catat semua data inventaris secara detail dan terpusat, mengurangi
                            risiko kehilangan atau kesalahan data.</p>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="text-blue-700 mb-4 flex justify-center">
                            {{-- Ikon untuk Pelaporan Kerusakan Mudah --}}
                            <x-heroicon-o-exclamation-triangle class="w-16 h-16" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Pelaporan Kerusakan Mudah</h3>
                        <p class="text-gray-600">Mahasiswa dapat dengan mudah melaporkan kerusakan barang, mempercepat
                            proses perbaikan dan pemeliharaan.</p>
                    </div>
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="text-blue-700 mb-4 flex justify-center">
                            {{-- Ikon untuk Monitoring Efisien --}}
                            <x-heroicon-o-computer-desktop class="w-16 h-16" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Monitoring Efisien</h3>
                        <p class="text-gray-600">Admin dapat memantau kondisi, jumlah, dan status laporan kerusakan
                            barang secara real-time melalui dashboard.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center">
                {{-- <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} SISINPEM - Sistem Informasi Inventaris Pembelajaran.
                    Dibuat oleh Kelompok 2 MIC2023. All rights reserved.
                </p> --}}
                {{-- Alternatif lain jika ingin dipisah dengan baris atau simbol: --}}
                
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} SISINPEM - Sistem Informasi Inventaris Pembelajaran. All rights reserved.
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Kelompok 2 MIC Angkatan 2023
                </p>
               
            </div>
        </footer>
    </div>
</body>

</html>
