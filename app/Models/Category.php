<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Wajib menambahkan trait SoftDeletes karena di migrasi kita pakai $table->softDeletes()
    use HasFactory, SoftDeletes;

    // Proteksi Mass Assignment: hanya kolom ini yang boleh diisi lewat method create() atau update()
    protected $fillable = [
        'name',
        'prefix_code',
        'description',
    ];

    /**
     * Relasi One-to-Many: 
     * Satu Kategori bisa menampung/memiliki banyak Aset.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}