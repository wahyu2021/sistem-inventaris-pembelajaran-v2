<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Location;
use App\Models\DamageReport;
use App\Models\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DamageReportSubmittedNotification; // Pastikan notifikasi ini ada
use Illuminate\Validation\Rule;

class ReportDamageForm extends Component
{
    use WithFileUploads;

    public ?Location $location = null;
    public $locationId = null;
    public $locationSearch = '';
    public $locationSearchResults = [];
    public $selectedLocationId = null;
    public $selectedLocationName = '';
    public $description;
    public $severity = 'ringan';
    public $newImageDamage;
    public $allowedSeverities = [];

    public function mount($locationParam = null)
    {
        if (property_exists(DamageReport::class, 'allowedSeverities') && is_array(DamageReport::$allowedSeverities)) {
            $this->allowedSeverities = DamageReport::$allowedSeverities;
        } else {
            $this->allowedSeverities = ['ringan', 'sedang', 'parah'];
            // \Log::warning('ReportDamageForm: DamageReport::$allowedSeverities tidak terdefinisi atau bukan array.');
        }

        if ($locationParam) {
            if ($locationParam instanceof Location) {
                $this->location = $locationParam;
                $this->locationId = $this->location->id;
                $this->selectedLocationId = $this->location->id;
                $this->selectedLocationName = $this->location->name;
            } else {
                $foundLocation = Location::find($locationParam);
                if ($foundLocation) {
                    $this->location = $foundLocation;
                    $this->locationId = $foundLocation->id;
                    $this->selectedLocationId = $foundLocation->id;
                    $this->selectedLocationName = $foundLocation->name;
                }
            }
        }
    }

    protected function rules()
    {
        return [
            'selectedLocationId' => 'required|exists:locations,id',
            'description' => 'required|string|min:10|max:1000',
            'severity' => ['required', Rule::in($this->allowedSeverities)],
            'newImageDamage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected $messages = [
        'selectedLocationId.required' => 'Lokasi wajib dipilih atau sudah ditentukan dari URL.',
        'selectedLocationId.exists' => 'Lokasi yang dipilih tidak valid atau tidak ditemukan.',
        'description.required' => 'Deskripsi kerusakan wajib diisi.',
        'description.min' => 'Deskripsi minimal 10 karakter.',
        'severity.required' => 'Tingkat kerusakan wajib dipilih.',
        'newImageDamage.image' => 'File harus berupa gambar.',
        'newImageDamage.mimes' => 'Format gambar yang diizinkan adalah JPEG, PNG, JPG, GIF.',
        'newImageDamage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function updatedLocationSearch()
    {
        if (!$this->locationId) {
            $this->selectedLocationId = null;
            $this->selectedLocationName = '';
            $this->resetErrorBag('selectedLocationId');

            if (strlen($this->locationSearch) >= 2) {
                $this->locationSearchResults = Location::where('name', 'like', '%' . $this->locationSearch . '%')
                    ->orderBy('name')
                    ->limit(5)
                    ->get();
            } else {
                $this->locationSearchResults = [];
            }
        }
    }

    public function selectLocationFromSearch($locationId, $locationName)
    {
        $this->selectedLocationId = $locationId;
        $this->selectedLocationName = $locationName;
        $this->locationSearch = $locationName;
        $this->locationSearchResults = [];
        $this->resetErrorBag('selectedLocationId');
    }

    public function submitReport()
    {
        $validatedData = $this->validate();
        $imagePath = null;

        if ($this->newImageDamage) {
            $imagePath = $this->newImageDamage->store('damage-reports/locations', 'public');
        }

        $reportedByName = Auth::check() ? Auth::user()->name : 'Guest';
        $reportedByIdUser = Auth::id();

        DamageReport::create([
            'location_id' => $validatedData['selectedLocationId'],
            'reported_by' => $reportedByName,
            'reported_by_id_user' => $reportedByIdUser,
            'description' => $validatedData['description'],
            'severity' => $validatedData['severity'],
            'status' => 'dilaporkan',
            'image_damage' => $imagePath,
            'reported_at' => now(),
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            $latestReport = DamageReport::where('location_id', $validatedData['selectedLocationId'])
                                        ->where('reported_by_id_user', $reportedByIdUser)
                                        ->latest('reported_at')->first();
            if ($latestReport) {
                 Notification::send($admins, new DamageReportSubmittedNotification($latestReport));
            }
        }

        $this->resetFormFields();
        session()->flash('message', 'Laporan kerusakan berhasil dikirim. Terima kasih!');
        return redirect()->route('mahasiswa.locations.index');
    }

    public function resetFormFields()
    {
        $this->reset('description', 'severity', 'newImageDamage', 'locationSearch');
        if (!$this->locationId) {
            $this->reset('selectedLocationId', 'selectedLocationName', 'locationSearchResults');
        }
    }

    public function render()
    {
        $headerTitle = $this->selectedLocationName
            ? 'Laporkan Kerusakan: ' . $this->selectedLocationName
            : 'Laporkan Kerusakan Lokasi';

        return view('livewire.mahasiswa.report-damage-form', [ // Pastikan path ini benar
            'headerTitle' => $headerTitle
        ])->layout('layouts.app');
    }
}