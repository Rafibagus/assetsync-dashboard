<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTicket extends Model
{
    // Mengizinkan mass-assignment untuk kolom-kolom ini
    protected $fillable = [
        'asset_id', 
        'issue_title', 
        'issue_description',
        'photo_path', 
        'status'
    ];

    // Relasi Inverse One-to-Many: Tiket ini milik 1 Aset
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}