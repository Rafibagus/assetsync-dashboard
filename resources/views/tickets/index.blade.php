<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Tiket Perawatan (History)') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                <table id="ticketsTable" class="w-full text-sm text-left text-gray-600 display whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Masalah</th>
                            <th class="px-6 py-4">Aset & Lokasi</th>
                            <th class="px-6 py-4 text-center">Foto Bukti</th> <!-- KOLOM BARU -->
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi / Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-xs text-gray-500">{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 whitespace-normal">
                                <p class="font-bold text-gray-900">{{ $ticket->issue_title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($ticket->issue_description, 50) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-blue-600">{{ $ticket->asset->name }}</p>
                                <p class="text-xs text-gray-500">📍 {{ $ticket->asset->location->name ?? 'Tidak ada lokasi' }}</p>
                            </td>
                            
                            <!-- LOGIKA TAMPILKAN FOTO DI SINI -->
                            <td class="px-6 py-4 text-center">
                                @if($ticket->photo_path)
                                    <a href="{{ asset('storage/' . $ticket->photo_path) }}" target="_blank" class="inline-flex items-center bg-blue-50 text-blue-700 border border-blue-200 px-2 py-1 rounded text-xs font-semibold hover:bg-blue-100 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Lihat Foto
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($ticket->status == 'Pending')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Pending</span>
                                @elseif($ticket->status == 'In Progress')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700">In Progress</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Resolved</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="flex justify-center items-center">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 py-1" onchange="this.form.submit()">
                                        <option value="Pending" {{ $ticket->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ticketsTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": {
                    "search": "Cari Tiket / Aset:",
                    "lengthMenu": "Tampilkan _MENU_ tiket",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ tiket",
                    "paginate": { "first": "Awal", "last": "Akhir", "next": "Maju", "previous": "Mundur" }
                }
            });
        });
    </script>
    <style>
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 0.5rem; margin-bottom: 1rem; }
        .dataTables_wrapper .dataTables_length select { border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 2rem 0.25rem 0.5rem; }
    </style>
</x-app-layout>