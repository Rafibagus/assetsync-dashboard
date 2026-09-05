<!-- resources/views/assets/create.blade.php -->
<!-- Asumsi kamu menggunakan layout utama, misalnya app.blade.php -->
{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Aset Baru</h2>
            <p class="text-gray-600 text-sm mt-1">Masukkan detail informasi aset inventaris perusahaan.</p>
        </div>

        <form action="{{ route('assets.store') }}" method="POST">
            <!-- Wajib ada untuk mencegah serangan CSRF -->
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <!-- Field: Asset Tag -->
                <div>
                    <label for="asset_tag" class="block text-sm font-medium text-gray-700">Kode Aset / Tag <span class="text-red-500">*</span></label>
                    <input type="text" name="asset_tag" id="asset_tag" 
                        value="{{ old('asset_tag') }}"
                        class="mt-1 block w-full rounded-md shadow-sm sm:text-sm 
                        {{ $errors->has('asset_tag') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500' }}"
                        placeholder="Contoh: IT-001">
                    @error('asset_tag')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Aset <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" 
                        value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                        {{ $errors->has('name') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
                        placeholder="Contoh: MacBook Pro M2">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" 
                        class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                        {{ $errors->has('category_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}">
                        <option value="">-- Pilih Kategori --</option>
                        <!-- Looping data kategori dari Controller -->
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Aset <span class="text-red-500">*</span></label>
                    <select name="status" id="status" 
                        class="mt-1 block w-full rounded-md shadow-sm sm:text-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available (Tersedia)</option>
                        <option value="Deployed" {{ old('status') == 'Deployed' ? 'selected' : '' }}>Deployed (Digunakan)</option>
                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance (Perawatan)</option>
                        <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>Retired (Pensiun/Rusak)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" id="purchase_date" 
                        value="{{ old('purchase_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('purchase_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Field: Purchase Cost -->
                <div>
                    <label for="purchase_cost" class="block text-sm font-medium text-gray-700">Harga Beli (Rp)</label>
                    <input type="number" name="purchase_cost" id="purchase_cost" min="0" step="1000"
                        value="{{ old('purchase_cost') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Contoh: 15000000">
                    @error('purchase_cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-5 border-t border-gray-200">
                <a href="{{ route('assets.index') }}" 
                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>

{{-- @endsection --}}