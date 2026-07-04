<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\{RegisterController, LoginController, LogoutController};
use App\Http\Controllers\{RoleController, ProductController, AppReviewController};
use App\Http\Controllers\Buyer\{WalletController, AddressController, CartController, CheckoutController};
use App\Http\Controllers\Seller\{StoreController, ProductController as SellerProductController, OrderController as SellerOrderController};
use App\Http\Controllers\Driver\JobController;
use App\Http\Controllers\Admin\{DiscountController, MonitoringController};

// ═══ PUBLIC ══════════════════════════════════════════════════════════════════
Route::get('/', function () {
    $featuredProducts = \App\Models\Product::with('store')->where('stock', '>', 0)->latest()->take(6)->get();
    $latestReviews = \App\Models\AppReview::latest()->take(3)->get();
    return view('public.home', compact('featuredProducts', 'latestReviews'));
})->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/reviews', [AppReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [AppReviewController::class, 'store'])->name('reviews.store');
Route::get('/security-demo', fn() => view('security-demo'))->name('security.demo');
Route::get('/stores/{store}', function (\App\Models\Store $store) {
    $products = $store->products()->where('stock','>',0)->latest()->paginate(12);
    return view('public.stores.show', compact('store', 'products'));
})->name('stores.show');

// ═══ AUTH ════════════════════════════════════════════════════════════════════
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// ═══ ROLE SELECTION ══════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {
    Route::get('/role/select', [RoleController::class, 'selectForm'])->name('role.select');
    Route::post('/role/set', [RoleController::class, 'setRole'])->name('role.set');
});

