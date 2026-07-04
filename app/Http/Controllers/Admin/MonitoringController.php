<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Store, Product, Order, Voucher, Promo};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'          => User::count(),
            'stores'         => Store::count(),
            'products'       => Product::count(),
            'orders'         => Order::count(),
            'vouchers'       => Voucher::count(),
            'promos'         => Promo::count(),
            'active_drivers' => Order::where('status','Sedang Dikirim')->whereNotNull('driver_id')->distinct('driver_id')->count(),
            'overdue'        => Order::whereNotIn('status',['Pesanan Selesai','Dikembalikan'])
                                    ->where('overdue_at','<', Carbon::now())
                                    ->count(),
        ];
        $recentOrders    = Order::with('buyer','store')->latest()->take(10)->get();
        $overdueOrders   = Order::whereNotIn('status',['Pesanan Selesai','Dikembalikan'])
                                ->where('overdue_at','<', Carbon::now())
                                ->where('refunded', false)
                                ->with('buyer','store')
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'overdueOrders'));
    }

    public function orders()
    {
        $orders = Order::with('buyer','store','driver')->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function users()
    {
        $users = User::with('userRoles')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }
}
