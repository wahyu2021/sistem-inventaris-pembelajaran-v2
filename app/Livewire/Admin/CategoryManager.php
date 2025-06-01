<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str; // Jika Anda menggunakan Str::limit di view

class CategoryManager extends Component
{
    use WithPagination;

    // properti untuk form
    public $name, $description;
    public $categoryId; // Untuk menyimpan ID saat edit

    // properti untuk kontrol UI
    public $isOpen = false;
    public $search = '';

    // mengatur tema paginasi
    protected $paginationTheme = 'tailwind';

    // Aturan validasi, menggunakan metode agar bisa dinamis untuk update
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:categories,name,' . $this->categoryId, // unik kecuali dirinya sendiri saat update
            'description' => 'nullable|string|max:500',
        ];
    }

    // Pesan validasi kustom (opsional)
    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.min' => 'Nama kategori minimal 3 karakter.',
        'name.unique' => 'Nama kategori sudah ada.',
    ];

    // Untuk validasi real-time (opsional, dijalankan setiap kali properti diupdate)
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        // Query untuk mengambil kategori dengan paginasi dan pencarian
        $query = Category::query();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.category-manager', [
            'categories' => $categories
        ])->layout('layouts.app'); // Menggunakan layout default Jetstream/aplikasi Anda
    }

    /**
     * Mempersiapkan modal untuk membuat kategori baru.
     */
    public function create()
    {
        $this->resetInputFields(); // Kosongkan field form
        $this->openModal();        // Buka modal
    }

    /**
     * Membuka modal form.
     */
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag(); // Hapus pesan error validasi sebelumnya
        $this->resetValidation(); // Hapus status validasi sebelumnya
    }

    /**
     * Menutup modal form.
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * Mereset semua field input form.
     */
    private function resetInputFields(){
        $this->name = '';
        $this->description = '';
        $this->categoryId = null; // Penting untuk menandakan mode 'create'
    }

    /**
     * Menyimpan kategori baru atau memperbarui kategori yang sudah ada.
     */
    public function store()
    {
        // Validasi input berdasarkan $rules
        // Jika $this->categoryId ada, maka ini adalah update, jika tidak, ini adalah create
        // Metode rules() sudah menangani logika validasi unique yang berbeda untuk create dan update
        $this->validate();

        // Gunakan updateOrCreate untuk menangani create atau update
        Category::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Kirim pesan sukses ke session
        session()->flash('message',
            $this->categoryId ? 'Kategori berhasil diperbarui.' : 'Kategori berhasil ditambahkan.');

        $this->closeModal();        // Tutup modal
        $this->resetInputFields(); // Kosongkan field form untuk input selanjutnya
        // $this->dispatch('categorySaved'); // Opsional: emit event jika ada listener lain
    }

    /**
     * Mempersiapkan modal untuk mengedit kategori.
     * @param int $id ID kategori yang akan diedit
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id); // Ambil data kategori, error jika tidak ditemukan
        $this->categoryId = $id;              // Set ID untuk mode edit
        $this->name = $category->name;        // Isi field form dengan data yang ada
        $this->description = $category->description;

        $this->openModal(); // Buka modal
    }

    /**
     * Menghapus kategori.
     * (Anda juga perlu menambahkan metode ini jika ingin fungsionalitas hapus)
     * @param int $id ID kategori yang akan dihapus
     */
    public function delete($id)
    {
        // Opsi: Tambahkan pengecekan jika kategori masih memiliki item
        // $category = Category::withCount('items')->find($id);
        // if ($category && $category->items_count > 0) {
        //     session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki item terkait.');
        //     return;
        // }

        Category::find($id)->delete();
        session()->flash('message', 'Kategori berhasil dihapus.');
        // $this->dispatch('categoryDeleted'); // Opsional: emit event
    }
}