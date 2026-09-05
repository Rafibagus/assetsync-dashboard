<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        // Ambil satu kategori acak dari database
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        $assetNames = [
            'MacBook Pro 14" M2',
            'MacBook Air 13" M1',
            'ThinkPad X1 Carbon Gen 10',
            'Dell XPS 15',
            'ASUS ROG Zephyrus G14',
            'Monitor LG UltraFine 27"',
            'Monitor Dell Ultrasharp 24"',
            'Cisco Catalyst 2960 Switch',
            'MikroTik Cloud Core Router',
            'Kursi Ergonomis Ergotec',
            'Meja Kerja Adjustable Stand-Desk',
            'Toyota Avanza Veloz 2023',
            'Honda Vario 160cc',
            'iPad Pro 11" M2',
            'Samsung Galaxy S23 Ultra',
        ];

        return [
            // Format tag: [PREFIX]-[RANDOM_NUMBER_6_DIGIT], contoh: LP-482910
            'asset_tag'       => $category->prefix_code . '-' . fake()->unique()->numerify('######'),
            'name'            => fake()->randomElement($assetNames) . ' - ' . fake()->bothify('??-###'),
            'category_id'     => $category->id,
            'purchase_date'   => fake()->dateTimeBetween('-4 years', 'now')->format('Y-m-d'),
            'purchase_cost'   => fake()->numberBetween(15, 450) * 100000, // Rp 1.500.000 s/d Rp 45.000.000
            'warranty_months' => fake()->randomElement([12, 24, 36, 48]),
            'status'          => fake()->randomElement(['Available', 'Deployed', 'Maintenance', 'Retired']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}