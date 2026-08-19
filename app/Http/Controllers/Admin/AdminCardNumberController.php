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

        if ($request->filled('card_type')) {
            $query->where('card_type', $request->card_type);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $card_numbers = $query->paginate(20);
        $total_count = CardNumber::count();
        $available_count = CardNumber::where('is_used', false)->count();
        $used_count = CardNumber::where('is_used', true)->count();
        $golden_available_count = CardNumber::where('card_type', 'golden')->where('is_used', false)->count();
        $standard_available_count = CardNumber::where('card_type', 'standard')->where('is_used', false)->count();

        return view('admin.card_numbers.index', compact(
            'card_numbers', 
            'total_count', 
            'available_count', 
            'used_count',
            'golden_available_count',
            'standard_available_count'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:500',
            'card_type' => 'required|string|in:standard,golden',
            'amount' => 'nullable|numeric|min:1',
        ]);

        $quantity = (int) $request->quantity;
        $card_type = $request->input('card_type', 'standard');
        
        if ($request->filled('amount') && (float)$request->amount > 0) {
            $amount = (float) $request->amount;
        } else {
            $amount = ($card_type === 'golden') ? 1000.00 : 300.00;
        }

        $created_count = 0;

        for ($i = 0; $i < $quantity; $i++) {
            do {
                $number = sprintf("%012d", mt_rand(1, 999999999999));
                if (strlen($number) < 12) {
                    $number = str_pad(mt_rand(100000, 999999) . mt_rand(100000, 999999), 12, '0', STR_PAD_LEFT);
                }
            } while (CardNumber::where('number', $number)->exists());

            CardNumber::create([
                'number'    => $number,
                'amount'    => $amount,
                'card_type' => $card_type,
                'is_used'   => false,
            ]);

            $created_count++;
        }

        $type_label = ($card_type === 'golden') ? 'Golden Membership (৳1,000)' : 'Standard Membership (৳300)';
        return redirect()->back()->with('success', "{$created_count} {$type_label} 12-Digit Card(s) generated successfully!");
    }

    public function update(Request $request, $id)
    {
        $card = CardNumber::findOrFail($id);

        $request->validate([
            'number' => 'required|string|digits:12|unique:card_numbers,number,' . $id,
            'amount' => 'required|numeric|min:0',
            'card_type' => 'required|string|in:standard,golden',
        ], [
            'number.required' => '12-Digit number is required',
            'number.digits'   => 'Number must be exactly 12 digits',
            'number.unique'   => 'This 12-digit number already exists',
        ]);

        $card->update([
            'number'    => trim($request->number),
            'amount'    => $request->amount,
            'card_type' => $request->card_type,
        ]);

        return redirect()->back()->with('success', '12-Digit Card Number updated successfully.');
    }

    public function destroy($id)
    {
        $card = CardNumber::findOrFail($id);
        $card->delete();

        return redirect()->back()->with('success', '12-Digit Card Number deleted successfully.');
    }
}
