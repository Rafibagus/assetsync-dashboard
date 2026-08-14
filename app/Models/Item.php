<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar data bisa disimpan
    protected $fillable = [
        'name', 
        'category', 
        'stock', 
        'price', 
        'image'
    ];
}