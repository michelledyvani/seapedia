<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private function getStore() { return Auth::user()->store; }

    public function index()
    {
        $store = $this->getStore();
        if (!$store) return redirect()->route('seller.store.create')->with('error', 'Buat toko dulu!');
        $products = $store->products()->latest()->paginate(10);
        return view('seller.products.index', compact('store', 'products'));
    }

    public function create()
    {
        $store = $this->getStore();
        if (!$store) return redirect()->route('seller.store.create');
        return view('seller.products.create', compact('store'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();
        if (!$store) return redirect()->route('seller.store.create');

        $v = $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image_url'   => 'nullable|url|max:500',
        ]);

        $store->products()->create([
            'name'        => strip_tags($v['name']),
            'description' => strip_tags($v['description']),
            'price'       => $v['price'],
            'stock'       => $v['stock'],
            'image_url'   => $v['image_url'] ?? null,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk ditambahkan!');
    }

    public function edit(Product $product)
    {
        $store = $this->getStore();
        if (!$store || $product->store_id !== $store->id) abort(403);
        return view('seller.products.edit', compact('product', 'store'));
    }

    public function update(Request $request, Product $product)
    {
        $store = $this->getStore();
        if (!$store || $product->store_id !== $store->id) abort(403);

        $v = $request->validate([
            'name'        => 'required|string|max:200',
            'description' => 'required|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image_url'   => 'nullable|url|max:500',
        ]);

        $product->update([
            'name'        => strip_tags($v['name']),
            'description' => strip_tags($v['description']),
            'price'       => $v['price'],
            'stock'       => $v['stock'],
            'image_url'   => $v['image_url'] ?? null,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $store = $this->getStore();
        if (!$store || $product->store_id !== $store->id) abort(403);
        $product->delete();
        return redirect()->route('seller.products.index')->with('success', 'Produk dihapus.');
    }
}
