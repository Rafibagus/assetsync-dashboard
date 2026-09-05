<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Aset') }}
            </h2>
            <a href="{{ route('assets.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Tambah Aset
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <!-- Wrapper Responsif untuk Tabel -->
                    <div class="overflow-x-auto w-full">
                        <table id="assetsTable" class="w-full text-sm text-left whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tag Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Harga Beli</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Otomatis diisi oleh Yajra -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Script jQuery & DataTables Core -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- CSS Override Paksa agar menyatu dengan Laravel Breeze -->
    <style>
        /* Desain Wrapper & Form Input (Search & Pagination) */
        .dataTables_wrapper { font-family: inherit; font-size: 0.875rem; color: #374151; width: 100%; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            background-color: #ffffff !important;
            color: #111827 !important;
            margin-left: 0.5rem;
        }
        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            outline: none !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 1px #6366f1 !important;
        }
        
        /* Desain Header & Baris Tabel */
        table.dataTable { border-collapse: collapse !important; width: 100% !important; margin-top: 1rem !important; margin-bottom: 1rem !important; }
        table.dataTable thead th {
            background-color: #f9fafb !important;
            color: #4b5563 !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 0.75rem 1rem !important;
            border-bottom: 1px solid #e5e7eb !important;
        }
        table.dataTable tbody td {
            background-color: #ffffff !important;
            color: #374151 !important;
            padding: 1rem !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }
        table.dataTable tbody tr:hover td { background-color: #f9fafb !important; }

        /* Desain Tombol Pagination */
        .dataTables_wrapper .dataTables_paginate { display: flex; justify-content: flex-end; gap: 0.25rem; margin-top: 1rem; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            background: #ffffff !important;
            color: #374151 !important;
            cursor: pointer;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: #ffffff !important;
            border-color: #4f46e5 !important;
        }
        .dataTables_wrapper .dataTables_info { margin-top: 1rem; color: #6b7280; }
    </style>

    <script>
        $(document).ready(function() {
            $('#assetsTable').DataTable({
                processing: true, 
                serverSide: true,
                ajax: "{{ route('assets.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'asset_tag', name: 'assets.asset_tag' },
                    { data: 'name', name: 'assets.name' },
                    { data: 'category_name', name: 'category.name', orderable: false, searchable: false },
                    { data: 'status', name: 'assets.status' },
                    { data: 'purchase_cost', name: 'assets.purchase_cost' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
                ]
            });
        });
    </script>
    <!-- Form tersembunyi khusus untuk mengeksekusi method DELETE secara aman -->
    <form id="deleteForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Fungsi konfirmasi hapus standar industri
        function confirmDelete(deleteUrl) {
            if (confirm('Apakah kamu yakin ingin menghapus aset ini? Data akan dipindahkan ke tempat sampah (Soft Delete).')) {
                const form = document.getElementById('deleteForm');
                form.action = deleteUrl;
                form.submit();
            }
        }
    </script>
</x-app-layout>