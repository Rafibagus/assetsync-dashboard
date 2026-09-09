<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AssetController;
use App\Models\Assets;       
use App\Models\Category;    
use App\Models\Location;          
use App\Models\Department;        
use App\Models\MaintenanceTicket; 

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        // 1. Buat Query Dasar Aset
        $query = \App\Models\Asset::query();
        
        // 2. Terapkan Filter Jika Ada
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // 3. Hitung KPI Menggunakan Clone (agar filter tidak hilang)
        $totalAssets = (clone $query)->count();
        $tersedia   = (clone $query)->where('status', 'Available')->count();
        $digunakan  = (clone $query)->where('status', 'Deployed')->count(); 
        $perawatan  = (clone $query)->where('status', 'Maintenance')->count();
        
        $rusak = round($perawatan * 0.7); 
        $rutin = $perawatan - $rusak;

        // 4. Data Kategori untuk Bar Chart (ikut terfilter)
        $categories = \App\Models\Category::withCount(['assets' => function($q) use ($request) {
            if ($request->filled('location_id')) $q->where('location_id', $request->location_id);
            if ($request->filled('department_id')) $q->where('department_id', $request->department_id);
        }])->get();

        // 5. Data Tren 6 Bulan (ikut terfilter)
        $trendLabels = []; $trendTotal = []; $trendBaru = []; $trendPerawatan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('M y');
            
            $trendBaru[] = (clone $query)->whereYear('created_at', $date->year)
                                        ->whereMonth('created_at', $date->month)->count();
            $trendTotal[] = (clone $query)->where('created_at', '<=', $date->endOfMonth())->count();
            $trendPerawatan[] = (clone $query)->where('status', 'Maintenance')
                                            ->where('updated_at', '<=', $date->endOfMonth())->count();
        }

        // ==========================================
        // 6. JIKA REQUEST AJAX, KEMBALIKAN JSON SAJA
        // ==========================================
        if ($request->ajax()) {
            return response()->json([
                'kpi' => [
                    'total' => number_format($totalAssets),
                    'tersedia' => number_format($tersedia),
                    'digunakan' => number_format($digunakan),
                    'rusak' => number_format($rusak),
                    'rutin' => number_format($rutin),
                ],
                'charts' => [
                    'bar' => $categories->pluck('assets_count'),
                    'line' => [
                        'total' => $trendTotal,
                        'baru' => $trendBaru,
                        'perawatan' => $trendPerawatan
                    ]
                ]
            ]);
        }

        // ==========================================
        // 7. Jika Request Biasa (Load Halaman Pertama Kali), Kirim Semua View
        // ==========================================
        
        // LOGIKA BARU: Aset Habis Garansi Bulan Ini
        $warrantyEndingAssets = \App\Models\Asset::whereNotNull('purchase_date')
            ->whereNotNull('warranty_months')
            ->where('warranty_months', '>', 0)
            ->get()
            ->filter(function($asset) {
                // Parse format tanggal ke Carbon dengan aman
                $purchaseDate = \Carbon\Carbon::parse($asset->purchase_date);
                
                // Tambahkan durasi bulan garansi untuk mendapat tanggal kedaluwarsa
                $expiryDate = $purchaseDate->copy()->addMonths($asset->warranty_months);
                
                // Titipkan hasil tanggal ini ke dalam object asset agar bisa dibaca di HTML (Blade)
                $asset->expiry_date = $expiryDate;

                // Tampilkan hanya jika bulan dan tahun kedaluwarsanya = bulan dan tahun saat ini
                return $expiryDate->isSameMonth(\Carbon\Carbon::now());
            })
            ->sortBy('expiry_date') // Urutkan dari tanggal yang paling dekat
            ->take(5); // Tampilkan 5 teratas

        $locations = \App\Models\Location::all();
        $departments = \App\Models\Department::all();
        
        $pendingTickets = \App\Models\MaintenanceTicket::with('asset.location')
                                ->where('status', 'Pending')->latest()->get();

        return view('dashboard', compact(
            'totalAssets', 'tersedia', 'digunakan', 'perawatan', 'rusak', 'rutin',
            'categories', 'trendLabels', 'trendTotal', 'trendBaru', 'trendPerawatan', 'warrantyEndingAssets',
            'locations', 'departments', 'pendingTickets'
        ));
    })->name('dashboard');

    Route::post('/dashboard/items', [ItemController::class, 'store'])->name('items.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/dashboard/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});

// Route untuk audit aset kritis
Route::get('/assets/audit', [\App\Http\Controllers\AssetController::class, 'audit'])->name('assets.audit');

// Route::resource akan otomatis membuatkan semua rute CRUD 
Route::resource('assets', AssetController::class);

require __DIR__.'/auth.php';

// Route untuk Manajemen Tiket
Route::resource('tickets', \App\Http\Controllers\MaintenanceTicketController::class)->only(['index', 'update']);

Route::get('/assets/{asset}/report', [\App\Http\Controllers\MaintenanceTicketController::class, 'create'])->name('tickets.create');
Route::post('/assets/{asset}/report', [\App\Http\Controllers\MaintenanceTicketController::class, 'store'])->name('tickets.store');