<?php

namespace Database\Seeders;

use App\Models\MonthlyBazaarItem;
use Illuminate\Database\Seeder;

class MonthlyBazaarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => '৳২,৫০০ মাসিক খাদ্য প্যাক',
                'package_name' => '২৫০০ টাকার মাসিক খাদ্য সামগ্রী প্রিমিয়াম প্যাকেজ',
                'price' => 2500,
                'quantity' => 50,
                'sold_quantity' => 0,
                'description' => "মিনিকেট চাল: ১৫ কেজী\nমসুর ডাল: ২ কেজী\nসোয়াবিন তেল: ২ লিটার\nলবণ: ১ কেজী\nপেয়াজ: ৩ কেজী",
                'status' => 'active',
            ],
            [
                'title' => '৳৫,০০০ মাসিক খাদ্য প্যাক',
                'package_name' => '৫০০০ টাকার সম্পূর্ণ পারিবারিক মাসিক বাজার প্যাকেজ',
                'price' => 5000,
                'quantity' => 50,
                'sold_quantity' => 0,
                'description' => "মিনিকেট চাল: ২৫ কেজী\nমসুর ডাল: ৩ কেজী\nসোয়াবিন তেল: ৫ লিটার\nচিনি: ২ কেজী\nলবণ: ২ কেজী\nহলুদ ও মরিচ গুড়া: ৫০০ গ্রাম\nপেয়াজ ও রসুন: ৫ কেজী",
                'status' => 'active',
            ],
        ];

        foreach ($items as $item) {
            MonthlyBazaarItem::firstOrCreate(
                ['title' => $item['title']],
                $item
            );
        }
    }
}
