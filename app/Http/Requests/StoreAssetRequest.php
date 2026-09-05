<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     * Untuk sekarang set true. Nantinya ini bisa disambungkan dengan Spatie Permission (RBAC).
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Aturan validasi utama.
     */
    public function rules(): array
    {
        return [
            // Wajib diisi, maksimal 50 karakter, dan tidak boleh ada di tabel assets kolom asset_tag
            'asset_tag' => 'required|string|max:50|unique:assets,asset_tag',
            
            'name' => 'required|string|max:255',
            
            // Wajib diisi dan ID yang dikirim harus ada di tabel categories kolom id
            'category_id' => 'required|exists:categories,id',
            
            'purchase_date' => 'nullable|date',
            
            // Harus berupa angka dan tidak boleh minus
            'purchase_cost' => 'nullable|numeric|min:0',
            
            // Harus angka bulat dan tidak boleh minus
            'warranty_months' => 'nullable|integer|min:0',
            
            // Hanya menerima 4 status standar ini
            'status' => 'required|in:Available,Deployed,Maintenance,Retired',
        ];
    }

    /**
     * (Opsional) Custom pesan error agar lebih ramah dibaca pengguna.
     */
    public function messages(): array
    {
        return [
            'asset_tag.required' => 'Tag/Kode Aset wajib diisi.',
            'asset_tag.unique' => 'Tag/Kode Aset ini sudah digunakan. Silakan gunakan kode lain.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid atau sudah dihapus.',
            'status.in' => 'Status aset tidak dikenali oleh sistem.',
        ];
    }
}