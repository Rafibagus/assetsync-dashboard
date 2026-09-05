<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Aset: ') . $asset->asset_tag }}
            </h2>
            <a href="{{ route('assets.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    
                    <!-- Kolom Kiri: Foto / Media Visual -->
                    <div class="p-8 bg-gray-50 border-r border-gray-200 flex flex-col items-center justify-center">
                        <!-- Placeholder Foto (Nantinya diganti dengan tag <img> dari database) -->
                        <div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-400 mb-6">
                            <span class="text-gray-500 text-sm font-medium text-center px-4">
                                Foto Fisik Aset<br>(Belum Diunggah)
                            </span>
                        </div>
                        
                        <!-- Placeholder QR Code -->
                        <div class="w-32 h-32 bg-white p-2 border border-gray-300 rounded shadow-sm flex items-center justify-center">
                            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                <span class="text-white text-xs font-mono">QR CODE</span>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 font-mono">{{ $asset->asset_tag }}</p>
                    </div>

                    <!-- Kolom Kanan: Informasi Spesifikasi -->
                    <div class="p-8 md:col-span-2">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $asset->name }}</h3>
                                <p class="text-sm text-indigo-600 font-medium">{{ $asset->category->name ?? 'Kategori Tidak Diketahui' }}</p>
                            </div>
                            
                            @php
                                $badgeColors = [
                                    'Available' => 'bg-green-100 text-green-800',
                                    'Deployed' => 'bg-blue-100 text-blue-800',
                                    'Maintenance' => 'bg-yellow-100 text-yellow-800',
                                    'Retired' => 'bg-red-100 text-red-800',
                                ];
                                $color = $badgeColors[$asset->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-4 py-1 rounded-full text-sm font-bold {{ $color }}">
                                {{ $asset->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4 border-t border-gray-200 pt-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tag Identitas</p>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $asset->asset_tag }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Harga Pembelian</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $asset->purchase_cost ? 'Rp ' . number_format($asset->purchase_cost, 0, ',', '.') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pembelian</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d F Y') : 'Tidak Tercatat' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Masa Garansi</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $asset->warranty_months ? $asset->warranty_months . ' Bulan' : 'Tidak Ada Garansi' }}</p>
                            </div>
                        </div>

                        <!-- Tombol Aksi Bawah -->
                        <div class="mt-10 flex space-x-4">
                            <a href="{{ route('assets.edit', $asset->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow-sm hover:bg-indigo-700 text-sm font-medium transition">
                                Edit Data Aset
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>