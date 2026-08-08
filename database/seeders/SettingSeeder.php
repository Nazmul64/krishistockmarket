<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_settings')->insert(
            [
                [
                    'name' => 'title',
                    'value' => 'ikrishiporibar',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'logo',
                    'value' => 'default-logo.png',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'favicon',
                    'value' => 'default-favicon.png',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'address1',
                    'value' => 'Dhaka, Narsingdi',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'address2',
                    'value' => 'Dhaka, Narsingdi',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'phone1',
                    'value' => '+88016*******',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'phone2',
                    'value' => '+88016*******',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'email1',
                    'value' => 'siteemail@gmail.com',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'email2',
                    'value' => 'siteemail2@gmail.com',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'about_us_text',
                    'value' => 'This is about us',
                    'created_at' => Carbon::now()
                ],   [
                    'name' => 'about_us_text2',
                    'value' => 'This is about us',
                    'created_at' => Carbon::now()
                ],







                [
                    'name' => 'slider1_text',
                    'value' => 'This is Slider 1',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider2_text',
                    'value' => 'This is Slider 2',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider3_text',
                    'value' => 'This is Slider 3',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider4_text',
                    'value' => 'This is Slider 4',
                    'created_at' => Carbon::now()
                ],
// ==============
                [
                    'name' => 'slider1_description',
                    'value' => 'This is Slider 4',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider2_description',
                    'value' => 'This is Slider 4',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider3_description',
                    'value' => 'This is Slider 4',
                    'created_at' => Carbon::now()
                ],

                [
                    'name' => 'slider4_description',
                    'value' => 'This is Slider 4',
                    'created_at' => Carbon::now()
                ],
// ==============
                [
                    'name' => 'slider1_img',
                    'value' => 'slider1_img.png',
                    'created_at' => Carbon::now()
                ],               [
                    'name' => 'slider2_img',
                    'value' => 'slider2_img.png',
                    'created_at' => Carbon::now()
                ],               [
                    'name' => 'slider3_img',
                    'value' => 'slider3_img.png',
                    'created_at' => Carbon::now()
                ],               [
                    'name' => 'slider4_img',
                    'value' => 'slider4_img.png',
                    'created_at' => Carbon::now()
                ],













                [
                    'name' => 'facbook_link',
                    'value' => 'http:://facebook.com',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'linkedin_link',
                    'value' => 'http:://Linkedin.com',
                    'created_at' => Carbon::now()
                ],
                [
                    'name' => 'twitter_link',
                    'value' => 'http:://twitter.com',
                    'created_at' => Carbon::now()
                ]
            ]
        );
    }
}
