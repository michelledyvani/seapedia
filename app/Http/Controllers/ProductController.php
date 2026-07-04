<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('store')->where('stock', '>', 0);
        if ($request->filled('q')) {
            $q = strip_tags($request->q);
            $query->where('name', 'like', '%' . $q . '%');
        }
        $products = $query->latest()->paginate(12);
        return view('public.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('store');
        return view('public.products.show', compact('product'));
    }
}
