<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique()->comment('ID unik untuk Barcode/QR Code');
            $table->string('name');
            
            // Relasi ke tabel categories
            // restrictOnDelete: Mencegah kategori dihapus jika masih ada aset yang terhubung
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            
            // Kolom lokasi dan vendor (opsional, set nullable jika tabel belum ada)
            // $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            // $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            
            $table->date('purchase_date')->nullable();
            // Presisi 15 digit dengan 2 desimal, standar untuk sistem keuangan/akuntansi
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->integer('warranty_months')->default(0)->comment('Masa garansi dalam bulan');
            
            $table->enum('status', ['Available', 'Deployed', 'Maintenance', 'Retired'])->default('Available');
            
            $table->timestamps();
            $table->softDeletes(); // Standar audit: jangan pernah hapus data aset secara fisik
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};