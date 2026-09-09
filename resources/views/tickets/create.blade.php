<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                <h2 class="text-xl font-bold text-red-600 mb-4">Lapor Kerusakan Aset</h2>
                
                <div class="mb-6 p-4 bg-gray-50 rounded-md">
                    <p class="text-sm text-gray-600">Aset yang dilaporkan:</p>
                    <p class="font-bold text-gray-900">{{ $asset->name }} ({{ $asset->asset_tag }})</p>
                </div>

                <!-- PERUBAHAN 1: Tambahkan enctype="multipart/form-data" -->
                <form action="{{ route('tickets.store', $asset->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Judul Masalah</label>
                        <input type="text" name="issue_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Contoh: Layar mati total" required>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Detail</label>
                        <textarea name="issue_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm" placeholder="Jelaskan kronologi atau kondisi fisiknya..." required></textarea>
                    </div>

                    <!-- PERUBAHAN 2: Input Foto Ditambahkan di Sini -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Unggah Foto Bukti (Opsional)</label>
                        <input type="file" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ url()->previous() }}" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>