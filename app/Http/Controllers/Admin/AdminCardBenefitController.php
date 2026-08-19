<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardBenefit;
use Illuminate\Support\Str;

class AdminCardBenefitController extends Controller
{
    public function index()
    {
        $cards = CardBenefit::orderBy('order_num', 'asc')->latest()->get();
        return view('admin.card_benefits.index', compact('cards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_name' => 'required|string|max:255',
            'card_type' => 'required|string|max:50',
            'badge_text' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'brochure_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:6144',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/cards');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = 'card_' . time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $imageName);
            $imagePath = 'upload/cards/' . $imageName;
        }

        $brochurePath = null;
        if ($request->hasFile('brochure_image')) {
            $uploadDir = public_path('upload/cards');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $brochureName = 'brochure_' . time() . '_' . Str::random(6) . '.' . $request->file('brochure_image')->getClientOriginalExtension();
            $request->file('brochure_image')->move($uploadDir, $brochureName);
            $brochurePath = 'upload/cards/' . $brochureName;
        }

        // Process facilities list from input (either array or newline-separated text)
        $facilities = [];
        if ($request->has('facilities')) {
            if (is_array($request->facilities)) {
                $facilities = array_filter(array_map('trim', $request->facilities));
            } else {
                $lines = explode("\n", str_replace("\r", "", $request->facilities));
                $facilities = array_filter(array_map('trim', $lines));
            }
        }

        CardBenefit::create([
            'card_name' => $request->card_name,
            'card_type' => $request->card_type,
            'badge_text' => $request->badge_text,
            'card_number_sample' => $request->card_number_sample,
            'validity' => $request->validity ?? '12/2030',
            'card_fee' => $request->card_fee,
            'investment_limit' => $request->investment_limit,
            'monthly_profit' => $request->monthly_profit,
            'withdrawal_notice' => $request->withdrawal_notice,
            'image' => $imagePath,
            'brochure_image' => $brochurePath,
            'short_description' => $request->short_description,
            'facilities' => array_values($facilities),
            'card_color_theme' => $request->card_color_theme ?? 'gold',
            'action_button_text' => $request->action_button_text ?? 'কার্ডের জন্য আবেদন করুন',
            'action_button_url' => $request->action_button_url ?? '/register',
            'status' => $request->has('status') ? 1 : 0,
            'order_num' => $request->order_num ?? 0,
        ]);

        $notification = [
            'message' => 'কার্ড সুবিধাসমূহ সফলভাবে যোগ করা হয়েছে!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $card = CardBenefit::findOrFail($id);
        return view('admin.card_benefits.edit', compact('card'));
    }

    public function update(Request $request, $id)
    {
        $card = CardBenefit::findOrFail($id);

        $request->validate([
            'card_name' => 'required|string|max:255',
            'card_type' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            'brochure_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:6144',
        ]);

        $imagePath = $card->image;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('upload/cards');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($card->image && file_exists(public_path($card->image)) && !Str::contains($card->image, ['krishi_sme_gold_card.png', 'krishi_sme_red_card.png'])) {
                @unlink(public_path($card->image));
            }
            $imageName = 'card_' . time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $imageName);
            $imagePath = 'upload/cards/' . $imageName;
        }

        $brochurePath = $card->brochure_image;
        if ($request->hasFile('brochure_image')) {
            $uploadDir = public_path('upload/cards');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($card->brochure_image && file_exists(public_path($card->brochure_image)) && !Str::contains($card->brochure_image, ['brochure_gold_card.png', 'brochure_red_card.png'])) {
                @unlink(public_path($card->brochure_image));
            }
            $brochureName = 'brochure_' . time() . '_' . Str::random(6) . '.' . $request->file('brochure_image')->getClientOriginalExtension();
            $request->file('brochure_image')->move($uploadDir, $brochureName);
            $brochurePath = 'upload/cards/' . $brochureName;
        }

        // Process facilities list
        $facilities = [];
        if ($request->has('facilities')) {
            if (is_array($request->facilities)) {
                $facilities = array_filter(array_map('trim', $request->facilities));
            } else {
                $lines = explode("\n", str_replace("\r", "", $request->facilities));
                $facilities = array_filter(array_map('trim', $lines));
            }
        }

        $card->update([
            'card_name' => $request->card_name,
            'card_type' => $request->card_type,
            'badge_text' => $request->badge_text,
            'card_number_sample' => $request->card_number_sample,
            'validity' => $request->validity ?? '12/2030',
            'card_fee' => $request->card_fee,
            'investment_limit' => $request->investment_limit,
            'monthly_profit' => $request->monthly_profit,
            'withdrawal_notice' => $request->withdrawal_notice,
            'image' => $imagePath,
            'brochure_image' => $brochurePath,
            'short_description' => $request->short_description,
            'facilities' => array_values($facilities),
            'card_color_theme' => $request->card_color_theme ?? 'gold',
            'action_button_text' => $request->action_button_text ?? 'কার্ডের জন্য আবেদন করুন',
            'action_button_url' => $request->action_button_url ?? '/register',
            'status' => $request->has('status') ? 1 : 0,
            'order_num' => $request->order_num ?? 0,
        ]);

        $notification = [
            'message' => 'কার্ড সুবিধাসমূহ সফলভাবে আপডেট করা হয়েছে!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.card_benefits.index')->with($notification);
    }

    public function destroy($id)
    {
        $card = CardBenefit::findOrFail($id);
        if ($card->image && file_exists(public_path($card->image)) && !Str::contains($card->image, ['krishi_sme_gold_card.png', 'krishi_sme_red_card.png'])) {
            @unlink(public_path($card->image));
        }
        if ($card->brochure_image && file_exists(public_path($card->brochure_image)) && !Str::contains($card->brochure_image, ['brochure_gold_card.png', 'brochure_red_card.png'])) {
            @unlink(public_path($card->brochure_image));
        }
        $card->delete();

        $notification = [
            'message' => 'কার্ড সুবিধাসমূহ ডিলিট করা হয়েছে!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function toggleStatus($id)
    {
        $card = CardBenefit::findOrFail($id);
        $card->status = $card->status == 1 ? 0 : 1;
        $card->save();

        return response()->json([
            'success' => true,
            'status' => $card->status,
            'message' => 'স্ট্যাটাস সফলভাবে পরিবর্তন হয়েছে!'
        ]);
    }
}
