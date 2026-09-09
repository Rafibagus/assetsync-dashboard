<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Department;
use App\Models\Asset;
use App\Models\MaintenanceTicket;

class AssetSupportSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Data Master Lokasi
        $locations = [
            Location::firstOrCreate(['name' => 'Gedung A - Pusat']),
            Location::firstOrCreate(['name' => 'Gedung B - Operasional']),
            Location::firstOrCreate(['name' => 'Ruang Server Utama']),
            Location::firstOrCreate(['name' => 'Gudang Inventaris']),
        ];

        // 2. Buat Data Master Departemen
        $departments = [
            Department::firstOrCreate(['name' => 'IT Support & Infrastructure']),
            Department::firstOrCreate(['name' => 'Human Resources (HRD)']),
            Department::firstOrCreate(['name' => 'Finance & Accounting']),
            Department::firstOrCreate(['name' => 'General Affairs']),
        ];

        // 3. Update aset yang sudah ada agar memiliki lokasi & departemen
        $assets = Asset::all();
        foreach ($assets as $asset) {
            // Pilih lokasi dan departemen secara acak dari array di atas
            $asset->update([
                'location_id' => $locations[array_rand($locations)]->id,
                'department_id' => $departments[array_rand($departments)]->id,
            ]);
        }

        // 4. Buat Tiket Perawatan otomatis untuk aset yang berstatus 'Maintenance'
        $maintenanceAssets = Asset::where('status', 'Maintenance')->get();
        foreach ($maintenanceAssets as $asset) {
            // Pastikan tidak membuat tiket ganda jika seeder dijalankan berkali-kali
            if ($asset->maintenanceTickets()->count() == 0) {
                MaintenanceTicket::create([
                    'asset_id' => $asset->id,
                    'issue_title' => 'Inspeksi & Perbaikan: ' . $asset->name,
                    'issue_description' => 'Aset ini terindikasi mengalami kendala atau telah masuk jadwal perawatan rutin. Mohon teknisi segera melakukan pengecekan fisik di ' . $asset->location->name . '.',
                    'status' => 'Pending',
                ]);
            }
        }
        
        $this->command->info('Data Lokasi, Departemen, dan Tiket berhasil disuntikkan!');
    }
}