<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Barang Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Cari & Pilih Barang
                    </h3>
                </div>

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, kode unik, atau kategori..." class="form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <select wire:model.live="filterCategory" class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Daftar Item --}}
                @if($items->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($items as $item)
                            <div class="bg-white rounded-lg shadow-md border border-gray-200 flex flex-col">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-48 w-full object-cover rounded-t-lg">
                                @else
                                    <div class="h-48 w-full bg-gray-200 flex items-center justify-center rounded-t-lg">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="p-4 flex flex-col flex-grow">
                                    <h4 class="text-lg font-semibold text-gray-800 truncate" title="{{ $item->name }}">{{ $item->name }}</h4>
                                    <p class="text-xs text-gray-500 mb-1">{{ $item->category->name ?? 'Tidak Berkategori' }}</p>
                                    <p class="text-sm text-gray-600 mb-2 flex-grow">{{ Str::limit($item->description, 60) }}</p>
                                    <div class="text-xs text-gray-500 mb-1">Kode: {{ $item->unique_code ?? '-' }}</div>
                                    <div class="text-xs text-gray-500 mb-1">Jumlah: {{ $item->quantity }}</div>
                                    <div class="text-xs text-gray-500 mb-3">Kondisi: <span class="font-medium">{{ Str::title($item->condition) }}</span></div>
                                    <a href="{{ route('mahasiswa.damages.report', ['item' => $item->id]) }}"
                                       class="mt-auto w-full text-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                                        Laporkan Kerusakan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($items->hasPages())
                    <div class="mt-8">
                        {{ $items->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada item ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>