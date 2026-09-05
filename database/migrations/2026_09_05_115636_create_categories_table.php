<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('prefix_code', 10)->unique()->comment('Kode awalan untuk generate barcode, misal: IT, MBL');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Mencegah data kategori hilang permanen
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};