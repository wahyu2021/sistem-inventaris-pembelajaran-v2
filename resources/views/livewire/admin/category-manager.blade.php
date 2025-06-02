<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori Barang') }}
        </h2>
    </x-slot>

    {{-- Konten Utama Komponen --}}
    <div class="py-6"> {{-- Tambahkan padding jika diperlukan, atau sesuaikan dengan struktur dashboard Anda --}}
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Judul Halaman Internal Komponen dan Tombol Tambah --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Kategori
                    </h3>
                </div>

                {{-- Pesan Sukses/Error dari Session Flash --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Modal untuk Tambah/Edit Kategori --}}
                @if ($isOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                role="document">
                                <form wire:submit.prevent="store">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start w-full">
                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4"
                                                    id="modal-title">
                                                    {{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                                                </h3>
                                                <div class="space-y-4">
                                                    <div>
                                                        <label for="name"
                                                            class="block text-sm font-medium text-gray-700">Nama
                                                            Kategori <span class="text-red-500">*</span></label>
                                                        <input type="text" wire:model.defer="name" id="name"
                                                            name="name"
                                                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                                                            placeholder="Contoh: Buku Pelajaran">
                                                        @error('name')
                                                            <span
                                                                class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="description"
                                                            class="block text-sm font-medium text-gray-700">Deskripsi
                                                            (Opsional)</label>
                                                        <textarea wire:model.defer="description" id="description" name="description" rows="3"
                                                            class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror"
                                                            placeholder="Deskripsi singkat mengenai kategori ini"></textarea>
                                                        @error('description')
                                                            <span
                                                                class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition ease-in-out duration-150">
                                            Simpan
                                        </button>
                                        <button type="button" wire:click="closeModal()"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition ease-in-out duration-150">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Input Pencarian --}}
                <div class="mb-4 flex justify-between">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari berdasarkan nama atau deskripsi kategori..."
                        class="form-input rounded-md shadow-sm w-full sm:w-2/3 lg:w-1/2 py-2 px-3 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <button wire:click="create()"
                        class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Kategori
                    </button>
                </div>

                {{-- Tabel untuk Menampilkan Daftar Kategori --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-4/12">
                                    Nama Kategori</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-5/12">
                                    Deskripsi</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($categories as $index => $category)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $categories->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 break-words">
                                        {{ Str::limit($category->description, 70) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $category->id }})"
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
                                        <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="Anda yakin ingin menghapus kategori '{{ $category->name }}'?"
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
                                    <td colspan="4"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-6">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <p class="mt-2 text-lg text-gray-700">Belum ada
                                                kategori.</p>
                                            <p class="text-xs text-gray-500">Silakan tambahkan
                                                kategori baru menggunakan tombol di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                @if ($categories->hasPages())
                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
