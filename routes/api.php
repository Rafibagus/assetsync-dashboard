<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Models\Item;


// 1. Endpoint untuk melihat daftar barang (Akan diuji via Postman dengan method GET)
Route::get('/items', function () {
    return response()->json(Item::all(), 200);
});

// 2. Endpoint untuk menambah barang (Akan diuji via Postman dengan method POST)
Route::post('/items', function (Request $request) {
    // Tembok validasi yang nanti akan Anda "serang" saat QA Testing
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ]);

    $item = Item::create($validated);
    return response()->json($item, 201);

});

// Rute Publik (Akses Login)
    Route::post('/login', [AuthController::class, 'login']);

    // Rute Terproteksi (Wajib Token Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Endpoint Asset
        Route::get('/assets', [AssetController::class, 'index']);
        Route::get('/assets/scan/{tag}', [AssetController::class, 'scanQr']);
    });