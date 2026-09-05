<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $totalData = 10000; // Ubah jumlah data yang ingin di-generate (misal: 5.000 atau 10.000)
        $chunkSize = 1000;  // Insert per 1.000 data sekaligus untuk performa maksimal

        $this->command->info("Memulai pembuatan {$totalData} data aset dummy...");

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->error("Tabel categories masih kosong. Jalankan CategorySeeder terlebih dahulu.");
            return;
        }

        $assetNames = [
            'MacBook Pro 14" M2', 'MacBook Air 13" M1', 'ThinkPad X1 Carbon',
            'Dell XPS 15', 'ASUS ROG Zephyrus', 'Monitor LG 27"', 'Monitor Dell 24"',
            'Cisco Switch 24-Port', 'MikroTik Router', 'Kursi Ergotec', 'Stand-Desk 140cm',
            'Toyota Avanza Operasional', 'Honda Vario 160', 'iPad Pro 11"', 'Galaxy S23'
        ];

        $statuses = ['Available', 'Deployed', 'Maintenance', 'Retired'];

        $records = [];
        $uniqueCounter = 100000;

        for ($i = 1; $i <= $totalData; $i++) {
            $category = $categories->random();
            $uniqueCounter++;

            $records[] = [
                'asset_tag'       => $category->prefix_code . '-' . $uniqueCounter,
                'name'            => $assetNames[array_rand($assetNames)] . ' #' . $i,
                'category_id'     => $category->id,
                'purchase_date'   => now()->subDays(rand(10, 1500))->toDateString(),
                'purchase_cost'   => rand(15, 400) * 100000,
                'warranty_months' => [12, 24, 36][array_rand([12, 24, 36])],
                'status'          => $statuses[array_rand($statuses)],
                'created_at'      => now(),
                'updated_at'      => now(),
                'deleted_at'      => null,
            ];

            // Insert ke database setiap kali array mencapai ukuran chunk
            if (count($records) === $chunkSize) {
                DB::table('assets')->insert($records);
                $records = []; // Bersihkan memori RAM
                $this->command->info("Tersimpan: {$i} / {$totalData} data...");
            }
        }

        // Insert sisa data jika ada
        if (!empty($records)) {
            DB::table('assets')->insert($records);
        }

        $this->command->info("Selesai! Berhasil meng-generate {$totalData} data aset.");
    }
}