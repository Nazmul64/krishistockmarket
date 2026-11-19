<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BoardMember;
use App\Models\BuyStock;
use App\Models\CardBenefit;
use App\Models\CardNumber;
use App\Models\CommunityPost;
use App\Models\CorporateActivity;
use App\Models\CorporateProfile;
use App\Models\Deposit;
use App\Models\EmployeeInfo;
use App\Models\Grievance;
use App\Models\InvestmentSector;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyBazaarOrder;
use App\Models\Notice;
use App\Models\Partnership;
use App\Models\PropertyInquiry;
use App\Models\SellStock;
use App\Models\ShareholderProfile;
use App\Models\SitePaymentSystem;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserInvestment;
use App\Models\UserProfite;
use App\Models\WalletTransaction;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerPortalApiController extends Controller
{
    /**
     * Resolve authenticated user from Sanctum Bearer token or fallback user_id in request.
     */
    protected function resolveUser(Request $request)
    {
        try {
            // 1. Check Sanctum token authentication
            $user = auth('sanctum')->user();
            if ($user) {
                return $user;
            }

            // 2. Check header or parameter user_id (for seamless mobile transitions)
            $userId = $request->header('X-User-Id') ?? $request->input('user_id');
            if ($userId) {
                return User::find($userId);
            }
        } catch (\Throwable $e) {
            \Log::error('ResolveUser error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * 1. Customer / Member Authentication (API Login)
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ইউজারনেম ও পাসওয়ার্ড সঠিকভাবে প্রদান করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $input = trim($request->input('username'));

            // Match by username, email, phone, or customer_id
            $user = User::where('username', $input)
                ->orWhere('email', $input)
                ->orWhere('phone', $input)
                ->orWhere('customer_id', $input)
                ->first();

            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'প্রদত্ত তথ্য বা পাসওয়ার্ড সঠিক নয়। অনুগ্রহ করে পুনরায় চেষ্টা করুন।',
                ], 401);
            }

            if (method_exists($user, 'isActive') && !$user->isActive()) {
                return response()->json([
                    'success' => false,
                    'message' => 'আপনার একাউন্টটি সাময়িকভাবে নিষ্ক্রিয় বা স্থগিত রয়েছে। এডমিনের সাথে যোগাযোগ করুন।',
                ], 403);
            }

            // Update login metadata
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Generate Sanctum token
            $token = $user->createToken('ekrishi_mobile_app')->plainTextToken;

            // Card number
            $cardNumber = function_exists('GetUserCardNumber') ? GetUserCardNumber($user->id) : 'N/A';

            return response()->json([
                'success' => true,
                'message' => 'সফলভাবে লগইন হয়েছে!',
                'token'   => $token,
                'user'    => [
                    'id'                   => $user->id,
                    'name'                 => $user->name,
                    'username'             => $user->username,
                    'email'                => $user->email,
                    'phone'                => $user->phone ?? '',
                    'customer_id'          => $user->customer_id ?? 'CUS-' . $user->id,
                    'role'                 => $user->role ?? 'customer',
                    'balance'              => (float) ($user->balance ?? 0),
                    'locked_balance'       => (float) ($user->locked_balance ?? 0),
                    'membership_card_type' => $user->membership_card_type ?: 'SME',
                    'card_number'          => $cardNumber,
                    'avatar'               => $user->avatar ? url($user->avatar) : null,
                    'created_at'           => $user->created_at ? $user->created_at->format('d M, Y') : null,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'লগইনে সমস্যা হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 2. Customer API Logout
     */
    public function logout(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }
        } catch (\Throwable $e) {
            // Ignore token deletion error on logout
        }

        return response()->json([
            'success' => true,
            'message' => 'আপনি সফলভাবে লগআউট হয়েছেন।',
        ]);
    }

    /**
     * 2.1 Customer / Member Forgot Password (Request OTP via Email)
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে আপনার রেজিস্টার্ড ইমেইল, ইউজারনেম বা ফোন নম্বর প্রদান করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $input = trim($request->input('username'));

            $user = User::where('email', $input)
                ->orWhere('username', $input)
                ->orWhere('phone', $input)
                ->orWhere('customer_id', $input)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'প্রদত্ত তথ্য দিয়ে কোনো অ্যাকাউন্ট খুঁজে পাওয়া যায়নি।',
                ], 404);
            }

            if (empty($user->email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই অ্যাকাউন্টে কোনো ইমেইল ঠিকানা যুক্ত নেই। হেল্পলাইনে যোগাযোগ করুন।',
                ], 422);
            }

            // Generate 6-digit OTP
            $otp = (string) random_int(100000, 999999);
            $cacheKey = 'ekrishi_pwd_otp_' . strtolower(trim($user->email));

            Cache::put($cacheKey, [
                'otp'     => $otp,
                'user_id' => $user->id,
                'email'   => $user->email,
            ], now()->addMinutes(15));

            // Record into password_resets table for redundancy
            try {
                DB::table('password_resets')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'token'      => Hash::make($otp),
                        'created_at' => now(),
                    ]
                );
            } catch (\Throwable $dbEx) {
                Log::warning('Password reset DB insert error: ' . $dbEx->getMessage());
            }

            // Send Email via configured SMTP
            $emailSent = false;
            $mailError = null;
            try {
                Mail::raw(
                    "প্রিয় {$user->name},\n\n" .
                    "আপনার ই-কৃষি সেবা অ্যাকাউন্টের পাসওয়ার্ড রিসেট করার জন্য ৬ ডিজিটের ওটিপি কোড হলো:\n\n" .
                    "      ★ {$otp} ★\n\n" .
                    "কোডটির মেয়াদ আগামী ১৫ মিনিট। নিরাপত্তার স্বার্থে কোডটি কারো সাথে শেয়ার করবেন না।\n\n" .
                    "যদি আপনি পাসওয়ার্ড রিসেট করার অনুরোধ না করে থাকেন, তবে এই মেইলটি উপেক্ষা করুন।\n\n" .
                    "ধন্যবাদান্তে,\n" .
                    "ই-কৃষি সেবা লিমিটেড\n" .
                    "ওয়েবসাইট: https://ekrishisheba.com",
                    function ($message) use ($user) {
                        $message->to($user->email, $user->name)
                                ->subject('ই-কৃষি সেবা - পাসওয়ার্ড রিসেট ওটিপি কোড');
                    }
                );
                $emailSent = true;
            } catch (\Throwable $mailEx) {
                Log::error('Password reset mail send failed: ' . $mailEx->getMessage());
                $mailError = $mailEx->getMessage();
            }

            // Obfuscate email for user display: e.g. "adm***@gmail.com"
            $emailParts = explode('@', $user->email);
            $obfuscated = substr($emailParts[0], 0, min(3, strlen($emailParts[0]))) . '***@' . ($emailParts[1] ?? '');

            return response()->json([
                'success'   => true,
                'message'   => "পাসওয়ার্ড রিসেট ওটিপি আপনার ইমেইল ({$obfuscated})-এ পাঠানো হয়েছে।",
                'email'     => $user->email,
                'mail_sent' => $emailSent,
                'debug_otp' => config('app.debug') ? $otp : null,
            ]);
        } catch (\Throwable $e) {
            Log::error('forgotPassword error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'অনুরোধটি প্রক্রিয়াকরণে সমস্যা হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 2.2 Customer / Member Reset Password with OTP
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'otp'      => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে সকল তথ্য এবং কমপক্ষে ৬ অক্ষরের নতুন পাসওয়ার্ড প্রদান করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $email = strtolower(trim($request->input('email')));
            $inputOtp = trim($request->input('otp'));
            $newPassword = $request->input('password');

            $cacheKey = 'ekrishi_pwd_otp_' . $email;
            $cachedData = Cache::get($cacheKey);

            $isValid = false;
            $userId = null;

            if ($cachedData && isset($cachedData['otp']) && $cachedData['otp'] === $inputOtp) {
                $isValid = true;
                $userId = $cachedData['user_id'] ?? null;
            } else {
                // Redundant check in password_resets table
                $record = DB::table('password_resets')->where('email', $email)->first();
                if ($record && Hash::check($inputOtp, $record->token)) {
                    $isValid = true;
                }
            }

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'প্রদত্ত ওটিপি কোডটি সঠিক নয় অথবা এর মেয়াদ শেষ হয়ে গেছে।',
                ], 400);
            }

            // Find user
            $user = $userId ? User::find($userId) : User::where('email', $email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'ইউজার খুঁজে পাওয়া যায়নি।',
                ], 404);
            }

            // Update password
            $user->password = Hash::make($newPassword);
            $user->save();

            // Clear cache and reset tokens
            Cache::forget($cacheKey);
            DB::table('password_resets')->where('email', $email)->delete();

            return response()->json([
                'success' => true,
                'message' => 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে! এখন নতুন পাসওয়ার্ড দিয়ে লগইন করুন।',
            ]);
        } catch (\Throwable $e) {
            Log::error('resetPassword error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'পাসওয়ার্ড পরিবর্তনের সময় ত্রুটি হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 3. Full Customer Dashboard Data
     */
    public function dashboard(Request $request)
    {
        $user = $this->resolveUser($request);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'অনুমোদিত নয়। অনুগ্রহ করে পুনরায় লগইন করুন।',
            ], 401);
        }

        try {
            // 1. Calculate financials
            $all_sell = (float) SellStock::where('user_id', $user->id)->where('status', 'aproved')->sum('selled_price');
            $all_buy = (float) BuyStock::where('user_id', $user->id)->where('status', 'aproved')->sum('buyed_price');
            $user_profit = (float) UserProfite::where('user_id', $user->id)->sum('profit');

            $active_stocks_count = BuyStock::where('user_id', $user->id)->where('status', 'aproved')->count();
            $bazaar_orders_count = MonthlyBazaarOrder::where('user_id', $user->id)->count();

            // 2. Recent activities
            $recent_bazaar_orders = MonthlyBazaarOrder::where('user_id', $user->id)
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($order) {
                    return [
                        'id'                  => $order->id,
                        'package_title'       => $order->package_title,
                        'quantity'            => $order->quantity,
                        'price'               => (float) $order->price,
                        'total_price'         => (float) $order->total_price,
                        'payment_method'      => $order->payment_method,
                        'status'              => $order->status,
                        'request_area'        => $order->request_area,
                        'agent_point'         => $order->agent_point,
                        'created_at'          => $order->created_at ? $order->created_at->format('d M, Y') : '-',
                    ];
                });

            $recent_buystocks = BuyStock::where('user_id', $user->id)
                ->with('stock')
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($bs) {
                    return [
                        'id'          => $bs->id,
                        'stock_id'    => $bs->stock_id,
                        'stock_name'  => $bs->stock->name ?? ('স্টক #' . $bs->stock_id),
                        'quantity'    => $bs->buy_quantiy,
                        'price'       => (float) $bs->buyed_price,
                        'trx_number'  => $bs->trx_number,
                        'status'      => $bs->status,
                        'created_at'  => $bs->created_at ? $bs->created_at->format('d M, Y') : '-',
                    ];
                });

            $recent_deposits = Deposit::where('user_id', $user->id)
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($dep) {
                    return [
                        'id'              => $dep->id,
                        'amount'          => (float) ($dep->deposit_amount ?? $dep->amount ?? 0),
                        'payment_method'  => $dep->payment_method ?? $dep->method ?? 'Bank/MFS',
                        'from_number'     => $dep->pay_from_number ?? $dep->from_number ?? '-',
                        'trx_number'      => $dep->trx_number ?? $dep->transaction_id ?? '-',
                        'status'          => $dep->status ?? 'pending',
                        'screenshot'      => $dep->screenshot ? url('upload/payment/' . $dep->screenshot) : null,
                        'created_at'      => $dep->created_at ? $dep->created_at->format('d M, Y') : '-',
                    ];
                });

            $userPhone = $user->phone;
            $recent_property_inquiries = PropertyInquiry::where(function ($q) use ($user, $userPhone) {
                $q->where('user_id', $user->id);
                if (!empty($userPhone)) {
                    $q->orWhere('customer_phone', $userPhone);
                }
            })->with('property')
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($inq) {
                    return [
                        'id'             => $inq->id,
                        'property_id'    => $inq->property_id,
                        'property_name'  => $inq->property ? $inq->property->property_name : 'সাধারণ অনুসন্ধান',
                        'inquiry_code'   => $inq->inquiry_code,
                        'inquiry_type'   => $inq->inquiry_type,
                        'preferred_date' => $inq->preferred_date ? date('d M, Y', strtotime($inq->preferred_date)) : '-',
                        'status'         => $inq->status,
                        'created_at'     => $inq->created_at ? $inq->created_at->format('d M, Y') : '-',
                    ];
                });

            $property_inquiries_count = PropertyInquiry::where(function ($q) use ($user, $userPhone) {
                $q->where('user_id', $user->id);
                if (!empty($userPhone)) {
                    $q->orWhere('customer_phone', $userPhone);
                }
            })->count();

            // Card info
            $cardNumber = function_exists('GetUserCardNumber') ? GetUserCardNumber($user->id) : 'N/A';
            $locked_balance = (float) ($user->locked_balance ?? 0.00);
            $available_balance = max(0, (float) $user->balance - $locked_balance);

            return response()->json([
                'success' => true,
                'user'    => [
                    'id'                   => $user->id,
                    'name'                 => $user->name,
                    'username'             => $user->username,
                    'email'                => $user->email,
                    'phone'                => $user->phone ?? '',
                    'customer_id'          => $user->customer_id ?? 'CUS-' . $user->id,
                    'role'                 => $user->role ?? 'customer',
                    'balance'              => (float) ($user->balance ?? 0),
                    'available_balance'    => $available_balance,
                    'locked_balance'       => $locked_balance,
                    'membership_card_type' => $user->membership_card_type ?: 'SME',
                    'card_number'          => $cardNumber,
                    'avatar'               => $user->avatar ? url($user->avatar) : null,
                    'created_at'           => $user->created_at ? $user->created_at->format('d M, Y') : 'Active',
                ],
                'stats'   => [
                    'wallet_balance'           => (float) ($user->balance ?? 0),
                    'all_buy'                  => $all_buy,
                    'all_sell'                 => $all_sell,
                    'user_profit'              => $user_profit,
                    'active_stocks_count'      => $active_stocks_count,
                    'bazaar_orders_count'      => $bazaar_orders_count,
                    'property_inquiries_count' => $property_inquiries_count,
                ],
                'recent_bazaar_orders'      => $recent_bazaar_orders,
                'recent_buystocks'          => $recent_buystocks,
                'recent_deposits'           => $recent_deposits,
                'recent_property_inquiries' => $recent_property_inquiries,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'ড্যাশবোর্ড তথ্য লোড হতে ত্রুটি: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 4. Payment Methods for Deposit
     */
    public function depositMethods()
    {
        try {
            $methods = SitePaymentSystem::all()->map(function ($sys) {
                $logoUrl = null;
                if (!empty($sys->logo)) {
                    $logoUrl = Str::startsWith($sys->logo, ['http://', 'https://']) ? $sys->logo : url($sys->logo);
                }

                return [
                    'id'           => $sys->id,
                    'name'         => $sys->pay_s_name,
                    'number'       => $sys->pay_s_number,
                    'type'         => $sys->pay_s_type ?? 'Personal/Agent',
                    'logo'         => $logoUrl,
                    'instructions' => "উক্ত {$sys->pay_s_name} নাম্বারে ক্যাশ-আউট বা সেন্ট মানি করুন এবং ট্রানজেকশন আইডি ও প্রেরক নাম্বার জমা দিন।",
                ];
            });

            if ($methods->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'methods' => $methods,
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback default methods below
        }

        // High quality fallback payment methods
        $fallback = [
            [
                'id'           => 1,
                'name'         => 'bKash (বিকাশ মার্চেন্ট / এজেন্ট)',
                'number'       => '01712-345678',
                'type'         => 'Merchant / Agent',
                'logo'         => null,
                'instructions' => 'বিকাশ এজেন্ট বা মার্চেন্ট নাম্বারে পেমেন্ট / ক্যাশ-ইন করে ট্রানজেকশন আইডি ও প্রেরক নাম্বার প্রদান করুন।',
            ],
            [
                'id'           => 2,
                'name'         => 'Nagad (নগদ পার্সোনাল)',
                'number'       => '01812-345678',
                'type'         => 'Personal',
                'logo'         => null,
                'instructions' => 'নগদ পার্সোনাল নাম্বারে সেন্ড মানি করুন এবং ট্রানজেকশন আইডি প্রদান করুন।',
            ],
            [
                'id'           => 3,
                'name'         => 'Rocket (রকেট পার্সোনাল)',
                'number'       => '01912-345678-9',
                'type'         => 'Personal',
                'logo'         => null,
                'instructions' => 'রকেট একাউন্টে সেন্ড মানি করে ট্রানজেকশন আইডি প্রদান করুন।',
            ],
            [
                'id'           => 4,
                'name'         => 'Bank Transfer (ইসলামী ব্যাংক বাংলাদেশ)',
                'number'       => 'A/C: 2050XXXXXXXXXXXXX (Branch: Principal)',
                'type'         => 'Corporate Account',
                'logo'         => null,
                'instructions' => 'ব্যাংক একাউন্টে ফান্ড ট্রান্সফার করে ডিপোজিট স্লিপের ছবি বা রেফারেন্স নম্বর দিন।',
            ],
        ];

        return response()->json([
            'success' => true,
            'methods' => $fallback,
        ]);
    }

    /**
     * 5. Submit Deposit Request
     */
    public function submitDeposit(Request $request)
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন।'], 401);
        }

        $validator = Validator::make($request->all(), [
            'deposit_amount'  => 'required|numeric|min:10',
            'payment_method'  => 'required|string',
            'pay_from_number' => 'nullable|string',
            'trx_number'      => 'nullable|string',
            'screenshot'      => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'প্রয়োজনীয় ফিল্ডগুলো পূরণ করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $screenshotName = null;
            if ($request->hasFile('screenshot')) {
                $uploadDir = public_path('upload/payment');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $file = $request->file('screenshot');
                $screenshotName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $screenshotName);
            } elseif ($request->filled('screenshot_base64')) {
                $uploadDir = public_path('upload/payment');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $screenshotName = time() . '_' . Str::random(6) . '.jpg';
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->screenshot_base64));
                file_put_contents($uploadDir . '/' . $screenshotName, $imageData);
            }

            $cardNumber = function_exists('GetUserCardNumber') ? GetUserCardNumber($user->id) : 'N/A';

            $deposit = Deposit::create([
                'user_id'         => $user->id,
                'card_number'     => $cardNumber,
                'deposit_amount'  => $request->deposit_amount,
                'payment_method'  => $request->payment_method,
                'pay_from_number' => $request->pay_from_number ?? '',
                'trx_number'      => $request->trx_number ?? ('TRX-' . strtoupper(Str::random(8))),
                'screenshot'      => $screenshotName,
                'status'          => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'আপনার ডিপোজিট রিকোয়েস্ট সফলভাবে জমা হয়েছে! এডমিন যাচাই করে দ্রুত এপ্রুভ করবেন।',
                'deposit' => $deposit,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'ডিপোজিট জমা দিতে সমস্যা হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 6. Submit Withdrawal Request
     */
    public function submitWithdraw(Request $request)
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন।'], 401);
        }

        $validator = Validator::make($request->all(), [
            'withdraw_amount'   => 'required|numeric|min:50',
            'payment_system_id' => 'required',
            'account_number'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'উত্তোলনের পরিমাণ ও পেমেন্ট মেথড সঠিকভাবে নির্বাচন করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $withdrawAmount = (float) $request->withdraw_amount;
            $locked_balance = (float) ($user->locked_balance ?? 0.00);
            $available_balance = max(0, (float) $user->balance - $locked_balance);

            if ($withdrawAmount > $available_balance) {
                return response()->json([
                    'success' => false,
                    'message' => "পর্যাপ্ত ব্যালেন্স নেই। আপনার বর্তমান উত্তোলনযোগ্য ব্যালেন্স: ৳" . number_format($available_balance, 2),
                ], 400);
            }

            $hasPending = Withdraw::where('user_id', $user->id)->where('status', 'pending')->exists();
            if ($hasPending) {
                return response()->json([
                    'success' => false,
                    'message' => 'আপনার পূর্বের একটি উত্তোলন রিকোয়েস্ট পেন্ডিং রয়েছে। অনুগ্রহ করে অপেক্ষা করুন।',
                ], 400);
            }

            Withdraw::create([
                'user_id'    => $user->id,
                'amount'     => $withdrawAmount,
                'method_id'  => $request->payment_system_id,
                'status'     => 'pending',
                'created_at' => Carbon::now(),
            ]);

            // Deduct balance
            $user->update([
                'balance' => $user->balance - $withdrawAmount,
            ]);

            return response()->json([
                'success'     => true,
                'message'     => 'আপনার টাকা উত্তোলনের আবেদন সফলভাবে গ্রহণ করা হয়েছে!',
                'new_balance' => (float) $user->balance,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'উত্তোলনে সমস্যা হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 7. Full Wallet Ledger
     */
    public function walletLedger(Request $request)
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন।'], 401);
        }

        try {
            $transactions = WalletTransaction::where('user_id', $user->id)
                ->latest()
                ->get()
                ->map(function ($tx) {
                    return [
                        'id'               => $tx->id,
                        'transaction_id'   => $tx->transaction_id,
                        'transaction_type' => $tx->transaction_type,
                        'credit_amount'    => (float) $tx->credit_amount,
                        'debit_amount'     => (float) $tx->debit_amount,
                        'previous_balance' => (float) $tx->previous_balance,
                        'new_balance'      => (float) $tx->new_balance,
                        'payment_method'   => $tx->payment_method,
                        'status'           => $tx->status ?? 'Approved',
                        'created_at'       => $tx->created_at ? $tx->created_at->format('d M, Y h:i A') : '-',
                    ];
                });

            $deposits = Deposit::where('user_id', $user->id)
                ->latest()
                ->get();

            $withdrawals = Withdraw::where('user_id', $user->id)
                ->latest()
                ->get();

            return response()->json([
                'success'      => true,
                'transactions' => $transactions,
                'deposits'     => $deposits,
                'withdrawals'  => $withdrawals,
                'balance'      => (float) $user->balance,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'ওয়ালেট হিস্ট্রি লোড হতে ত্রুটি: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 8. My Stocks (Buy / Sell list)
     */
    public function myStocks(Request $request)
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন।'], 401);
        }

        try {
            $buyStocks = BuyStock::where('user_id', $user->id)
                ->with('stock')
                ->latest()
                ->get()
                ->map(function ($bs) {
                    return [
                        'id'           => $bs->id,
                        'stock_id'     => $bs->stock_id,
                        'stock_name'   => $bs->stock->name ?? ('স্টক #' . $bs->stock_id),
                        'quantity'     => $bs->buy_quantiy,
                        'buyed_price'  => (float) $bs->buyed_price,
                        'trx_number'   => $bs->trx_number,
                        'status'       => $bs->status,
                        'created_at'   => $bs->created_at ? $bs->created_at->format('d M, Y') : '-',
                    ];
                });

            $sellStocks = SellStock::where('user_id', $user->id)
                ->with('stock')
                ->latest()
                ->get()
                ->map(function ($ss) {
                    return [
                        'id'           => $ss->id,
                        'stock_id'     => $ss->stock_id,
                        'stock_name'   => $ss->stock->name ?? ('স্টক #' . $ss->stock_id),
                        'quantity'     => $ss->selled_quantiy,
                        'selled_price' => (float) $ss->selled_price,
                        'status'       => $ss->status,
                        'created_at'   => $ss->created_at ? $ss->created_at->format('d M, Y') : '-',
                    ];
                });

            return response()->json([
                'success'     => true,
                'buy_stocks'  => $buyStocks,
                'sell_stocks' => $sellStocks,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'স্টক তালিকা লোড হতে ত্রুটি: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 9. My Monthly Bazaar Orders
     */
    public function myOrders(Request $request)
    {
        $user = $this->resolveUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন।'], 401);
        }

        try {
            $orders = MonthlyBazaarOrder::where('user_id', $user->id)
                ->latest()
                ->get()
                ->map(function ($o) {
                    return [
                        'id'                  => $o->id,
                        'package_title'       => $o->package_title,
                        'quantity'            => $o->quantity,
                        'price'               => (float) $o->price,
                        'total_price'         => (float) $o->total_price,
                        'payment_method'      => $o->payment_method,
                        'pay_from_number'     => $o->pay_from_number,
                        'trx_number'          => $o->trx_number,
                        'status'              => $o->status,
                        'request_area'        => $o->request_area,
                        'agent_point'         => $o->agent_point,
                        'distribution_status' => $o->distribution_status ?? 'pending',
                        'collection_note'     => $o->collection_note,
                        'created_at'          => $o->created_at ? $o->created_at->format('d M, Y') : '-',
                    ];
                });

            return response()->json([
                'success' => true,
                'orders'  => $orders,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'অর্ডার তালিকা লোড হতে ত্রুটি: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 10. Card Information, Benefits & Digital Card View
     */
    public function cardInfo(Request $request)
    {
        try {
            $user = $this->resolveUser($request);

            $cardBenefits = CardBenefit::where('status', 1)
                ->orderBy('order_num', 'asc')
                ->get()
                ->map(function ($cb) {
                    return [
                        'id'          => $cb->id,
                        'card_type'   => $cb->card_type,
                        'title'       => $cb->title,
                        'price'       => (float) $cb->price,
                        'color'       => $cb->color ?? '#10B981',
                        'gradient'    => $cb->gradient ?? 'linear-gradient(135deg, #064e3b 0%, #047857 100%)',
                        'facilities'  => is_array($cb->facilities) ? $cb->facilities : [],
                    ];
                });

            $userCard = null;
            if ($user) {
                $userCard = [
                    'card_number'          => function_exists('GetUserCardNumber') ? GetUserCardNumber($user->id) : 'N/A',
                    'membership_card_type' => $user->membership_card_type ?: 'SME',
                    'holder_name'          => $user->name,
                    'customer_id'          => $user->customer_id ?? 'CUS-' . $user->id,
                    'balance'              => (float) ($user->balance ?? 0),
                    'member_since'         => $user->created_at ? $user->created_at->format('d M, Y') : 'Active',
                ];
            }

            return response()->json([
                'success'   => true,
                'benefits'  => $cardBenefits,
                'user_card' => $userCard,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'কার্ড তথ্য লোড হতে ত্রুটি: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 11. Apply for Membership or Dealership Card
     */
    public function submitCardApplication(Request $request)
    {
        $user = $this->resolveUser($request);

        $validator = Validator::make($request->all(), [
            'card_type'       => 'required|string',
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'present_address' => 'required|string',
            'payment_method'  => 'required|string',
            'transaction_id'  => 'required|string',
            'nid_number'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'আবেদনের সকল প্রয়োজনীয় তথ্য পূরণ করুন।',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Format card number prefix
            $prefix = match(strtolower($request->card_type)) {
                'sme'      => 'SME',
                'gold'     => 'GLD',
                'property' => 'PRP',
                'platinum' => 'PLT',
                'dealer'   => 'DLR',
                default    => 'AGR',
            };

            $generatedCardNumber = $prefix . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);

            // Update user if logged in
            if ($user) {
                $user->update([
                    'membership_card_type' => strtoupper($request->card_type),
                    'present_address'      => $request->present_address,
                    'nid_number'           => $request->nid_number ?? $user->nid_number,
                ]);

                CardNumber::updateOrCreate(
                    ['used_by' => $user->id],
                    [
                        'number'    => $generatedCardNumber,
                        'card_type' => $request->card_type,
                        'is_used'   => 1,
                        'used_at'   => now(),
                    ]
                );
            }

            return response()->json([
                'success'     => true,
                'message'     => 'মেম্বারশিপ কার্ডের আবেদন সফলভাবে গৃহীত হয়েছে! আপনার আবেদন অনুমোদনের পর কার্ডটি সক্রিয় হবে।',
                'card_number' => $generatedCardNumber,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'আবেদন জমা দিতে সমস্যা হয়েছে: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 12. Corporate & Services Hub Dynamic Information
     */
    public function corporateData(Request $request)
    {
        try {
            // 1. Board of Directors
            $boardMembers = BoardMember::orderBy('order_num', 'asc')->get()->map(function ($bm) {
                return [
                    'id'          => $bm->id,
                    'name'        => $bm->name,
                    'designation' => $bm->designation,
                    'image'       => $bm->image ? url($bm->image) : null,
                    'bio'         => $bm->bio,
                ];
            });

            // 2. Investment Sectors (10 Investment Sectors)
            $sectors = InvestmentSector::where('status', 'active')->get()->map(function ($sec) {
                return [
                    'id'              => $sec->id,
                    'name'            => $sec->sector_name ?? $sec->title,
                    'title'           => $sec->title ?? $sec->sector_name,
                    'icon'            => $sec->icon ?? 'fa-leaf',
                    'image'           => $sec->image ? url($sec->image) : null,
                    'description'     => $sec->description,
                    'min_investment'  => (float) ($sec->min_investment ?? 1000),
                    'expected_return' => $sec->expected_return ?? '১২-১৮% বাৎসরিক মুনাফা',
                ];
            });

            // 3. Notices & Circulars
            $notices = Notice::where('status', 'published')->orWhere('status', 'active')->latest()->take(10)->get()->map(function ($n) {
                return [
                    'id'          => $n->id,
                    'title'       => $n->title,
                    'description' => $n->description,
                    'date'        => $n->created_at ? $n->created_at->format('d M, Y') : '-',
                ];
            });

            return response()->json([
                'success'       => true,
                'board_members' => $boardMembers,
                'sectors'       => $sectors,
                'notices'       => $notices,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success'       => true,
                'board_members' => [],
                'sectors'       => [],
                'notices'       => [],
            ]);
        }
    }
}
