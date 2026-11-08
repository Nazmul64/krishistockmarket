<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name'        => 'Internal (অভ্যন্তরীণ কর্মী)',
                'code'        => 'internal',
                'is_internal' => true,
                'status'      => 'active',
            ],
            [
                'name'        => 'External (বাইরের ব্যবসায়িক/কাস্টমার)',
                'code'        => 'external',
                'is_internal' => false,
                'status'      => 'active',
            ],
            [
                'name'        => 'Service (সিস্টেম/ইন্টিগ্রেশন অ্যাকাউন্ট)',
                'code'        => 'service',
                'is_internal' => false,
                'status'      => 'active',
            ],
        ];

        foreach ($types as $type) {
            UserType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}
