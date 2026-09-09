<x-app-layout>
    <!-- SUB-HEADER: Filter Global & Aksi -->
    <div class="bg-white border-b border-gray-200 py-4 px-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 sticky top-0 z-10">
        <!-- Filter Kiri -->
        <div class="flex space-x-3 w-full md:w-auto">
            <select class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-full md:w-40">
                <option>Juli 2024</option>
                <option>Juni 2024</option>
            </select>
            <!-- Filter Lokasi (Ditambahkan ID) -->
            <select id="filter-location" name="location_id" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-full md:w-40">
                <option value="">Semua Lokasi</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>

            <!-- Filter Departemen (Ditambahkan ID) -->
            <select id="filter-department" name="department_id" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 w-full md:w-40 hidden md:block">
                <option value="">Semua Departemen</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Aksi Kanan -->
        <div class="flex space-x-3 w-full md:w-auto">
            <a href="{{ route('assets.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition flex items-center justify-center w-full md:w-auto">
                <span class="mr-2">+</span> Tambah Aset Baru
            </a>
            <a href="{{ route('assets.audit') }}" class="bg-indigo-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-800 transition flex items-center justify-center w-full md:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Buat Audit Aset Kritis
            </a>
        </div>
    </div>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- BARIS 1: KARTU KPI DENGAN TREN -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-semibold text-gray-700">Total Aset</span>
                        <div class="p-2 bg-gray-100 text-gray-500 rounded-md">📦</div>
                    </div>
                    <div class="mt-4">
                        <!-- Tambah ID di sini -->
                        <span id="kpi-total" class="text-3xl font-bold text-gray-900">{{ number_format($totalAssets) }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-semibold text-gray-700">Aset Tersedia</span>
                        <div class="p-2 bg-green-50 text-green-500 rounded-md">✅</div>
                    </div>
                    <div class="mt-4">
                         <!-- Tambah ID di sini -->
                        <span id="kpi-tersedia" class="text-3xl font-bold text-green-600">{{ number_format($tersedia) }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-semibold text-gray-700">Aset Digunakan</span>
                        <div class="p-2 bg-blue-50 text-blue-500 rounded-md">🖥️</div>
                    </div>
                    <div class="mt-4">
                         <!-- Tambah ID di sini -->
                        <span id="kpi-digunakan" class="text-3xl font-bold text-blue-600">{{ number_format($digunakan) }}</span>
                    </div>
                </div>

                <!-- Kartu Kondisi Aset (Revised) -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <span class="text-sm font-semibold text-gray-700">Kondisi Aset (Revised)</span>
                    <div class="mt-2 space-y-2">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                            <span class="text-sm text-gray-600">Masalah / Rusak:</span>
                             <!-- Tambah ID di sini -->
                            <span id="kpi-rusak" class="text-lg font-bold text-red-500">{{ number_format($rusak) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Masuk Perawatan:</span>
                             <!-- Tambah ID di sini -->
                            <span id="kpi-rutin" class="text-lg font-bold text-orange-500">{{ number_format($rutin) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BARIS 2: KONTEN UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- KOLOM KIRI (Lebar: 2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-base font-bold text-gray-800 mb-4">Tren Perkembangan Aset (6 Bulan Terakhir)</h3>
                        <div class="relative h-72 w-full"><canvas id="trendChart"></canvas></div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-base font-bold text-gray-800 mb-4">Distribusi Kategori Aset</h3>
                        <div class="relative h-64 w-full"><canvas id="categoryBarChart"></canvas></div>
                    </div>
                </div>

                <!-- KOLOM KANAN (Lebar: 1/3) -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800">Wawasan & Tindakan Kritis</h3>
                    
                    <!-- 1. Kartu Peringatan Audit -->
                    <div class="bg-red-50 p-5 rounded-xl border border-red-200">
                        <div class="flex items-center space-x-2 text-red-700 font-bold mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>Audit Segera Diperlukan!</span>
                        </div>
                        <p class="text-sm text-red-800 mb-4">Lebih dari 10% aset perusahaan terindikasi rusak atau kritis.</p>
                        <a href="{{ route('assets.audit') }}" class="w-full block text-center bg-white border border-red-300 text-red-700 py-2 rounded-md text-sm font-semibold hover:bg-red-100 transition">Mulai Audit Aset Kritis</a>
                    </div>

                    <!-- 2. Kartu Garansi (YANG SEMPAT HILANG) -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 border-b pb-2 mb-3">Aset Habis Garansi Bulan Ini</h4>
                        <div class="space-y-3">
                            @forelse($warrantyEndingAssets as $asset)
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                <div>
                                    <p class="font-semibold text-gray-700">{{ $asset->name }}</p>
                                    <p class="text-gray-500 text-xs">SN: {{ $asset->asset_tag }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Berakhir pada:</p>
                                    <span class="text-xs font-bold text-red-600">{{ $asset->expiry_date->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="flex items-center space-x-2 text-green-600 bg-green-50 p-2 rounded text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Bulan ini aman, tidak ada garansi yang kedaluwarsa.</span>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Kartu Tiket (DENGAN TOMBOL YANG HILANG) -->
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 border-b pb-2 mb-3">Permintaan Perawatan Baru</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Tiket Permintaan Pending</p>
                                @if($pendingTickets->count() > 0)
                                    <p class="text-xs text-gray-500 mt-1 truncate w-48">
                                        {{ $pendingTickets->first()->asset->name }} - {{ $pendingTickets->first()->asset->location->name ?? 'Lokasi Umum' }}
                                    </p>
                                @else
                                    <p class="text-xs text-green-600 mt-1">Tidak ada tiket tertunda.</p>
                                @endif
                            </div>
                            <span class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded">{{ $pendingTickets->count() }} Tiket</span>
                        </div>
                        
                        <a href="{{ route('tickets.index') }}" class="w-full block text-center mt-4 bg-gray-50 border border-gray-200 text-gray-600 py-2 rounded-md text-sm font-semibold hover:bg-gray-100 transition {{ $pendingTickets->count() == 0 ? 'opacity-50 pointer-events-none' : '' }}">
                            Tinjau Tiket Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Chart.js & AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Deklarasi variabel chart di luar agar bisa diupdate oleh AJAX
        let lineChart, barChart;
        
        const trendLabels = {!! json_encode($trendLabels) !!};
        const rawCategories = {!! json_encode($categories) !!};

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inisialisasi Line Chart
            const ctxLine = document.getElementById('trendChart').getContext('2d');
            lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label: 'Total Aset', data: {!! json_encode($trendTotal) !!}, borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.1)', borderWidth: 2, fill: true },
                        { label: 'Aset Baru', data: {!! json_encode($trendBaru) !!}, borderColor: '#10B981', backgroundColor: '#10B981', borderWidth: 2 },
                        { label: 'Masuk Perawatan', data: {!! json_encode($trendPerawatan) !!}, borderColor: '#F59E0B', backgroundColor: '#F59E0B', borderWidth: 2 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. Inisialisasi Bar Chart
            const ctxBar = document.getElementById('categoryBarChart').getContext('2d');
            barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: rawCategories.map(c => c.name),
                    datasets: [{ label: 'Jumlah Aset', data: rawCategories.map(c => c.assets_count), backgroundColor: '#4F46E5', borderRadius: 4 }]
                },
                options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // 3. LOGIKA AJAX UNTUK FILTER
            const locSelect = document.getElementById('filter-location');
            const deptSelect = document.getElementById('filter-department');

            function fetchFilteredData() {
                const locId = locSelect.value;
                const deptId = deptSelect.value;
                
                // Panggil route /dashboard tapi minta data JSON
                fetch(`/dashboard?location_id=${locId}&department_id=${deptId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest', 
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // Update Angka KPI di layar
                    document.getElementById('kpi-total').innerText = data.kpi.total;
                    document.getElementById('kpi-tersedia').innerText = data.kpi.tersedia;
                    document.getElementById('kpi-digunakan').innerText = data.kpi.digunakan;
                    document.getElementById('kpi-rusak').innerText = data.kpi.rusak;
                    document.getElementById('kpi-rutin').innerText = data.kpi.rutin;

                    // Update Grafik Bar (Kategori)
                    barChart.data.datasets[0].data = data.charts.bar;
                    barChart.update();

                    // Update Grafik Line (Tren)
                    lineChart.data.datasets[0].data = data.charts.line.total;
                    lineChart.data.datasets[1].data = data.charts.line.baru;
                    lineChart.data.datasets[2].data = data.charts.line.perawatan;
                    lineChart.update();
                });
            }

            // Jalankan fungsi AJAX setiap kali dropdown diubah
            locSelect.addEventListener('change', fetchFilteredData);
            deptSelect.addEventListener('change', fetchFilteredData);
        });
    </script>
</x-app-layout>