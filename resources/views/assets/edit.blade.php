<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Aset: ') . $asset->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-8">
                
                <form action="{{ route('assets.update', $asset->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        
                        <!-- Field Tag & Nama -->
                        <div>
                            <label for="asset_tag" class="block text-sm font-medium text-gray-700">Kode Aset / Tag <span class="text-red-500">*</span></label>
                            <input type="text" name="asset_tag" id="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('asset_tag') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Aset <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $asset->name) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Field Kategori & Status -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $asset->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Aset <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Available" {{ (old('status', $asset->status) == 'Available') ? 'selected' : '' }}>Available</option>
                                <option value="Deployed" {{ (old('status', $asset->status) == 'Deployed') ? 'selected' : '' }}>Deployed</option>
                                <option value="Maintenance" {{ (old('status', $asset->status) == 'Maintenance') ? 'selected' : '' }}>Maintenance</option>
                                <option value="Retired" {{ (old('status', $asset->status) == 'Retired') ? 'selected' : '' }}>Retired</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Field Tanggal Beli & Harga -->
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('purchase_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="purchase_cost" class="block text-sm font-medium text-gray-700">Harga Beli (Rp)</label>
                            <input type="number" name="purchase_cost" id="purchase_cost" value="{{ old('purchase_cost', (int)$asset->purchase_cost) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('purchase_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi Jelas di Bawah -->
                    <div class="flex justify-end items-center space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('assets.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>