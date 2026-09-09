<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Aset Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="mb-6">
                        <p class="text-sm text-gray-600">Masukkan detail informasi aset inventaris perusahaan di bawah ini.</p>
                    </div>

                    <!-- Pastikan route-nya mengarah ke assets.store -->
                    <form method="POST" action="{{ route('assets.store') }}" class="space-y-6">
                        @csrf

                        <!-- Kode Aset / Tag -->
                        <div>
                            <label for="asset_tag" class="block text-sm font-medium text-gray-700">Kode Aset / Tag <span class="text-red-500">*</span></label>
                            <input type="text" name="asset_tag" id="asset_tag" placeholder="Contoh: IT-001" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('asset_tag') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Aset -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Contoh: MacBook Pro M2" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <select name="location_id" id="location_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="" selected>-- Pilih Lokasi (Opsional) --</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Departemen -->
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Departemen</label>
                            <select name="department_id" id="department_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="" selected>-- Pilih Departemen (Opsional) --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status Aset -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Aset <span class="text-red-500">*</span></label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="Available">Available (Tersedia)</option>
                                <option value="Deployed">Deployed (Digunakan)</option>
                                <option value="Maintenance">Maintenance (Perawatan)</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Pembelian -->
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" id="purchase_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Input Lama Garansi -->
                        <div class="mt-4">
                            <label for="warranty_months" class="block text-sm font-medium text-gray-700">Lama Garansi (Bulan) - Opsional</label>
                            <input type="number" name="warranty_months" id="warranty_months" value="{{ old('warranty_months') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: 12 (untuk 1 tahun) atau 18">
                            @error('warranty_months') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli (Rp)</label>
                            <input type="number" name="purchase_price" id="purchase_price" placeholder="Contoh: 15000000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('assets.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan Aset
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>