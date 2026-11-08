<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            SettingSeeder::class,
            StockPresetSeeder::class,
            MonthlyBazaarSeeder::class,
            EmployeeSeeder::class,
            SupplierSeeder::class,
            CardBenefitSeeder::class,
            UserTypeSeeder::class,
            PermissionCatalogueSeeder::class,
            RoleSeeder::class,
            RbacUserSeeder::class,
            RbacSampleDataSeeder::class,
        ]);
    }
}
