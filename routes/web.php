<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AssetController;
use App\Models\Asset;       // Wajib ditambahkan untuk memanggil data aset
use App\Models\Category;    // Wajib ditambahkan untuk memanggil data kategori

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // --- UBAH RUTE DASHBOARD DI SINI ---
    // Kita ganti ItemController menjadi langsung menghitung statistik untuk UI Dashboard
    Route::get('/dashboard', function () {
        // 1. Hitung Status Aset (Pastikan ejaan statusnya sesuai dengan yang ada di database)
        $totalAssets = Asset::count();
        $tersedia   = Asset::where('status', 'Available')->count();
        $digunakan  = Asset::where('status', 'Deployed')->count(); 
        $perawatan  = Asset::where('status', 'Maintenance')->count(); 

        // 2. Distribusi Kategori (Menghitung jumlah aset per kategori)
        $categories = Category::withCount('assets')->get();

        // 3. Aset Terbaru (Preview 5 aset terakhir dimasukkan)
        $recentAssets = Asset::with('category')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalAssets', 'tersedia', 'digunakan', 'perawatan', 'categories', 'recentAssets'
        ));
    })->name('dashboard');
    // -----------------------------------

    Route::post('/dashboard/items', [ItemController::class, 'store'])->name('items.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/dashboard/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});

// Route::resource akan otomatis membuatkan semua rute CRUD 
// (index, create, store, show, edit, update, destroy)
Route::resource('assets', AssetController::class);

require __DIR__.'/auth.php';