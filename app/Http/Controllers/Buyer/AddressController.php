<?php
namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->get();
        return view('buyer.address.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'full_address'   => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'is_default'     => 'nullable|boolean',
        ]);

        $user = Auth::user();
        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'label'          => strip_tags($v['label']),
            'recipient_name' => strip_tags($v['recipient_name']),
            'phone'          => strip_tags($v['phone']),
            'full_address'   => strip_tags($v['full_address']),
            'city'           => strip_tags($v['city']),
            'province'       => strip_tags($v['province']),
            'is_default'     => $request->boolean('is_default'),
        ]);

        return redirect()->route('buyer.addresses')->with('success', 'Alamat ditambahkan.');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return redirect()->route('buyer.addresses')->with('success', 'Alamat dihapus.');
    }
}
