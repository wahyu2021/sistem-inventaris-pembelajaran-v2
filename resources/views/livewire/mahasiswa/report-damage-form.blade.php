<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $item ? 'Laporkan Kerusakan: ' . $item->name : 'Laporkan Kerusakan Barang' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                        <div class="mt-2">
                            <a href="{{ route('mahasiswa.items.index') }}"
                                class="text-sm font-semibold text-green-700 hover:text-green-800 underline">Kembali ke
                                Daftar Barang</a>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submitReport">
                    <div class="space-y-6">
                        @if ($item)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Detail Barang Dilaporkan:</h3>
                                <p class="text-sm text-gray-600">Nama: {{ $item->name }}</p>
                                <p class="text-sm text-gray-600">Kode Unik: {{ $item->unique_code ?? '-' }}</p>
                                <p class="text-sm text-gray-600">Kategori: {{ $item->category->name ?? '-' }}</p>
                                <input type="hidden" wire:model="itemId" value="{{ $item->id }}">
                            </div>
                            <hr />
                        @else
                            <div>
                                <label for="itemId" class="block text-sm font-medium text-gray-700">Pilih Item yang
                                    Rusak <span class="text-red-500">*</span></label>
                                <select wire:model.defer="itemId" id="itemId"
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('itemId') border-red-500 @enderror">
                                    <option value="">-- Pilih Item --</option>
                                    @foreach ($allItems as $it)
                                        <option value="{{ $it->id }}">{{ $it->name }}
                                            ({{ $it->unique_code ?? 'Tanpa Kode' }})</option>
                                    @endforeach
                                </select>
                                @error('itemId')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label for="severity" class="block text-sm font-medium text-gray-700">Tingkat Kerusakan
                                <span class="text-red-500">*</span></label>
                            <select wire:model.defer="severity" id="severity"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('severity') border-red-500 @enderror">
                                @foreach ($allowedSeverities as $sev)
                                    <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                                @endforeach
                            </select>
                            @error('severity')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsikan
                                Kerusakan <span class="text-red-500">*</span></label>
                            <textarea wire:model.defer="description" id="description" rows="5"
                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror"
                                placeholder="Jelaskan detail kerusakan pada barang ini..."></textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="newImageDamage" class="block text-sm font-medium text-gray-700">Unggah Foto
                                Kerusakan (Opsional)</label>
                            <input type="file" wire:model="newImageDamage" id="newImageDamage"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('newImageDamage') border-red-500 @enderror">
                            <div wire:loading wire:target="newImageDamage" class="text-sm text-gray-500 mt-1">
                                Mengunggah...</div>
                            @error('newImageDamage')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror

                            @if ($newImageDamage)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $newImageDamage->temporaryUrl() }}" alt="Preview Foto Kerusakan"
                                        class="mt-1 h-32 w-auto object-contain rounded border">
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end pt-4">
                            <a href="{{ route('mahasiswa.items.index') }}"
                                class="px-4 py-2 mr-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                Kirim Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
