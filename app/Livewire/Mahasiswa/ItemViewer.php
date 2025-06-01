<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Item;
use App\Models\Category;
use Livewire\WithPagination;

class ItemViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $categories;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset paginasi saat pencarian berubah
    }

    public function updatingFilterCategory()
    {
        $this->resetPage(); // Reset paginasi saat filter kategori berubah
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

        $items = $query->paginate(12); // Jumlah item per halaman

        return view('livewire.mahasiswa.item-viewer', [
            'items' => $items,
        ])->layout('layouts.app'); // Menggunakan layout default Jetstream
    }
}