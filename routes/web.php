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

// ═══ BUYER ═══════════════════════════════════════════════════════════════════
Route::middleware(['auth','role:buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/dashboard', function () {
        $user   = Auth::user();
        $wallet = $user->getOrCreateWallet();
        $orders = \App\Models\Order::where('buyer_id', $user->id)->count();
        return view('dashboard.buyer', compact('wallet','orders'));
    })->name('dashboard');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/topup', [WalletController::class, 'topUp'])->name('wallet.topup');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::put('/cart/items/{cartItem}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/orders', function () {
        $orders = \App\Models\Order::where('buyer_id', Auth::id())
                    ->with('store','items')->latest()->paginate(10);
        return view('buyer.orders.index', compact('orders'));
    })->name('orders');

    Route::get('/orders/{order}', function (\App\Models\Order $order) {
        if ($order->buyer_id !== Auth::id()) abort(403);
        $order->load('items','address','store','statusHistories','driver');
        return view('buyer.orders.show', compact('order'));
    })->name('orders.show');
});

// Alias dashboard.buyer
Route::middleware(['auth','role:buyer'])->get('/dashboard/buyer', function () {
    return redirect()->route('buyer.dashboard');
})->name('dashboard.buyer');

// ═══ SELLER ══════════════════════════════════════════════════════════════════
Route::middleware(['auth','role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', function () {
        $store    = Auth::user()->store;
        $products = $store ? $store->products()->count() : 0;
        $orders   = $store ? \App\Models\Order::where('store_id',$store->id)->count() : 0;
        $income   = $store ? \App\Models\Order::where('store_id',$store->id)->where('status','Pesanan Selesai')->sum('subtotal') : 0;
        return view('dashboard.seller', compact('store','products','orders','income'));
    })->name('dashboard');

    Route::get('/store', [StoreController::class, 'createOrEdit'])->name('store.create');
    Route::post('/store', [StoreController::class, 'store'])->name('store.store');
    Route::put('/store', [StoreController::class, 'update'])->name('store.update');

    Route::get('/products', [SellerProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [SellerProductController::class, 'create'])->name('products.create');
    Route::post('/products', [SellerProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [SellerProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [SellerProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders');
    Route::post('/orders/{order}/process', [SellerOrderController::class, 'process'])->name('orders.process');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth','role:seller'])->get('/dashboard/seller', function () {
    return redirect()->route('seller.dashboard');
})->name('dashboard.seller');

// ═══ ADMIN ═══════════════════════════════════════════════════════════════════
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts');
    Route::post('/vouchers', [DiscountController::class, 'storeVoucher'])->name('vouchers.store');
    Route::post('/promos', [DiscountController::class, 'storePromo'])->name('promos.store');
    Route::delete('/vouchers/{voucher}', [DiscountController::class, 'destroyVoucher'])->name('vouchers.destroy');
    Route::delete('/promos/{promo}', [DiscountController::class, 'destroyPromo'])->name('promos.destroy');
});

// ═══ DRIVER ══════════════════════════════════════════════════════════════════
Route::middleware(['auth','role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [JobController::class, 'dashboard'])->name('dashboard');
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs');
    Route::get('/jobs/{order}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{order}/take', [JobController::class, 'take'])->name('jobs.take');
    Route::post('/jobs/{order}/complete', [JobController::class, 'complete'])->name('jobs.complete');
});

Route::middleware(['auth','role:driver'])->get('/dashboard/driver', function () {
    return redirect()->route('driver.dashboard');
})->name('dashboard.driver');



