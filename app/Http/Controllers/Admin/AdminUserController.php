<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function alluser(Request $request){
        $query = User::where('role', 'user')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lock_status')) {
            if ($request->lock_status === 'locked') {
                $query->where('locked_balance', '>', 0);
            } elseif ($request->lock_status === 'unlocked') {
                $query->where(function($q) {
                    $q->whereNull('locked_balance')->orWhere('locked_balance', '<=', 0);
                });
            }
        }

        $all_user = $query->paginate(20);

        return view('admin.all-user', compact('all_user'));
    }

    /**
     * Unlock user's membership frozen balance.
     */
    public function unlockBalance($id)
    {
        $user = User::findOrFail($id);
        $previous_locked = (float) $user->locked_balance;

        $user->update([
            'locked_balance' => 0.00,
        ]);

        return redirect()->back()->with('success', "ইউজার '{$user->name}' (ফোন: {$user->phone})-এর ফ্রিজকৃত ৳" . number_format($previous_locked, 2) . " সফলভাবে আনলক (Unlock) করা হয়েছে!");
    }

    /**
     * Lock user's membership balance.
     */
    public function lockBalance(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $amount = $request->filled('amount') ? (float)$request->amount : ($user->membership_card_type === 'golden' ? 1000.00 : 300.00);

        $user->update([
            'locked_balance' => $amount,
        ]);

        return redirect()->back()->with('success', "ইউজার '{$user->name}' (ফোন: {$user->phone})-এর ৳" . number_format($amount, 2) . " সফলভাবে লক (Lock) করা হয়েছে!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return back()->with('success', "User Delete Sucessfully");
    }
}
