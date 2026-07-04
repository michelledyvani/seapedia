<?php
namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $wallet       = Auth::user()->getOrCreateWallet();
        $transactions = $wallet->transactions()->latest()->paginate(10);
        return view('buyer.wallet.index', compact('wallet', 'transactions'));
    }

    public function topUp(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:10000|max:10000000']);
        $wallet = Auth::user()->getOrCreateWallet();
        $wallet->topUp($request->amount, 'Top-up manual (simulasi)');
        return redirect()->route('buyer.wallet')->with('success', 'Saldo ditambahkan: Rp ' . number_format($request->amount, 0, ',', '.'));
    }
}
