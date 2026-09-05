<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop & Notebook', 'prefix_code' => 'LP', 'description' => 'Perangkat laptop operasional karyawan'],
            ['name' => 'Monitor & Display', 'prefix_code' => 'MN', 'description' => 'Monitor eksternal dan smart display'],
            ['name' => 'Server & Networking', 'prefix_code' => 'SRV', 'description' => 'Server rak, switch, dan router'],
            ['name' => 'Perabot Kantor', 'prefix_code' => 'FUR', 'description' => 'Meja kerja, kursi ergonomis, lemari berkas'],
            ['name' => 'Kendaraan Operasional', 'prefix_code' => 'VHC', 'description' => 'Mobil dan motor operasional kantor'],
            ['name' => 'Smartphone & Tablet', 'prefix_code' => 'MOB', 'description' => 'Perangkat mobile untuk tim lapangan dan testing'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['prefix_code' => $category['prefix_code']],
                $category
            );
        }
    }
}