<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardNumber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminCardNumberController extends Controller
{
    public function index(Request $request)
    {
        $query = CardNumber::with('user')->orderBy('id', 'desc');

        if ($request->filled('status')) {
            if ($request->status == 'available') {
                $query->where('is_used', false);
            } elseif ($request->status == 'used') {
                $query->where('is_used', true);
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('number', 'like', "%{$search}%");
        }

        $card_numbers = $query->paginate(20);
        $total_count = CardNumber::count();
        $available_count = CardNumber::where('is_used', false)->count();
        $used_count = CardNumber::where('is_used', true)->count();

        return view('admin.card_numbers.index', compact('card_numbers', 'total_count', 'available_count', 'used_count'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:500',
        ]);

        $quantity = (int) $request->quantity;
        $created_count = 0;

        for ($i = 0; $i < $quantity; $i++) {
            do {
                // Generate a random 12-digit string
                $number = sprintf("%012d", mt_rand(1, 999999999999));
                // Alternatively: mt_rand(100000000000, 999999999999)
                if (strlen($number) < 12) {
                    $number = str_pad(mt_rand(100000, 999999) . mt_rand(100000, 999999), 12, '0', STR_PAD_LEFT);
                }
            } while (CardNumber::where('number', $number)->exists());

            CardNumber::create([
                'number'  => $number,
                'amount'  => 300.00,
                'is_used' => false,
            ]);

            $created_count++;
        }

        return redirect()->back()->with('success', "{$created_count} 12-Digit Number(s) generated successfully!");
    }

    public function update(Request $request, $id)
    {
        $card = CardNumber::findOrFail($id);

        $request->validate([
            'number' => 'required|string|digits:12|unique:card_numbers,number,' . $id,
            'amount' => 'required|numeric|min:0',
        ], [
            'number.required' => '12-Digit number is required',
            'number.digits'   => 'Number must be exactly 12 digits',
            'number.unique'   => 'This 12-digit number already exists',
        ]);

        $card->update([
            'number' => trim($request->number),
            'amount' => $request->amount,
        ]);

        return redirect()->back()->with('success', '12-Digit Number updated successfully.');
    }

    public function destroy($id)
    {
        $card = CardNumber::findOrFail($id);
        $card->delete();

        return redirect()->back()->with('success', '12-Digit Number deleted successfully.');
    }
}
