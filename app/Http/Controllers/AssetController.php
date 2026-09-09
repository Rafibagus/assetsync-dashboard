<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Http\Requests\StoreAssetRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    /**
     * Menampilkan daftar aset.
     * Mendukung pemrosesan server-side (AJAX) untuk Yajra DataTables dan view biasa.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Eager loading relasi category dan ambil kolom spesifik dari tabel assets
            $assets = Asset::with('category')->select('assets.*');

            return DataTables::of($assets)
                ->addIndexColumn()
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '-';
                })
                ->editColumn('status', function ($row) {
                    // Menggunakan inline CSS agar tidak diblokir oleh compiler Tailwind
                    $colors = [
                        'Available'   => 'background-color: #d1fae5; color: #065f46;', // Hijau
                        'Deployed'    => 'background-color: #dbeafe; color: #1e40af;', // Biru
                        'Maintenance' => 'background-color: #fef3c7; color: #92400e;', // Kuning
                        'Retired'     => 'background-color: #fee2e2; color: #991b1b;', // Merah
                    ];
                    $style = $colors[$row->status] ?? 'background-color: #f3f4f6; color: #1f2937;';
                    
                    return '<span style="' . $style . ' padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">' . e($row->status) . '</span>';
                })
                ->editColumn('purchase_cost', function ($row) {
                    return $row->purchase_cost ? 'Rp ' . number_format($row->purchase_cost, 0, ',', '.') : '-';
                })
                ->addColumn('action', function ($row) {
                    $showUrl   = route('assets.show', $row->id);
                    $editUrl   = route('assets.edit', $row->id);
                    $deleteUrl = route('assets.destroy', $row->id);
                    $reportUrl = route('tickets.create', $row->id); // Rute Lapor Rusak

                    // Tombol bawaan
                    $detailBtn = '<a href="' . $showUrl . '" style="color: #4f46e5; font-weight: 600; margin-right: 12px; text-decoration: none;">Detail</a>';
                    $editBtn   = '<a href="' . $editUrl . '" style="color: #0284c7; font-weight: 600; margin-right: 12px; text-decoration: none;">Edit</a>';
                    $deleteBtn = '<button type="button" onclick="confirmDelete(\'' . $deleteUrl . '\')" style="color: #dc2626; font-weight: 600; cursor: pointer; text-decoration: none; margin-right: 12px;">Hapus</button>';
                    
                    // Tombol Lapor Rusak (Hanya muncul jika status BUKAN Maintenance)
                    $reportBtn = '';
                    if ($row->status !== 'Maintenance') {
                        $reportBtn = '<a href="' . $reportUrl . '" style="color: #ea580c; font-weight: 600; text-decoration: none;">Lapor Rusak</a>';
                    }

                    // Gabungkan semua tombol dalam satu container flex
                    return '<div style="display: flex; align-items: center;">' . $detailBtn . $editBtn . $deleteBtn . $reportBtn . '</div>';
                })  
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('assets.index');
    }

    /**
     * Menampilkan form untuk registrasi aset baru.
     */
    public function create()
        {
            $categories = \App\Models\Category::all();
            $locations = \App\Models\Location::all();
            $departments = \App\Models\Department::all();
            
            return view('assets.create', compact('categories', 'locations', 'departments'));
        }

    /**
     * Menyimpan data aset baru ke database.
     * Validasi ditangani otomatis oleh StoreAssetRequest.
     */
    public function store(StoreAssetRequest $request)
    {
        Asset::create($request->validated());

        return redirect()
            ->route('assets.index')
            ->with('success', 'Aset baru berhasil ditambahkan ke dalam sistem!');
    }

    /**
     * Menampilkan detail lengkap satu aset tertentu.
     */
    public function show(Asset $asset)
    {
        // Load relasi kategori
        $asset->load('category');

        return view('assets.show', compact('asset'));
    }

    /**
     * Menampilkan form untuk mengedit data aset.
     */
    public function edit(Asset $asset)
        {
            $categories = \App\Models\Category::all();
            $locations = \App\Models\Location::all();
            $departments = \App\Models\Department::all();
            
            return view('assets.edit', compact('asset', 'categories', 'locations', 'departments'));
        }

    

    /**
     * Memperbarui data aset di database.
     */
    public function update(Request $request, Asset $asset)
    {
        $validatedData = $request->validate([
            // Abaikan ID aset saat ini agar tidak dianggap duplikat (unique constraint)
            'asset_tag'       => [
                'required',
                'string',
                'max:50',
                Rule::unique('assets', 'asset_tag')->ignore($asset->id),
            ],
            'name'            => ['required', 'string', 'max:255'],
            'category_id'     => ['required', 'exists:categories,id'],
            'purchase_date'   => ['nullable', 'date'],
            'purchase_cost'   => ['nullable', 'numeric', 'min:0'],
            'warranty_months' => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', Rule::in(['Available', 'Deployed', 'Maintenance', 'Retired'])],
        ], [
            'asset_tag.required' => 'Tag/Kode aset wajib diisi.',
            'asset_tag.unique'   => 'Tag/Kode aset ini sudah digunakan pada aset lain.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'status.in'          => 'Status aset tidak dikenali sistem.',
        ]);

        $asset->update($validatedData);

        return redirect()
            ->route('assets.index')
            ->with('success', "Aset {$asset->asset_tag} berhasil diperbarui!");
    }

    /**
     * Menghapus aset (Soft Delete).
     * Mendukung respons JSON (jika dihapus via fetch/AJAX) atau redirect biasa.
     */
    public function destroy(Request $request, Asset $asset)
    {
        $tag = $asset->asset_tag;
        $asset->delete(); // Karena menggunakan SoftDeletes, data tidak hilang permanen

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Aset {$tag} berhasil dihapus!",
            ]);
        }

        return redirect()
            ->route('assets.index')
            ->with('success', "Aset {$tag} berhasil dihapus!");
    }
    public function audit()
{
    // Mengambil aset yang statusnya 'Maintenance' (Kritis/Rusak)
    $criticalAssets = \App\Models\Asset::with('category')
                        ->where('status', 'Maintenance')
                        ->latest()
                        ->get();

    return view('assets.audit', compact('criticalAssets'));
}
}