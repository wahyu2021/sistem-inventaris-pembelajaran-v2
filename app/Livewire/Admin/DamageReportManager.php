<?php

namespace App\Livewire\Admin;

use App\Models\Item;
use App\Models\User;
use Livewire\Component;
use App\Models\DamageReport;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule; // Import Rule untuk validasi 'in'

class DamageReportManager extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk form
    public $reportId;
    public $item_id;
    public $reported_by_user_id;
    public $description;
    public $severity = 'ringan'; // <-- TAMBAHKAN PROPERTI BARU DENGAN DEFAULT
    public $status = 'dilaporkan';
    public $admin_notes;
    public $image_damage;
    public $newImageDamage;

    // Properti untuk UI
    public $isOpen = false;
    public $search = '';
    public $filterStatus = '';
    public $filterItem = '';
    public $filterSeverity = ''; // <-- TAMBAHKAN FILTER SEVERITY (OPSIONAL)

    public $itemsForForm;
    public $reportersForForm;
    public $allowedSeverities = []; // Akan diisi di mount()

    protected $paginationTheme = 'tailwind';
    protected $listeners = ['reportAdded' => '$refresh', 'reportUpdated' => '$refresh', 'reportDeleted' => '$refresh'];
    public $selectedReportDetail; // Untuk menyimpan objek laporan yang dipilih
    public $isReportDetailModalOpen = false;

    protected function rules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'reported_by_user_id' => 'required|exists:users,id',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in(DamageReport::$allowedSeverities)], // <-- VALIDASI UNTUK SEVERITY
            'status' => ['required', Rule::in(['dilaporkan', 'diverifikasi', 'dalam_perbaikan', 'selesai_diperbaiki', 'dihapuskan'])],
            'admin_notes' => 'nullable|string|max:1000',
            'newImageDamage' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'item_id.required' => 'Item wajib dipilih.',
        'reported_by_user_id.required' => 'Pelapor wajib diisi.',
        'description.required' => 'Deskripsi kerusakan wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'severity.required' => 'Tipe kerusakan wajib dipilih.',
        'severity.in' => 'Pilihan tipe kerusakan tidak valid.',
        'newImageDamage.image' => 'File harus berupa gambar.',
        'newImageDamage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount()
    {
        $this->itemsForForm = Item::orderBy('name')->get(['id', 'name']);
        $this->reportersForForm = User::where('role', 'mahasiswa')->orWhere('id', Auth::id())->orderBy('name')->get(['id', 'name']);
        $this->reported_by_user_id = Auth::id();
        $this->allowedSeverities = DamageReport::$allowedSeverities; // Ambil dari model
    }

    public function render()
    {
        $query = DamageReport::with(['item:id,name', 'reporter:id,name'])
            ->orderBy('reported_at', 'desc');

        // ... (logika search dan filterStatus, filterItem yang sudah ada) ...
        if (!empty($this->search)) { // Contoh
            $query->where(function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('item', function ($itemQuery) {
                        $itemQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }
        if (!empty($this->filterItem)) {
            $query->where('item_id', $this->filterItem);
        }

        // Filter berdasarkan severity (jika ada)
        if (!empty($this->filterSeverity)) {
            $query->where('severity', $this->filterSeverity);
        }

        $reports = $query->paginate(10);

        return view('livewire.admin.damage-report-manager', [
            'reports' => $reports,
            'allItems' => $this->itemsForForm, // Untuk filter item
        ])->layout('layouts.app'); // Ganti ke layouts.admin jika perlu
    }

    public function create()
    {
        $this->resetInputFields();
        $this->reported_by_user_id = Auth::id();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->reportId = null;
        $this->item_id = null;
        $this->description = '';
        $this->severity = 'ringan'; // <-- RESET SEVERITY KE DEFAULT
        $this->status = 'dilaporkan';
        $this->admin_notes = '';
        $this->image_damage = null;
        $this->newImageDamage = null;
        // $this->reported_by_user_id = Auth::id(); // Dibiarkan agar defaultnya tetap
    }

    public function store()
    {
        $validatedData = $this->validate();
        $imagePath = $this->image_damage;

        if ($this->newImageDamage) {
            if ($this->reportId && $this->image_damage) {
                Storage::disk('public')->delete($this->image_damage);
            }
            $imagePath = $this->newImageDamage->store('damage-reports', 'public');
        }

        $damageReportData = [
            'item_id' => $validatedData['item_id'],
            'reported_by_user_id' => $validatedData['reported_by_user_id'],
            'description' => $validatedData['description'],
            'severity' => $validatedData['severity'], // <-- TAMBAHKAN SEVERITY SAAT SIMPAN
            'status' => $validatedData['status'],
            'admin_notes' => $validatedData['admin_notes'],
            'image_damage' => $imagePath,
            'reported_at' => $this->reportId ? DamageReport::find($this->reportId)->reported_at : now(),
            'resolved_at' => ($validatedData['status'] === 'selesai_diperbaiki' || $validatedData['status'] === 'dihapuskan') ?
                ($this->reportId ? (DamageReport::find($this->reportId)->resolved_at ?? now()) : now()) :
                null,
        ];

        $damageReport = DamageReport::updateOrCreate(['id' => $this->reportId], $damageReportData);

        // ... (logika update kondisi item dan notifikasi yang sudah ada) ...
        $item = Item::find($validatedData['item_id']);
        if ($item) {
            // Update kondisi item berdasarkan severity dan status laporan
            if ($validatedData['severity'] === DamageReport::SEVERITY_BERAT || $validatedData['status'] === 'dihapuskan') {
                $item->condition = 'rusak berat';
            } elseif ($validatedData['severity'] === DamageReport::SEVERITY_SEDANG) {
                $item->condition = 'rusak ringan'; // Atau 'perlu investigasi'
            } elseif ($validatedData['status'] === 'selesai_diperbaiki') {
                $item->condition = 'baik';
            }
            // Anda bisa menambahkan logika lebih detail di sini
            $item->save();

            // Kirim notifikasi (jika laporan baru atau status relevan)
            $isNewReport = !$this->reportId;
            if ($isNewReport) {
                $admins = User::where('role', 'admin')->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new \App\Notifications\ItemDamagedNotification($item, $damageReport));
                }
            }
        }

        session()->flash('message', $this->reportId ? 'Laporan kerusakan berhasil diperbarui.' : 'Laporan kerusakan berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
        $this->dispatch($this->reportId ? 'reportUpdated' : 'reportAdded');
    }

    public function edit($id)
    {
        $report = DamageReport::findOrFail($id);
        $this->reportId = $id;
        $this->item_id = $report->item_id;
        $this->reported_by_user_id = $report->reported_by_user_id;
        $this->description = $report->description;
        $this->severity = $report->severity; // <-- LOAD SEVERITY SAAT EDIT
        $this->status = $report->status;
        $this->admin_notes = $report->admin_notes;
        $this->image_damage = $report->image_damage;
        $this->newImageDamage = null;

        $this->openModal();
    }

    public function delete($id)
    {
        // ... (logika delete yang sudah ada) ...
        $report = DamageReport::findOrFail($id);
        if ($report->image_damage) {
            Storage::disk('public')->delete($report->image_damage);
        }
        $report->delete();
        session()->flash('message', 'Laporan kerusakan berhasil dihapus.');
        $this->dispatch('reportDeleted');
    }

    public function updatingNewImageDamage()
    {
        $this->resetErrorBag('newImageDamage');
        $this->resetValidation('newImageDamage');
    }

    public function showReportDetail($reportId)
    {
        $this->selectedReportDetail = DamageReport::with(['item', 'reporter'])->find($reportId);
        if ($this->selectedReportDetail) {
            $this->isReportDetailModalOpen = true;
        }
    }

    public function closeReportDetailModal()
    {
        $this->isReportDetailModalOpen = false;
        $this->selectedReportDetail = null;
    }
}
