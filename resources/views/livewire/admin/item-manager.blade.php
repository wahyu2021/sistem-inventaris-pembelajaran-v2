<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Inventaris') }}
        </h2>
    </x-slot>

    {{-- Konten Utama Komponen --}}
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            {{-- Mengubah bg-white menjadi bg-gray-50 atau bg-blue-50 untuk sedikit variasi jika diinginkan, atau biarkan putih --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Inventaris
                    </h3>
                    {{-- Tombol utama disesuaikan dengan tema blue-700 --}}
                    <button wire:click="create()"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Data Inventaris
                    </button>
                </div>

                {{-- Pesan Flash --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
                {{-- Error message tidak ada di snippet asli, jika ada, styling serupa bisa diterapkan --}}


                {{-- Modal Tambah/Edit --}}
                @if ($isOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            {{-- Latar belakang modal overlay sedikit lebih terang --}}
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                                role="document">
                                <form wire:submit.prevent="store">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                            {{ $itemId ? 'Edit Data Inventaris' : 'Tambah Data Inventaris Baru' }}
                                        </h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {{-- Kolom Kiri --}}
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="name"
                                                        class="block text-sm font-medium text-gray-700">Nama Barang
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="text" wire:model.defer="name" id="name"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                    @error('name')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="category_id"
                                                        class="block text-sm font-medium text-gray-700">Kategori <span
                                                            class="text-red-500">*</span></label>
                                                    <select wire:model.defer="category_id" id="category_id"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('category_id') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="unique_code"
                                                        class="block text-sm font-medium text-gray-700">Kode Unik
                                                        (Opsional)</label>
                                                    <input type="text" wire:model.defer="unique_code"
                                                        id="unique_code"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('unique_code') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                    @error('unique_code')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="quantity"
                                                        class="block text-sm font-medium text-gray-700">Jumlah <span
                                                            class="text-red-500">*</span></label>
                                                    <input type="number" wire:model.defer="quantity" id="quantity"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('quantity') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500"
                                                        min="0">
                                                    @error('quantity')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- Kolom Kanan --}}
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="condition"
                                                        class="block text-sm font-medium text-gray-700">Kondisi <span
                                                            class="text-red-500">*</span></label>
                                                    <select wire:model.defer="condition" id="condition"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('condition') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="baik">Baik</option>
                                                        <option value="rusak ringan">Rusak Ringan</option>
                                                        <option value="rusak berat">Rusak Berat</option>
                                                        <option value="perlu investigasi">Perlu Investigasi</option>
                                                    </select>
                                                    @error('condition')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="location"
                                                        class="block text-sm font-medium text-gray-700">Lokasi
                                                        (Opsional)</label>
                                                    <input type="text" wire:model.defer="location" id="location"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('location') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                    @error('location')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label for="newImage"
                                                        class="block text-sm font-medium text-gray-700">Gambar Barang
                                                        (Opsional)</label>
                                                    {{-- Styling file input disesuaikan agar lebih netral dan rapi --}}
                                                    <input type="file" wire:model="newImage" id="newImage"
                                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 @error('newImage') border-red-500 @enderror">
                                                    @error('newImage')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror

                                                    <div wire:loading wire:target="newImage"
                                                        class="text-sm text-gray-500 mt-1">Uploading...</div>

                                                    @if ($newImage)
                                                        <p class="mt-2 text-sm text-gray-600">Preview Gambar Baru:</p>
                                                        <img src="{{ $newImage->temporaryUrl() }}"
                                                            alt="Preview Gambar Baru"
                                                            class="mt-1 h-20 w-20 object-cover rounded">
                                                    @elseif ($image)
                                                        <p class="mt-2 text-sm text-gray-600">Gambar Saat Ini:</p>
                                                        <img src="{{ Storage::url($image) }}" alt="Gambar Saat Ini"
                                                            class="mt-1 h-20 w-20 object-cover rounded">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Deskripsi di bawah grid --}}
                                        <div class="mt-4">
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700">Deskripsi
                                                (Opsional)</label>
                                            <textarea wire:model.defer="description" id="description" rows="3"
                                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500"></textarea>
                                            @error('description')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        {{-- Tombol Simpan disesuaikan dengan tema blue-700 --}}
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama, kode unik, atau kategori..."
                        class="col-span-1 md:col-span-2 form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <select wire:model.live="filterCategory"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Inventaris --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gambar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Barang</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Unik</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kondisi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Lokasi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($items as $item)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                                class="h-10 w-10 rounded-md object-cover">
                                        @else
                                            <span class="text-xs text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->unique_code ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($item->condition == 'baik') bg-green-100 text-green-800
                                            @elseif($item->condition == 'rusak ringan') bg-yellow-100 text-yellow-800
                                            @elseif($item->condition == 'rusak berat') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ Str::title($item->condition) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->location ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        {{-- Tombol Aksi Edit dengan warna tema --}}
                                        <button wire:click="edit({{ $item->id }})"
                                            class="text-indigo-600 hover:text-indigo-800 focus:outline-none transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">Edit</span>
                                        </button>
                                        <button wire:click="delete({{ $item->id }})"
                                            wire:confirm="Anda yakin ingin menghapus item '{{ $item->name }}'?"
                                            class="ml-3 text-red-600 hover:text-red-800 focus:outline-none transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">Hapus</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-6">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <p class="mt-2 text-lg text-gray-700">Belum ada data inventaris.</p>
                                            <p class="text-xs text-gray-500">Silakan tambahkan data baru menggunakan
                                                tombol di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                @if ($items->hasPages())
                    <div class="mt-6">
                        {{ $items->links() }} {{-- Styling paginasi default akan digunakan, bisa dikustomisasi jika perlu --}}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
