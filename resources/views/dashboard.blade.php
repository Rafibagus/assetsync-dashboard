<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- BARIS 1: 4 KOTAK METRIK (Bersebelahan) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Total Aset</span>
                    <span class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAssets }}</span>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Tersedia</span>
                    <span class="text-3xl font-bold text-green-600 mt-2">{{ $tersedia }}</span>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Digunakan</span>
                    <span class="text-3xl font-bold text-blue-600 mt-2">{{ $digunakan }}</span>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Perawatan</span>
                    <span class="text-3xl font-bold text-orange-500 mt-2">{{ $perawatan }}</span>
                </div>
            </div>

            <!-- BARIS 2: GRAFIK & INSIGHT (Bersebelahan 50% - 50%) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Kiri: Grafik Pie -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Kategori</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Kanan: Insight / Analisa -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-center space-y-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Analisa Cepat</h3>
                    
                    <div class="p-4 bg-blue-50 text-blue-800 rounded-lg border border-blue-100">
                        <p class="text-sm"><strong>Total Inventaris:</strong> Sistem mencatat <strong>{{ $totalAssets }} unit</strong> aset secara keseluruhan.</p>
                    </div>
                    
                    <div class="p-4 bg-orange-50 text-orange-800 rounded-lg border border-orange-100">
                        <p class="text-sm"><strong>Status Perawatan:</strong> Terdapat <strong>{{ $perawatan }} aset</strong> yang sedang dalam masa maintenance/perbaikan.</p>
                    </div>

                    @if($perawatan > ($totalAssets * 0.1))
                    <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-100">
                        <p class="text-sm font-semibold">⚠️ Peringatan Kritis</p>
                        <p class="text-sm mt-1">Lebih dari 10% aset perusahaan sedang rusak. Diperlukan audit segera!</p>
                    </div>
                    @else
                    <div class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-100">
                        <p class="text-sm font-semibold">✅ Status Aman</p>
                        <p class="text-sm mt-1">Tingkat kerusakan aset berada di bawah batas normal (kurang dari 10%).</p>
                    </div>
                    @endif
                </div>

            </div>

            <!-- BARIS 3: TABEL ASET TERBARU -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Aset Terbaru</h3>
                    <a href="{{ route('assets.index') }}" class="text-sm text-blue-600 hover:underline font-medium">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">Tag Aset</th>
                                <th class="px-4 py-3">Nama Aset</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 rounded-r-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAssets as $asset)
                            <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $asset->asset_tag }}</td>
                                <td class="px-4 py-3">{{ $asset->name }}</td>
                                <td class="px-4 py-3">{{ $asset->category->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full border 
                                        {{ $asset->status == 'Available' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                        {{ $asset->status == 'Deployed' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                        {{ $asset->status == 'Maintenance' ? 'bg-orange-50 text-orange-700 border-orange-200' : '' }}
                                    ">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada data aset.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoriesData = @json($categories);
            
            if (categoriesData && categoriesData.length > 0) {
                const labels = categoriesData.map(cat => cat.name);
                const dataCounts = categoriesData.map(cat => cat.assets_count);
                const totalAssetsSum = dataCounts.reduce((a, b) => a + b, 0);

                const backgroundColors = ['#3B82F6', '#F59E0B', '#10B981', '#6366F1', '#EC4899', '#8B5CF6', '#14B8A6'];

                const ctx = document.getElementById('categoryChart').getContext('2d');
                
                const myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataCounts,
                            backgroundColor: backgroundColors,
                            borderWidth: 2,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'right',
                                labels: { boxWidth: 12, font: { size: 11 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const percentage = totalAssetsSum > 0 ? ((value / totalAssetsSum) * 100).toFixed(1) : 0;
                                        return ` ${context.label}: ${value} unit (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        cutout: '65%',
                        // FITUR KLIK INLINE: Filter data langsung di dashboard tanpa pindah halaman
                        onClick: (event, elements) => {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const categoryName = labels[index];
                                
                                // Ambil data aset berdasarkan kategori yang diklik via fetch API
                                fetch(`{{ route('dashboard') }}?category=${encodeURIComponent(categoryName)}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.text())
                                .then(html => {
                                    // Parse HTML yang dikembalikan untuk mengambil bagian tabelnya saja
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newTable = doc.querySelector('#recent-assets-table');
                                    
                                    // Ganti isi tabel yang lama dengan data baru hasil filter
                                    if(newTable) {
                                        document.querySelector('#recent-assets-table').innerHTML = newTable.innerHTML;
                                    }
                                })
                                .catch(error => console.error('Gagal memuat data:', error));
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        beforeDraw(chart) {
                            const { width, height, ctx } = chart;
                            ctx.restore();
                            const fontSize = (height / 110).toFixed(2);
                            ctx.font = `bold ${fontSize}em sans-serif`;
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#1F2937';

                            const text = `${totalAssetsSum}`;
                            const textSub = 'Total Aset';
                            
                            const textX = Math.round((chart.chartArea.left + chart.chartArea.right) / 2);
                            const textY = Math.round((chart.chartArea.top + chart.chartArea.bottom) / 2) - 8;
                            
                            ctx.textAlign = 'center';
                            ctx.fillText(text, textX, textY);

                            ctx.font = `500 11px sans-serif`;
                            ctx.fillStyle = '#6B7280';
                            ctx.fillText(textSub, textX, textY + 18);
                            ctx.save();
                        }
                    }]
                });
            }
        });
    </script>
</x-app-layout>