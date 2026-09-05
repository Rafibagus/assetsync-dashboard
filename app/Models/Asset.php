<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_tag',
        'name',
        'category_id',
        'purchase_date',
        'purchase_cost',
        'warranty_months',
        'status',
    ];

    /**
     * Data Casting:
     * Memastikan tipe data yang keluar/masuk dari database sudah sesuai dengan tipe asli PHP.
     * Sangat penting untuk sistem akuntansi/inventory agar tidak terjadi bug kalkulasi.
     */
    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2', // Memastikan selalu ada 2 angka di belakang koma
        'warranty_months' => 'integer',
    ];

    /**
     * Relasi Inverse One-to-Many: 
     * Aset ini merujuk ke satu Kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Local Scope: Mempermudah query pencarian aset yang tersedia.
     * Cara panggil di Controller: Asset::available()->get();
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }
}