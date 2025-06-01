<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Item;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // Untuk upload gambar
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ItemStockUpdatedNotification;

class ItemManager extends Component
{
    use WithPagination, WithFileUploads;

    // Properti untuk form
    public $itemId;
    public $category_id;
    public $name;
    public $description;
    public $unique_code;
    public $quantity;
    public $condition = 'baik'; // Default value
    public $image; // Untuk menyimpan path gambar yang sudah ada
    public $newImage; // Untuk upload gambar baru
    public $location;

    // Properti untuk UI
    public $isOpen = false;
    public $search = '';
    public $filterCategory = ''; // Untuk filter berdasarkan kategori
    public $categories; // Untuk dropdown kategori

    protected $paginationTheme = 'tailwind';

    // Listener untuk event (misalnya refresh data setelah aksi)
    protected $listeners = ['itemAdded' => '$refresh', 'itemUpdated' => '$refresh', 'itemDeleted' => '$refresh'];

    // Aturan validasi
    protected function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'unique_code' => 'nullable|string|max:50|unique:items,unique_code,' . $this->itemId,
            'quantity' => 'required|integer|min:0',
            'condition' => 'required|string|in:baik,rusak ringan,rusak berat,perlu investigasi',
            'newImage' => 'nullable|image|max:2048', // Maks 2MB untuk gambar baru
            'location' => 'nullable|string|max:100',
        ];
    }

    // Pesan validasi kustom
    protected $messages = [
        'category_id.required' => 'Kategori wajib dipilih.',
        'newImage.image' => 'File harus berupa gambar.',
        'newImage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get(); // Ambil semua kategori untuk dropdown
    }

    public function render()
    {
        $query = Item::with('category')->orderBy('name', 'asc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('unique_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($catQuery) {
                        $catQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        $items = $query->paginate(10);

        return view('livewire.admin.item-manager', [
            'items' => $items,
        ])->layout('layouts.app'); // Sesuaikan dengan layout admin Anda
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag(); // Hapus error validasi sebelumnya
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->itemId = null;
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
        $this->unique_code = '';
        $this->quantity = 0;
        $this->condition = 'baik';
        $this->image = null;
        $this->newImage = null; // Reset properti untuk upload gambar baru
        $this->location = '';
    }

    public function store()
    {
        $validatedData = $this->validate();
        $imagePath = $this->image; // Path gambar lama (jika ada saat edit)
        $isNewItem = !$this->itemId; // Cek apakah ini item baru

        if ($this->newImage) {
            if ($this->itemId && $this->image) {
                Storage::disk('public')->delete($this->image);
            }
            $imagePath = $this->newImage->store('item-images', 'public');
        }

        $item = Item::updateOrCreate(['id' => $this->itemId], [
            'category_id' => $validatedData['category_id'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'unique_code' => $validatedData['unique_code'],
            'quantity' => $validatedData['quantity'],
            'condition' => $validatedData['condition'],
            'image' => $imagePath,
            'location' => $validatedData['location'],
        ]);

        // === KIRIM NOTIFIKASI JIKA ITEM BARU DITAMBAHKAN ===
        if ($isNewItem && $item) { // Pastikan $item adalah instance yang valid
            $admins = User::where('role', 'admin')->get();
            if ($admins->isNotEmpty()) {
                $actionMessage = 'Barang baru ditambahkan';
                Notification::send($admins, new ItemStockUpdatedNotification($item, $actionMessage, null, $item->quantity));
            }
        }
        // === AKHIR BAGIAN NOTIFIKASI ===
        // Opsional: Anda juga bisa menambahkan notifikasi jika stok item yang sudah ada bertambah signifikan saat update.
        // Ini memerlukan logika untuk menyimpan $oldQuantity sebelum update dan membandingkannya.
        // else if (!$isNewItem && $item && $item->wasChanged('quantity')) {
        //     $oldQuantity = $item->getOriginal('quantity');
        //     if ($item->quantity > $oldQuantity) {
        //         $admins = User::where('role', 'admin')->get();
        //         if ($admins->isNotEmpty()) {
        //             $actionMessage = 'Stok barang diperbarui';
        //             Notification::send($admins, new ItemStockUpdatedNotification($item, $actionMessage, $oldQuantity, $item->quantity));
        //         }
        //     }
        // }


        session()->flash('message', $this->itemId ? 'Data inventaris berhasil diperbarui.' : 'Data inventaris berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
        $this->dispatch($this->itemId ? 'itemUpdated' : 'itemAdded');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $this->itemId = $id;
        $this->category_id = $item->category_id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->unique_code = $item->unique_code;
        $this->quantity = $item->quantity;
        $this->condition = $item->condition;
        $this->image = $item->image; // Path gambar yang sudah ada
        $this->newImage = null; // Reset saat edit
        $this->location = $item->location;

        $this->openModal();
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);
        // Hapus gambar dari storage jika ada
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        session()->flash('message', 'Data inventaris berhasil dihapus.');
        $this->dispatch('itemDeleted'); // Emit event
    }

    // Untuk membersihkan file upload preview saat modal ditutup atau form di-reset
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingNewImage()
    {
        $this->resetErrorBag('newImage');
        $this->resetValidation('newImage');
    }
}
