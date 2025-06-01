<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengakses Auth

// Import Livewire Components (sesuaikan namespace jika berbeda)
// Admin
use App\Livewire\Admin\ItemManager as AdminItemManager;
use App\Livewire\Admin\DamageReportManager;
use App\Livewire\Admin\NotificationManager;
use App\Livewire\Admin\DashboardSummary as AdminDashboard; // Nama komponen baru kita
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Mahasiswa\DashboardPage as MahasiswaDashboard;
use App\Livewire\Mahasiswa\ItemViewer;
use App\Livewire\Mahasiswa\ReportDamageForm;

// Mahasiswa

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user && method_exists($user, 'isMahasiswa') && $user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }
    })->name('dashboard'); // Nama rute ini penting karena Jetstream mengarah ke sini setelah login

    // === GRUP RUTE ADMIN ===
    // Semua rute di dalam grup ini otomatis terproteksi oleh middleware auth di atas
    // Ditambah proteksi middleware 'role:admin'
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/kategori', CategoryManager::class)->name('categories.index');
        Route::get('/inventaris', AdminItemManager::class)->name('items.index');
        Route::get('/laporan-kerusakan', DamageReportManager::class)->name('damages.index');
        Route::get('/notifikasi', NotificationManager::class)->name('notifications.index');
    });


    // === GRUP RUTE MAHASISWA ===
    // Semua rute di dalam grup ini otomatis terproteksi oleh middleware auth di atas
    // Ditambah proteksi middleware 'role:mahasiswa'
    // === GRUP RUTE MAHASISWA ===
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', MahasiswaDashboard::class)->name('dashboard'); // Pastikan DashboardPage ada atau ganti dengan view
        Route::get('/cek-barang', ItemViewer::class)->name('items.index');
        Route::get('/lapor-kerusakan/{item?}', ReportDamageForm::class)->name('damages.report');
        // Route::get('/laporan-saya', MyDamageReports::class)->name('damages.my');
    });
    // Rute lain yang memerlukan autentikasi tetapi tidak spesifik peran bisa diletakkan di sini
    // Misalnya, halaman profil pengguna yang mungkin di-handle oleh Jetstream/Fortify.
});