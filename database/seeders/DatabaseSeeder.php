<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User, UserRole, Store, Product, AppReview, Voucher, Promo, Address, Wallet};
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ADMIN ──────────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $admin->id, 'role' => 'admin']);

        // ── SELLER 1 ───────────────────────────────────────────────────────
        $seller1 = User::create([
            'name'     => 'Budi Santoso',
            'username' => 'budi_seller',
            'email'    => 'seller@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $seller1->id, 'role' => 'seller']);

        $store1 = Store::create([
            'user_id'     => $seller1->id,
            'name'        => 'Toko Elektronik Jaya',
            'description' => 'Jual berbagai elektronik berkualitas dengan harga terbaik.',
        ]);

        $products1 = [
            ['name'=>'Headphone Wireless BT500','description'=>'Headphone bluetooth 5.0, noise cancelling aktif, baterai 30 jam.','price'=>899000,'stock'=>20,'image_url'=>'https://placehold.co/400x400/3B82F6/FFFFFF?text=Headphone'],
            ['name'=>'Smartwatch X200','description'=>'Jam tangan pintar, monitor detak jantung, GPS, waterproof IP68.','price'=>1250000,'stock'=>15,'image_url'=>'https://placehold.co/400x400/8B5CF6/FFFFFF?text=Smartwatch'],
            ['name'=>'Powerbank 20000mAh','description'=>'Powerbank kapasitas besar, fast charging 65W, dual output.','price'=>350000,'stock'=>30,'image_url'=>'https://placehold.co/400x400/06B6D4/FFFFFF?text=Powerbank'],
        ];
        foreach ($products1 as $p) { $store1->products()->create($p); }

        // ── SELLER 2 ───────────────────────────────────────────────────────
        $seller2 = User::create([
            'name'     => 'Siti Rahayu',
            'username' => 'siti_seller',
            'email'    => 'seller2@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $seller2->id, 'role' => 'seller']);

        $store2 = Store::create([
            'user_id'     => $seller2->id,
            'name'        => 'Warung Kopi Siti',
            'description' => 'Kopi specialty Indonesia, roast langsung dari petani lokal.',
        ]);

        $products2 = [
            ['name'=>'Kopi Arabika Gayo 500gr','description'=>'Single origin Aceh Gayo, medium roast, aroma floral caramel.','price'=>95000,'stock'=>100,'image_url'=>'https://placehold.co/400x400/92400E/FFFFFF?text=Kopi+Gayo'],
            ['name'=>'Kopi Toraja 250gr','description'=>'Kopi toraja premium, full body, dark roast, cocok untuk espresso.','price'=>75000,'stock'=>80,'image_url'=>'https://placehold.co/400x400/7C2D12/FFFFFF?text=Kopi+Toraja'],
            ['name'=>'Cold Brew Kit','description'=>'Paket lengkap bikin cold brew di rumah, termasuk botol dan filter.','price'=>185000,'stock'=>25,'image_url'=>'https://placehold.co/400x400/1E3A5F/FFFFFF?text=Cold+Brew'],
        ];
        foreach ($products2 as $p) { $store2->products()->create($p); }

        // ── BUYER ──────────────────────────────────────────────────────────
        $buyer = User::create([
            'name'     => 'Dewi Lestari',
            'username' => 'dewi_buyer',
            'email'    => 'buyer@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $buyer->id, 'role' => 'buyer']);

        Wallet::create(['user_id' => $buyer->id, 'balance' => 5000000]);
        Address::create([
            'user_id'        => $buyer->id,
            'label'          => 'Rumah',
            'recipient_name' => 'Dewi Lestari',
            'phone'          => '081234567890',
            'full_address'   => 'Jl. Raya Darmo No. 123',
            'city'           => 'Surabaya',
            'province'       => 'Jawa Timur',
            'is_default'     => true,
        ]);

        // ── DRIVER ────────────────────────────────────────────────────────
        $driver = User::create([
            'name'     => 'Eko Prasetyo',
            'username' => 'eko_driver',
            'email'    => 'driver@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $driver->id, 'role' => 'driver']);

        // ── MULTI-ROLE USER ────────────────────────────────────────────────
        $multi = User::create([
            'name'     => 'Rina Multi',
            'username' => 'rina_multi',
            'email'    => 'multi@seapedia.id',
            'password' => Hash::make('password'),
        ]);
        UserRole::create(['user_id' => $multi->id, 'role' => 'buyer']);
        UserRole::create(['user_id' => $multi->id, 'role' => 'seller']);
        UserRole::create(['user_id' => $multi->id, 'role' => 'driver']);
        Wallet::create(['user_id' => $multi->id, 'balance' => 2000000]);

        // ── VOUCHERS & PROMOS ──────────────────────────────────────────────
        Voucher::create([
            'code'           => 'COMPFEST10',
            'discount_type'  => 'percentage',
            'discount_value' => 10,
            'max_usage'      => 100,
            'used_count'     => 0,
            'expires_at'     => Carbon::now()->addMonths(3),
        ]);
        Voucher::create([
            'code'           => 'HEMAT50K',
            'discount_type'  => 'fixed',
            'discount_value' => 50000,
            'max_usage'      => 50,
            'used_count'     => 0,
            'expires_at'     => Carbon::now()->addMonths(1),
        ]);
        Promo::create([
            'code'           => 'SEAPEDIA18',
            'discount_type'  => 'percentage',
            'discount_value' => 18,
            'expires_at'     => Carbon::now()->addMonths(6),
        ]);
        Promo::create([
            'code'           => 'GRATIS20K',
            'discount_type'  => 'fixed',
            'discount_value' => 20000,
            'expires_at'     => Carbon::now()->addMonths(2),
        ]);

        // ── APP REVIEWS ────────────────────────────────────────────────────
        $reviews = [
            ['reviewer_name'=>'Ahmad Fauzi','rating'=>5,'comment'=>'Marketplace terbaik! Tampilan bersih dan mudah digunakan.'],
            ['reviewer_name'=>'Lia Permata','rating'=>4,'comment'=>'Proses checkout cepat, pengiriman juga on time.'],
            ['reviewer_name'=>'Roni Saputra','rating'=>5,'comment'=>'Bisa jadi buyer sekaligus seller di satu akun, keren!'],
            ['reviewer_name'=>'Maya Indah','rating'=>4,'comment'=>'Fitur voucher sangat membantu hemat belanja.'],
            ['reviewer_name'=>'Dodi Pratama','rating'=>5,'comment'=>'Driver responsif dan pengiriman aman. Recommended!'],
        ];
        foreach ($reviews as $r) {
            \App\Models\AppReview::create($r);
        }
    }
}
