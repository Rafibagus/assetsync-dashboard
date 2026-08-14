<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AssetSync Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Pesan Sukses -->
                @if(session('success'))
                    <div id="success-message" class="mb-4 p-4 bg-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Tambah Barang -->
                <h3 class="text-lg font-bold mb-4 mt-6">Tambah Barang Baru</h3>
                <form method="POST" action="{{ route('items.store') }}" id="form-add-item" class="mb-8 border p-4 rounded bg-gray-50">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="name" id="input-name" placeholder="Nama Barang" class="border p-2 rounded w-full" required>
                        
                        <!-- Fitur 3: Kategori menjadi Dropdown -->
                        <select name="category" id="input-category" class="border p-2 rounded w-full" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furnitur">Furnitur</option>
                            <option value="Kendaraan">Kendaraan</option>
                            <option value="Lisensi/Software">Lisensi/Software</option>
                        </select>
                        
                        <input type="number" name="stock" id="input-stock" placeholder="Jumlah Stok" class="border p-2 rounded w-full" required>
                        <input type="number" name="price" id="input-price" placeholder="Harga (Rp)" class="border p-2 rounded w-full" required>
                    </div>
                    
                    <!-- Fitur 1: Tombol Submit dimunculkan dengan warna jelas -->
                    <div class="mt-4">
                        <button type="submit" id="btn-submit-item" style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                            Simpan Barang
                        </button>
                    </div>
                </form>

                <!-- Tabel Daftar Barang -->
                <h3 class="text-lg font-bold mb-4">Daftar Aset</h3>
                <table id="table-items" class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b py-2">ID</th>
                            <th class="border-b py-2">Nama Barang</th>
                            <th class="border-b py-2">Kategori</th>
                            <th class="border-b py-2 text-center">Stok</th>
                            <th class="border-b py-2">Harga</th>
                            <th class="border-b py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="item-row hover:bg-gray-100">
                            <td class="border-b py-2">{{ $item->id }}</td>
                            <td class="border-b py-2 item-name">{{ $item->name }}</td>
                            <td class="border-b py-2 item-category">{{ $item->category }}</td>
                            <td class="border-b py-2 item-stock text-center">{{ $item->stock }}</td>
                            
                            <!-- Fitur 4: Harga diformat agar mudah dibaca -->
                            <td class="border-b py-2 item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            
                            <!-- Fitur 2: Tombol Hapus -->
                            <td class="border-b py-2 text-center">
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" id="btn-delete-{{ $item->id }}" style="background-color: #dc2626; color: white; padding: 4px 12px; border-radius: 4px; font-size: 14px; cursor: pointer;">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>