<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Generator $faker)
    {
        $demoUser = User::create([
            'name'              => $faker->name,
            'username'          => 'superadmin',
            'role'              => 'admin',
            'referral_id'       => '0',
            'email'             => 'admin@gmail.com',
            'avatar'            => "default-user.png",
            'phone'             => $faker->phoneNumber,
            'password'          => Hash::make('admin@gmail.com'),
            'email_verified_at' => now(),
        ]);
    }
}
