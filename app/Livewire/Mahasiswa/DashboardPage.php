<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DamageReport; // Pastikan model ini ada
use App\Models\Item; // Untuk link ke detail item jika perlu

class DashboardPage extends Component
{
    public $recentDamageReports;
    public $totalMyReports;
    public $myOpenReports;
    public $myResolvedReports;

    public function mount()
    {
        $user = Auth::user();

        // Ambil beberapa laporan kerusakan terbaru dari pengguna ini
        $this->recentDamageReports = $user->damageReports() // Asumsi relasi damageReports() ada di model User
            ->with('item:id,name') // Eager load nama item
            ->latest('reported_at') // Urutkan berdasarkan tanggal lapor terbaru
            ->take(5) // Ambil 5 laporan terbaru
            ->get();

        // Statistik Laporan Pengguna   
        $this->totalMyReports = $user->damageReports()->count();
        $this->myOpenReports = $user->damageReports()
            ->whereIn('status', ['dilaporkan', 'diverifikasi', 'dalam_perbaikan'])
            ->count();
        $this->myResolvedReports = $user->damageReports()
            ->whereIn('status', ['selesai_diperbaiki', 'dihapuskan'])
            ->count();
    }

    public function render()
    {
        return view('livewire.mahasiswa.dashboard-page')
            ->layout('layouts.app'); // Menggunakan layout default Jetstream (x-app-layout)
    }
}
