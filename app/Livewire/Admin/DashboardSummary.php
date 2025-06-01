<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Item;
use App\Models\Category;
use App\Models\DamageReport;
use App\Models\User;

class DashboardSummary extends Component
{
    // Properti untuk menyimpan data ringkasan
    public $totalItems;
    public $totalItemQuantity;
    public $itemsByCondition = [];
    public $recentItems;

    public $totalDamageReports;
    public $openDamageReports;
    public $recentDamageReports;

    public $totalCategories;
    public $totalUsers;
    public $totalAdminUsers;
    public $totalMahasiswaUsers;

    public function mount()
    {
        // Data Item
        $this->totalItems = Item::count();
        $this->totalItemQuantity = Item::sum('quantity');
        $this->itemsByCondition = [
            'baik' => Item::where('condition', 'baik')->count(),
            'rusak_ringan' => Item::where('condition', 'rusak ringan')->count(),
            'rusak_berat' => Item::where('condition', 'rusak berat')->count(),
            'perlu_investigasi' => Item::where('condition', 'perlu investigasi')->count(),
        ];
        $this->recentItems = Item::with('category')->latest()->take(5)->get();

        // Data Laporan Kerusakan
        $this->totalDamageReports = DamageReport::count();
        $this->openDamageReports = DamageReport::whereIn('status', ['dilaporkan', 'diverifikasi', 'dalam_perbaikan'])->count();
        $this->recentDamageReports = DamageReport::with(['item:id,name', 'reporter:id,name'])->latest('reported_at')->take(5)->get();

        // Data Kategori
        $this->totalCategories = Category::count();

        // Data Pengguna
        $this->totalUsers = User::count();
        $this->totalAdminUsers = User::where('role', 'admin')->count();
        $this->totalMahasiswaUsers = User::where('role', 'mahasiswa')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard-summary')
            ->layout('layouts.app'); // Menggunakan layout default Jetstream (x-app-layout)
        // atau 'layouts.admin' jika Anda punya layout admin terpisah
    }
}
