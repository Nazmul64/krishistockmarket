<?php

namespace App\Http\Controllers;

use App\Models\CardBenefit;
use App\Models\Feature;
use App\Models\OurPackage;
use App\Models\Stock;
use App\Models\StockGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function getHomepageData()
    {
        // Sliders
        $sliders = [];
        for ($i = 1; $i <= 4; $i++) {
            $img = setting('slider' . $i . '_img');
            if (!empty($img) && file_exists(public_path('upload/slider/' . $img))) {
                $sliders[] = [
                    'id' => $i,
                    'image' => asset('upload/slider/' . $img),
                    'title' => setting('slider' . $i . '_text') ?: 'কৃষি পরিবারে স্বাগতম',
                    'description' => setting('slider' . $i . '_description') ?: 'আধুনিক ও নিরাপদ কৃষি বিনিয়োগ প্ল্যাটফর্ম',
                ];
            }
        }

        // Offer Banner
        $offer_banner = null;
        $banner_img = setting('offer_banner_img');
        if (!empty($banner_img) && file_exists(public_path('upload/slider/' . $banner_img))) {
            $offer_banner = [
                'image' => asset('upload/slider/' . $banner_img),
                'link' => setting('offer_banner_link') ?: '#',
                'title' => setting('offer_banner_title') ?: 'স্পেশাল অফার',
            ];
        }

        // Live Stocks
        $stocks_query = Stock::latest()->take(6)->get();
        $stocks = $stocks_query->map(function ($stock) {
            $gallery = StockGallery::where('stock_id', $stock->id)->first();
            $image_path = $gallery ? asset('upload/stock_images/' . $gallery->image) : asset('upload/images/backend/logo/' . (setting('logo') ?: 'default-logo.png'));
            
            $last_price = function_exists('StockLastPricing') ? StockLastPricing($stock->id) : null;
            $selling_price = $last_price ? (float)$last_price->selling_price : 0;
            $buying_price = $last_price ? (float)$last_price->buying_price : 0;

            return [
                'id' => $stock->id,
                'name' => $stock->stock_name,
                'category' => $stock->stock_type ?? 'কৃষি পণ্য',
                'quantity' => $stock->stock_quantity ?? 'উপলব্ধ',
                'unit' => $stock->unit ?? 'কেজি',
                'selling_price' => $selling_price,
                'buying_price' => $buying_price,
                'image' => $image_path,
                'details_url' => route('stock.detials', $stock->id),
                'is_unlimited' => (bool)($stock->is_unlimited ?? false),
            ];
        });

        // Our Packages
        $packages = OurPackage::where('status', 1)->latest()->get()->map(function ($pkg) {
            return [
                'id' => $pkg->id,
                'title' => $pkg->title ?? 'প্যাকেজ',
                'image' => asset($pkg->image),
                'description' => $pkg->description ?? '',
                'price' => $pkg->price ?? '',
            ];
        });

        // Card Benefits / Membership Cards
        $card_benefits = CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get()->map(function ($card) {
            $facilities = is_array($card->facilities) ? $card->facilities : (is_string($card->facilities) ? json_decode($card->facilities, true) ?? [] : []);
            return [
                'id' => $card->id,
                'card_name' => $card->card_name,
                'card_type' => $card->card_type,
                'card_color_theme' => $card->card_color_theme ?: 'blue',
                'badge_text' => $card->badge_text ?: ($card->card_color_theme === 'gold' ? 'গোল্ডেন মেম্বারশিপ' : 'নরমাল মেম্বারশিপ'),
                'validity' => $card->validity,
                'card_number_sample' => $card->card_number_sample,
                'image' => $card->image && file_exists(public_path($card->image)) ? asset($card->image) : null,
                'brochure_image' => $card->brochure_image && file_exists(public_path($card->brochure_image)) ? asset($card->brochure_image) : null,
                'short_description' => $card->short_description,
                'card_fee' => $card->card_fee,
                'investment_limit' => $card->investment_limit,
                'monthly_profit' => $card->monthly_profit,
                'withdrawal_notice' => $card->withdrawal_notice,
                'facilities' => $facilities,
                'action_button_text' => $card->action_button_text ?: 'কার্ডের জন্য আবেদন করুন',
                'action_button_url' => $card->action_button_url ?: '/user/register',
            ];
        });

        // Features (Why Choose Us)
        $features = Feature::all()->map(function ($feat) {
            return [
                'id' => $feat->id,
                'title' => $feat->title,
                'description' => $feat->description,
                'icon' => $feat->icon ?: 'fa-check-circle',
                'color' => $feat->color ?: '#1b88ce',
                'image' => (!empty($feat->image) && file_exists(public_path($feat->image))) ? asset($feat->image) : null,
            ];
        });

        // Product Gallery
        $gallery = StockGallery::take(9)->get()->map(function ($img) {
            return [
                'id' => $img->id,
                'title' => 'কৃষি স্টক পণ্য',
                'image' => asset('upload/stock_images/' . $img->image),
            ];
        });

        // Site Settings & Branding
        $logo = setting('logo');
        $favicon = setting('favicon');
        // Clean up duplicate contact info if settings have same values
        $phone1 = setting('phone1') ?: null;
        $phone2 = setting('phone2') ?: null;
        if ($phone2 && $phone2 === $phone1) {
            $phone2 = null;
        }

        $email1 = setting('email1') ?: null;
        $email2 = setting('email2') ?: null;
        if ($email2 && $email2 === $email1) {
            $email2 = null;
        }

        $address1 = setting('address1') ?: null;
        $address2 = setting('address2') ?: null;
        if ($address2 && $address2 === $address1) {
            $address2 = null;
        }

        $site = [
            'title' => setting('title') ?: 'ekrishisheba',
            'font_family' => setting('site_font_family') ?: 'Hind Siliguri',
            'font_url' => getGoogleFontUrl(),
            'logo' => $logo ? asset('upload/images/backend/logo/' . $logo) : asset('assets/images/logo.png'),
            'favicon' => $favicon ? asset('upload/images/backend/logo/' . $favicon) : null,
            'top_notice' => setting('top_notice') ?: (setting('notice') ?: 'ekrishisheba-তে স্বাগতম! আধুনিক ও নিরাপদ কৃষি প্ল্যাটফর্ম।'),
            'phone1' => $phone1,
            'phone2' => $phone2,
            'email1' => $email1,
            'email2' => $email2,
            'address1' => $address1,
            'address2' => $address2,
            'facebook' => setting('facbook_link'),
            'linkedin' => setting('linkedin_link'),
            'twitter' => setting('twitter_link'),
            'instagram' => setting('instagram_link'),
            'youtube' => setting('youtube_link'),
            'about_us_text' => setting('about_us_text') ?: 'ekrishisheba - আপনার বিশ্বস্ত কৃষি প্ল্যাটফর্ম।',
            'footer_about' => setting('footer_about') ?: (setting('about_us_text') ?: 'ekrishisheba - আপনার বিশ্বস্ত কৃষি প্ল্যাটফর্ম।'),
            'footer_copyright' => setting('footer_copyright') ?: ('© ' . date('Y') . ' ekrishisheba. All Rights Reserved.'),
            'footer_developed_by' => setting('footer_developed_by') ?: 'ekrishisheba',
            'footer_developed_by_url' => setting('footer_developed_by_url') ?: '#',
        ];

        // Auth state & Navigation URLs
        $user = Auth::user();
        $auth = [
            'is_authenticated' => (bool)$user,
            'user_name' => $user ? $user->name : null,
            'user_role' => $user ? $user->role : null,
            'dashboard_url' => route('dashboard'),
            'login_url' => route('login'),
            'register_url' => route('register'),
            'logout_url' => route('logout'),
        ];

        $routes = [
            'home' => route('/'),
            'about' => route('about'),
            'stock_index' => route('stock.index'),
            'gallery' => route('gallery'),
            'contact' => route('contact'),
            'contact_send' => route('contact.send'),
            'terms' => route('terms'),
            'privacy' => route('privacy'),
            'career' => route('career'),
            'live_search' => route('api.live_search'),
            'csrf_token' => csrf_token(),
        ];

        return [
            'site' => $site,
            'auth' => $auth,
            'routes' => $routes,
            'sliders' => $sliders,
            'offer_banner' => $offer_banner,
            'stocks' => $stocks,
            'packages' => $packages,
            'card_benefits' => $card_benefits,
            'features' => $features,
            'gallery' => $gallery,
        ];
    }

    public function index()
    {
        $data = $this->getHomepageData();
        return view('frontend.index', compact('data'));
    }

    public function apiHomepageData()
    {
        return response()->json($this->getHomepageData());
    }

    public function apiLiveSearch(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json([
                'query' => $q,
                'total' => 0,
                'stocks' => [],
                'packages' => [],
                'cards' => [],
                'pages' => [],
            ]);
        }

        $term = '%' . $q . '%';

        // 1. Stocks
        $stocks = Stock::where('stock_name', 'like', $term)
            ->orWhere('description', 'like', $term)
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($s) {
                $gallery = StockGallery::where('stock_id', $s->id)->first();
                $image = $gallery ? asset('upload/stock_images/' . $gallery->image) : asset('upload/images/backend/logo/' . (setting('logo') ?: 'default-logo.png'));
                $last_price = function_exists('StockLastPricing') ? StockLastPricing($s->id) : null;
                $selling_price = $last_price ? (float)$last_price->selling_price : 0;
                $buying_price = $last_price ? (float)$last_price->buying_price : 0;
                return [
                    'id' => $s->id,
                    'type' => 'stock',
                    'title' => $s->stock_name,
                    'subtitle' => $s->description ? \Illuminate\Support\Str::limit(strip_tags($s->description), 35) : 'Agro Commodity',
                    'price' => '৳' . number_format($selling_price, 2),
                    'buying_price' => '৳' . number_format($buying_price, 2),
                    'image' => $image,
                    'url' => route('stock.detials', $s->id),
                ];
            });

        // 2. Packages
        $packages = OurPackage::where('status', 1)
            ->where(function ($sub) use ($term) {
                $sub->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($pkg) {
                return [
                    'id' => $pkg->id,
                    'type' => 'package',
                    'title' => $pkg->title ?? 'Package',
                    'subtitle' => $pkg->price ? ('Price: ' . $pkg->price) : 'Exclusive Package',
                    'image' => asset($pkg->image),
                    'url' => route('/') . '#packages',
                ];
            });

        // 3. Membership Cards
        $cards = CardBenefit::where('status', 1)
            ->where(function ($sub) use ($term) {
                $sub->where('card_name', 'like', $term)
                    ->orWhere('card_type', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            })
            ->orderBy('order_num', 'asc')
            ->take(4)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'type' => 'card',
                    'title' => $c->card_name,
                    'subtitle' => $c->badge_text ?: $c->card_type,
                    'image' => $c->image ? asset($c->image) : null,
                    'url' => route('/') . '#card-benefits',
                ];
            });

        // 4. Static Pages & Portals
        $pageDefinitions = [
            ['title_en' => 'Home', 'title_bn' => 'হোম', 'keywords' => 'home main index হোম ekrishisheba', 'url' => route('/')],
            ['title_en' => 'About Us', 'title_bn' => 'আমাদের সম্পর্কে', 'keywords' => 'about us company info আমাদের সম্পর্কে', 'url' => route('about')],
            ['title_en' => 'Stock Market', 'title_bn' => 'স্টক মার্কেট', 'keywords' => 'stock market live price রেট স্টক মার্কেট বাজার দর', 'url' => route('stock.index')],
            ['title_en' => 'Our Packages', 'title_bn' => 'প্যাকেজসমূহ', 'keywords' => 'package offer discount প্যাকেজ আওয়ার প্যাকেজসমূহ', 'url' => route('/') . '#packages'],
            ['title_en' => 'Membership Cards', 'title_bn' => 'মেম্বারশিপ কার্ড', 'keywords' => 'card sme gold golden membership মেম্বারশিপ কার্ড এসএমই গোল্ড', 'url' => route('/') . '#card-benefits'],
            ['title_en' => 'Produce Gallery', 'title_bn' => 'প্রোডাক্ট গ্যালারি', 'keywords' => 'gallery photo images ছবি গ্যালারি প্রোডাক্ট', 'url' => route('gallery')],
            ['title_en' => 'Contact Us', 'title_bn' => 'যোগাযোগ', 'keywords' => 'contact support helpline ফোন ইমেইল যোগাযোগ হেল্পলাইন', 'url' => route('contact')],
            ['title_en' => 'Career', 'title_bn' => 'ক্যারিয়ার', 'keywords' => 'career job path resume cv চাকরি ক্যারিয়ার সার্কুলার নিয়োগ জব আবেদন', 'url' => route('career')],
            ['title_en' => 'Terms & Conditions', 'title_bn' => 'টার্মস এন্ড কন্ডিশন', 'keywords' => 'terms conditions rules নিয়মাবলী শর্তাবলী টার্মস', 'url' => route('terms')],
            ['title_en' => 'Privacy Policy', 'title_bn' => 'প্রাইভেসি পলিসি', 'keywords' => 'privacy policy security গোপনীয়তা প্রাইভেসি পলিসি', 'url' => route('privacy')],
            ['title_en' => 'Login', 'title_bn' => 'লগইন', 'keywords' => 'login signin প্রবেশ লগইন একাউন্ট', 'url' => route('login')],
            ['title_en' => 'Register', 'title_bn' => 'রেজিস্ট্রেশন', 'keywords' => 'register signup join নিবন্ধন রেজিস্ট্রেশন অ্যাকাউন্ট তৈরি', 'url' => route('register')],
        ];

        $pages = [];
        $lowerQ = mb_strtolower($q);
        foreach ($pageDefinitions as $p) {
            if (
                mb_stripos(mb_strtolower($p['title_en']), $lowerQ) !== false ||
                mb_stripos(mb_strtolower($p['title_bn']), $lowerQ) !== false ||
                mb_stripos(mb_strtolower($p['keywords']), $lowerQ) !== false
            ) {
                $pages[] = [
                    'type' => 'page',
                    'title' => $p['title_en'],
                    'title_bn' => $p['title_bn'],
                    'subtitle' => 'Site Page / Navigation',
                    'url' => $p['url'],
                    'icon' => 'fa-external-link',
                ];
            }
        }

        $total = count($stocks) + count($packages) + count($cards) + count($pages);

        return response()->json([
            'query' => $q,
            'total' => $total,
            'stocks' => $stocks,
            'packages' => $packages,
            'cards' => $cards,
            'pages' => $pages,
        ]);
    }
}
