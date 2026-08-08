<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = User::where('username', '01700000000')->first();
        if (!$agent) {
            User::create([
                'username'          => '01700000000',
                'name'              => 'বিপণন এজেন্ট ১ (Marketing Agent)',
                'email'             => 'agent@ikrishiporibar.com',
                'phone'             => '01700000000',
                'role'              => 'employee',
                'balance'           => 0.00,
                'password'          => Hash::make('12345678'),
                'email_verified_at' => Carbon::now(),
            ]);
        }
    }
}
