<div>
    {{-- Slot Header ... --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Laporan Kerusakan Barang') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                {{-- Tombol Tambah dan Judul ... --}}
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Laporan Kerusakan
                    </h3>
                    <button wire:click="create()"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-opacity-50 transition ease-in-out duration-150">
                        Tambah Laporan Kerusakan
                    </button>
                </div>

                {{-- Pesan Flash ... --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                {{-- Modal Tambah/Edit --}}
                @if ($isOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
                                role="document">
                                <form wire:submit.prevent="store">
                                    <div class="bg-blue-700 px-4 py-3 sm:px-6">
                                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                            {{ $reportId ? 'Edit Laporan Kerusakan' : 'Tambah Laporan Kerusakan Baru' }}
                                        </h3>
                                    </div>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            {{-- Kolom Kiri --}}
                                            <div class="space-y-4">
                                                {{-- Item Barang --}}
                                                <div>
                                                    <label for="item_id"
                                                        class="block text-sm font-medium text-gray-700">Item Barang
                                                        <span class="text-red-500">*</span></label>
                                                    <select wire:model.defer="item_id" id="item_id"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('item_id') border-red-500 @enderror">
                                                        <option value="">Pilih Item</option>
                                                        @foreach ($itemsForForm as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('item_id')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                {{-- Dilaporkan Oleh --}}
                                                <div>
                                                    <label for="reported_by_user_id"
                                                        class="block text-sm font-medium text-gray-700">Dilaporkan Oleh
                                                        <span class="text-red-500">*</span></label>
                                                    <select wire:model.defer="reported_by_user_id"
                                                        id="reported_by_user_id"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('reported_by_user_id') border-red-500 @enderror">
                                                        <option value="">Pilih Pelapor</option>
                                                        @foreach ($reportersForForm as $reporter)
                                                            <option value="{{ $reporter->id }}">{{ $reporter->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('reported_by_user_id')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                {{-- TIPE KERUSAKAN BARU --}}
                                                <div>
                                                    <label for="severity"
                                                        class="block text-sm font-medium text-gray-700">Tipe Kerusakan
                                                        <span class="text-red-500">*</span></label>
                                                    <select wire:model.defer="severity" id="severity"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('severity') border-red-500 @enderror">
                                                        @foreach ($allowedSeverities as $sev)
                                                            <option value="{{ $sev }}">{{ Str::title($sev) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('severity')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                            </div>
                                            {{-- Kolom Kanan --}}
                                            <div class="space-y-4">
                                                {{-- Status Laporan --}}
                                                <div>
                                                    <label for="status"
                                                        class="block text-sm font-medium text-gray-700">Status Laporan
                                                        <span class="text-red-500">*</span></label>
                                                    <select wire:model.defer="status" id="status"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('status') border-red-500 @enderror">
                                                        <option value="dilaporkan">Dilaporkan</option>
                                                        <option value="diverifikasi">Diverifikasi</option>
                                                        <option value="dalam_perbaikan">Dalam Perbaikan</option>
                                                        <option value="selesai_diperbaiki">Selesai Diperbaiki</option>
                                                        <option value="dihapuskan">Dihapuskan</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                {{-- Catatan Admin --}}
                                                <div>
                                                    <label for="admin_notes"
                                                        class="block text-sm font-medium text-gray-700">Catatan Admin
                                                        (Opsional)</label>
                                                    <textarea wire:model.defer="admin_notes" id="admin_notes" rows="3"
                                                        class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('admin_notes') border-red-500 @enderror"></textarea>
                                                    @error('admin_notes')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                {{-- Foto Kerusakan --}}
                                                <div>
                                                    <label for="newImageDamage"
                                                        class="block text-sm font-medium text-gray-700">Foto Kerusakan
                                                        (Opsional)</label>
                                                    {{-- ... (input file dan preview gambar yang sudah ada) ... --}}
                                                    <input type="file" wire:model="newImageDamage"
                                                        id="newImageDamage"
                                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('newImageDamage') border-red-500 @enderror">
                                                    @error('newImageDamage')
                                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                    @enderror
                                                    <div wire:loading wire:target="newImageDamage"
                                                        class="text-sm text-gray-500 mt-1">Uploading...</div>
                                                    @if ($newImageDamage)
                                                        <img src="{{ $newImageDamage->temporaryUrl() }}" alt="Preview"
                                                            class="mt-1 h-20 w-20 object-cover rounded">
                                                    @elseif ($image_damage)
                                                        <img src="{{ Storage::url($image_damage) }}" alt="Gambar"
                                                            class="mt-1 h-20 w-20 object-cover rounded">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Deskripsi Kerusakan di bawah grid --}}
                                        <div class="mt-4">
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700">Deskripsi Kerusakan
                                                <span class="text-red-500">*</span></label>
                                            <textarea wire:model.defer="description" id="description" rows="4"
                                                class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('description') border-red-500 @enderror"></textarea>
                                            @error('description')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Tombol Simpan/Batal Modal ... --}}
                                    <div class="bg-gray-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
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

                {{-- === MODAL UNTUK DETAIL LAPORAN KERUSAKAN (READ-ONLY) === --}}
                @if ($isReportDetailModalOpen && $selectedReportDetail)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="detail-report-modal-title"
                        role="dialog" aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeReportDetailModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                role="document">
                                <div class="bg-blue-700 px-4 py-3 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-white"
                                        id="detail-report-modal-title">
                                        Detail Laporan Kerusakan
                                    </h3>
                                </div>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="space-y-3">
                                        <div>
                                            <strong class="font-medium text-gray-700">ID Laporan:</strong>
                                            <p class="text-sm text-gray-600">{{ $selectedReportDetail->id }}</p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Item Barang:</strong>
                                            <p class="text-sm text-gray-600">
                                                {{ $selectedReportDetail->item->name ?? 'N/A' }} (Kode:
                                                {{ $selectedReportDetail->item->unique_code ?? 'N/A' }})</p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Dilaporkan Oleh:</strong>
                                            <p class="text-sm text-gray-600">
                                                {{ $selectedReportDetail->reporter->name ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Tanggal Lapor:</strong>
                                            <p class="text-sm text-gray-600">
                                                {{ $selectedReportDetail->reported_at ? $selectedReportDetail->reported_at->format('d M Y, H:i') : '-' }}
                                            </p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Tipe Kerusakan:</strong>
                                            <p class="text-sm text-gray-600">
                                                {{ Str::title($selectedReportDetail->severity) }}</p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Deskripsi Kerusakan:</strong>
                                            <p class="text-sm text-gray-600 whitespace-pre-wrap">
                                                {{ $selectedReportDetail->description }}</p>
                                        </div>
                                        <div>
                                            <strong class="font-medium text-gray-700">Status Laporan:</strong>
                                            <p class="text-sm text-gray-600">
                                                {{ Str::title(str_replace('_', ' ', $selectedReportDetail->status)) }}
                                            </p>
                                        </div>
                                        @if ($selectedReportDetail->admin_notes)
                                            <div>
                                                <strong class="font-medium text-gray-700">Catatan Admin:</strong>
                                                <p class="text-sm text-gray-600 whitespace-pre-wrap">
                                                    {{ $selectedReportDetail->admin_notes }}</p>
                                            </div>
                                        @endif
                                        @if ($selectedReportDetail->resolved_at)
                                            <div>
                                                <strong class="font-medium text-gray-700">Tanggal
                                                    Diselesaikan:</strong>
                                                <p class="text-sm text-gray-600">
                                                    {{ $selectedReportDetail->resolved_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        @endif
                                        @if ($selectedReportDetail->image_damage)
                                            <div>
                                                <strong class="font-medium text-gray-700">Foto Kerusakan:</strong>
                                                <img src="{{ Storage::url($selectedReportDetail->image_damage) }}"
                                                    alt="Foto Kerusakan"
                                                    class="mt-1 max-h-60 w-auto object-contain rounded border">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-gray-100 px-4 py-3 sm:px-6 text-right">
                                    <button type="button" wire:click="closeReportDetailModal()"
                                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:w-auto sm:text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- === AKHIR MODAL DETAIL LAPORAN === --}}

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6"> {{-- Ubah ke 4 kolom untuk filter severity --}}
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari deskripsi, item..."
                        class="md:col-span-1 form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <select wire:model.live="filterItem"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Item</option>
                        @foreach ($allItems as $item)
                            <option value="{{ $item->id }}">{{ Str::limit($item->name, 30) }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterStatus"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status Laporan</option>
                        {{-- ... opsi status laporan ... --}}
                        <option value="dilaporkan">Dilaporkan</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="dalam_perbaikan">Dalam Perbaikan</option>
                        <option value="selesai_diperbaiki">Selesai Diperbaiki</option>
                        <option value="dihapuskan">Dihapuskan</option>
                    </select>
                    {{-- FILTER TIPE KERUSAKAN BARU --}}
                    <select wire:model.live="filterSeverity"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe Kerusakan</option>
                        @foreach ($allowedSeverities as $sev)
                            <option value="{{ $sev }}">{{ Str::title($sev) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Laporan Kerusakan --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item</th>
                                {{-- TAMBAHKAN KOLOM TIPE KERUSAKAN --}}
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe Kerusakan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Lapor</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reports as $report)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        @if ($report->item)
                                            {{ Str::limit($report->item->name, 20) }}
                                            {{-- ... (gambar jika ada) ... --}}
                                        @else
                                            Item Dihapus
                                        @endif
                                    </td>
                                    {{-- TAMPILKAN TIPE KERUSAKAN --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($report->severity == 'ringan') bg-green-100 text-green-800
                                            @elseif($report->severity == 'sedang') bg-yellow-100 text-yellow-800
                                            @elseif($report->severity == 'berat') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ Str::title($report->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 break-words">
                                        {{ Str::limit($report->description, 30) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->reporter->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $report->reported_at ? $report->reported_at->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{-- ... (styling status laporan yang sudah ada) ... --}}
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($report->status == 'dilaporkan') bg-yellow-100 text-yellow-800
                                            @elseif($report->status == 'diverifikasi') bg-blue-100 text-blue-800
                                            @elseif($report->status == 'dalam_perbaikan') bg-indigo-100 text-indigo-800
                                            @elseif($report->status == 'selesai_diperbaiki') bg-green-100 text-green-800
                                            @elseif($report->status == 'dihapuskan') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ Str::title(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2"> {{-- Menggunakan flex untuk tata letak ikon --}}
                                            {{-- Tombol Detail (Ikon Mata - Eye) --}}
                                            <button wire:click="showReportDetail({{ $report->id }})"
                                                class="text-green-600 hover:text-green-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500 transition ease-in-out duration-150"
                                                title="Lihat Detail">
                                                {{-- Menggunakan komponen Heroicon --}}
                                                <x-heroicon-o-eye class="w-5 h-5" />
                                                <span class="sr-only">Lihat Detail</span> {{-- Untuk Aksesibilitas --}}
                                            </button>

                                            {{-- Tombol Edit (Ikon Pensil - PencilSquare atau Pencil) --}}
                                            <button wire:click="edit({{ $report->id }})"
                                                class="text-blue-700 hover:text-blue-900 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition ease-in-out duration-150"
                                                title="Edit Laporan">
                                                {{-- Menggunakan komponen Heroicon (pencil-square lebih umum untuk edit) --}}
                                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                                                <span class="sr-only">Edit Laporan</span> {{-- Untuk Aksesibilitas --}}
                                            </button>

                                            {{-- Tombol Hapus (Ikon Tempat Sampah - Trash) --}}
                                            <button wire:click="delete({{ $report->id }})"
                                                wire:confirm="Anda yakin ingin menghapus laporan ini?"
                                                class="text-red-600 hover:text-red-800 p-1 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500 transition ease-in-out duration-150"
                                                title="Hapus Laporan">
                                                {{-- Menggunakan komponen Heroicon --}}
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                                <span class="sr-only">Hapus Laporan</span> {{-- Untuk Aksesibilitas --}}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        {{-- colspan jadi 7 --}}
                                        Belum ada laporan kerusakan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi ... --}}
                @if ($reports->hasPages())
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
