<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-600 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                {{ __('Audit Aset Kritis') }}
            </h2>
            <!-- Tombol Cetak Dokumen -->
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition shadow-sm hidden md:block">
                🖨️ Cetak Laporan Audit
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">
                            Daftar di bawah ini menampilkan aset perusahaan yang berstatus <strong>Maintenance / Rusak</strong>. Harap lakukan pengecekan fisik dan perbarui status aset jika perbaikan telah selesai.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Kode / Tag Aset</th>
                                <th class="px-6 py-4">Nama Aset</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($criticalAssets as $asset)
                            <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $asset->asset_tag }}</td>
                                <td class="px-6 py-4">{{ $asset->name }}</td>
                                <td class="px-6 py-4">{{ $asset->category->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">
                                        Kritis / Rusak
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="text-blue-600 hover:text-blue-900 font-medium text-xs bg-blue-50 px-3 py-2 rounded border border-blue-200 hover:bg-blue-100 transition">
                                        Perbarui Status
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg class="w-12 h-12 mb-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-lg font-medium text-gray-900">Semua Aset Aman!</p>
                                        <p class="text-sm">Tidak ada aset yang memerlukan audit kritis saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CSS Khusus agar tampilan tombol dan menu hilang saat di-print -->
    <style>
        @media print {
            nav, header button, td a { display: none !important; }
            .bg-white { box-shadow: none !important; }
        }
    </style>
</x-app-layout>