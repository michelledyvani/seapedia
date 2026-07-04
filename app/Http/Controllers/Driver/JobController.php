<?php
namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    // Daftar lowongan pengiriman tersedia (status Menunggu Pengirim, belum ada driver)
    public function index()
    {
        $jobs = Order::where('status', 'Menunggu Pengirim')
                     ->whereNull('driver_id')
                     ->with('store', 'buyer', 'address', 'items')
                     ->latest()
                     ->paginate(10);
        return view('driver.jobs.index', compact('jobs'));
    }

    // Detail satu lowongan
    public function show(Order $order)
    {
        if ($order->status !== 'Menunggu Pengirim' || $order->driver_id !== null) {
            return redirect()->route('driver.jobs')->with('error', 'Lowongan ini sudah diambil atau tidak tersedia.');
        }
        $order->load('store', 'buyer', 'address', 'items');
        return view('driver.jobs.show', compact('order'));
    }

    // Ambil lowongan (kunci agar tidak ganda)
    public function take(Order $order)
    {
        $driver = Auth::user();

        // Pakai DB transaction + lockForUpdate agar tidak ada dua driver ambil order yang sama
        DB::transaction(function () use ($order, $driver) {
            $fresh = Order::lockForUpdate()->find($order->id);

            if ($fresh->status !== 'Menunggu Pengirim' || $fresh->driver_id !== null) {
                throw new \Exception('Lowongan sudah diambil driver lain.');
            }

            $fresh->update(['driver_id' => $driver->id]);
            $fresh->updateStatus('Sedang Dikirim', 'Driver ' . $driver->name . ' mengambil pesanan.');
        });

        return redirect()->route('driver.dashboard')->with('success', 'Berhasil mengambil pesanan!');
    }

    // Konfirmasi selesai antar
    public function complete(Order $order)
    {
        $driver = Auth::user();

        if ($order->driver_id !== $driver->id) abort(403);
        if ($order->status !== 'Sedang Dikirim') {
            return back()->with('error', 'Status pesanan tidak valid.');
        }

        $order->updateStatus('Pesanan Selesai', 'Driver mengkonfirmasi pengiriman selesai.');

        return redirect()->route('driver.dashboard')->with('success', 'Pesanan selesai! Pendapatan tercatat.');
    }

    // Dashboard driver: job aktif + riwayat + pendapatan
    public function dashboard()
    {
        $driver      = Auth::user();
        $activeJob   = Order::where('driver_id', $driver->id)->where('status', 'Sedang Dikirim')->with('store','buyer','address')->first();
        $history     = Order::where('driver_id', $driver->id)->where('status', 'Pesanan Selesai')->with('store','buyer')->latest()->paginate(5);
        $totalEarned = Order::where('driver_id', $driver->id)->where('status', 'Pesanan Selesai')->sum('delivery_fee');

        return view('driver.dashboard', compact('driver', 'activeJob', 'history', 'totalEarned'));
    }
}
