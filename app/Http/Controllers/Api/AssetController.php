<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Http\Resources\AssetResource;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    // Mengambil semua data dengan pagination untuk list di mobile
    public function index()
    {
        $assets = Asset::with('category')->latest()->paginate(15);
        
        return AssetResource::collection($assets);
    }

    // Endpoint khusus untuk fitur Scan QR Code di Flutter
    public function scanQr($tag)
    {
        $asset = Asset::with('category')->where('asset_tag', $tag)->first();

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Aset tidak ditemukan dalam sistem.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => new AssetResource($asset)
        ]);
    }
}