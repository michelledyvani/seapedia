<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Voucher, Promo};
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        $promos   = Promo::latest()->get();
        return view('admin.discounts.index', compact('vouchers', 'promos'));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|max:50|unique:vouchers,code',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'max_usage'      => 'required|integer|min:1',
            'expires_at'     => 'required|date|after:now',
        ]);

        Voucher::create([
            'code'           => strtoupper(strip_tags($request->code)),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_usage'      => $request->max_usage,
            'expires_at'     => $request->expires_at,
        ]);

        return redirect()->route('admin.discounts')->with('success', 'Voucher berhasil dibuat!');
    }

    public function storePromo(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|max:50|unique:promos,code',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'expires_at'     => 'required|date|after:now',
        ]);

        Promo::create([
            'code'           => strtoupper(strip_tags($request->code)),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'expires_at'     => $request->expires_at,
        ]);

        return redirect()->route('admin.discounts')->with('success', 'Promo berhasil dibuat!');
    }

    public function destroyVoucher(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.discounts')->with('success', 'Voucher dihapus.');
    }

    public function destroyPromo(Promo $promo)
    {
        $promo->delete();
        return redirect()->route('admin.discounts')->with('success', 'Promo dihapus.');
    }
}
