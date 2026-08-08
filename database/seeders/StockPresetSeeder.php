<?php

namespace Database\Seeders;

use App\Models\StockPreset;
use Illuminate\Database\Seeder;

class StockPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $presets = [
            [
                'title' => '৳৫,০০০ স্টক',
                'package_name' => 'Stock Package ৳5,000',
                'price' => 5000,
                'quantity' => 10,
            ],
            [
                'title' => '৳১০,০০০ স্টক',
                'package_name' => 'Stock Package ৳10,000',
                'price' => 10000,
                'quantity' => 10,
            ],
            [
                'title' => '৳২০,০০০ স্টক',
                'package_name' => 'Stock Package ৳20,000',
                'price' => 20000,
                'quantity' => 10,
            ],
            [
                'title' => '৳৪০,০০০ স্টক',
                'package_name' => 'Stock Package ৳40,000',
                'price' => 40000,
                'quantity' => 10,
            ],
            [
                'title' => '৳৫০,০০০ স্টক',
                'package_name' => 'Stock Package ৳50,000',
                'price' => 50000,
                'quantity' => 10,
            ],
        ];

        foreach ($presets as $preset) {
            StockPreset::firstOrCreate(
                ['package_name' => $preset['package_name']],
                $preset
            );
        }
    }
}
