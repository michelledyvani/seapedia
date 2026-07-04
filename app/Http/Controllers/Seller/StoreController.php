<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function createOrEdit()
    {
        $store = Auth::user()->store;
        return $store ? view('seller.store.edit', compact('store')) : view('seller.store.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->store) return redirect()->route('seller.store.create')->withErrors(['general' => 'Sudah punya toko.']);

        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:stores,name',
            'description' => 'nullable|string|max:500',
        ]);

        Store::create(['user_id' => $user->id, 'name' => strip_tags($validated['name']), 'description' => strip_tags($validated['description'] ?? '')]);
        return redirect()->route('seller.products.index')->with('success', 'Toko berhasil dibuat!');
    }

    public function update(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('seller.store.create');

        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:stores,name,' . $store->id,
            'description' => 'nullable|string|max:500',
        ]);

        $store->update(['name' => strip_tags($validated['name']), 'description' => strip_tags($validated['description'] ?? '')]);
        return redirect()->route('seller.products.index')->with('success', 'Toko diperbarui.');
    }
}
