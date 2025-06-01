<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Item;
use App\Models\DamageReport;
use App\Models\User; // Untuk mengambil admin
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ItemDamagedNotification; // Notifikasi untuk admin

class ReportDamageForm extends Component
{
    use WithFileUploads;

    public ?Item $item = null; // Item yang akan dilaporkan
    public $itemId;

    public $description;
    public $severity = 'ringan'; // Default severity
    public $newImageDamage; // Untuk upload foto kerusakan

    public $allowedSeverities = [];
    public $allItems; // Untuk dropdown jika item tidak dipilih dari awal

    protected function rules()
    {
        return [
            'itemId' => 'required|exists:items,id',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', \Illuminate\Validation\Rule::in(DamageReport::$allowedSeverities)],
            'newImageDamage' => 'nullable|image|max:2048', // Maks 2MB
        ];
    }

    protected $messages = [
        'itemId.required' => 'Item wajib dipilih.',
        'description.required' => 'Deskripsi kerusakan wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'severity.required' => 'Tipe kerusakan wajib dipilih.',
        'newImageDamage.image' => 'File harus berupa gambar.',
        'newImageDamage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount($item = null) // $item bisa berupa ID atau null
    {
        if ($item) {
            if ($item instanceof Item) {
                $this->item = $item;
                $this->itemId = $item->id;
            } else {
                $this->item = Item::find($item);
                $this->itemId = $item;
            }
        }
        $this->allItems = Item::orderBy('name')->get(['id', 'name']);
        $this->allowedSeverities = DamageReport::$allowedSeverities;
    }

    public function submitReport()
    {
        $validatedData = $this->validate();
        $imagePath = null;

        if ($this->newImageDamage) {
            $imagePath = $this->newImageDamage->store('damage-reports', 'public');
        }

        $damageReport = DamageReport::create([
            'item_id' => $this->itemId, // Gunakan $this->itemId yang sudah divalidasi
            'reported_by_user_id' => Auth::id(),
            'description' => $validatedData['description'],
            'severity' => $validatedData['severity'],
            'status' => 'dilaporkan', // Status awal
            'image_damage' => $imagePath,
            'reported_at' => now(),
        ]);

        // Ambil instance item yang sebenarnya untuk notifikasi
        $reportedItem = Item::find($this->itemId);

        if ($reportedItem && $damageReport) {
            // Kirim notifikasi ke admin
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ItemDamagedNotification($reportedItem, $damageReport));
            }

            // Update kondisi item berdasarkan laporan baru
            if ($reportedItem->condition !== 'rusak berat') { // Jangan timpa jika sudah rusak berat
                if ($validatedData['severity'] === DamageReport::SEVERITY_BERAT) {
                    $reportedItem->condition = 'rusak berat';
                } elseif ($validatedData['severity'] === DamageReport::SEVERITY_SEDANG && $reportedItem->condition === DamageReport::SEVERITY_RINGAN) {
                    $reportedItem->condition = 'rusak ringan'; // atau 'perlu investigasi'
                } elseif ($validatedData['severity'] === DamageReport::SEVERITY_RINGAN && $reportedItem->condition === 'baik') {
                    $reportedItem->condition = 'rusak ringan';
                } else if ($reportedItem->condition === 'baik') { // Jika kondisi awal baik dan ada laporan
                    $reportedItem->condition = 'perlu investigasi';
                }
                $reportedItem->save();
            }
        }

        session()->flash('message', 'Laporan kerusakan berhasil dikirim. Terima kasih!');
        // return redirect()->route('mahasiswa.items.index'); // Redirect ke daftar item
        // Atau reset form jika ingin tetap di halaman yang sama
        $this->reset(['description', 'severity', 'newImageDamage']);
        if (!$this->item) { // Jika item dipilih dari dropdown, reset juga itemId
            $this->reset('itemId');
        }
    }

    public function render()
    {
        return view('livewire.mahasiswa.report-damage-form')
            ->layout('layouts.app');
    }
}
