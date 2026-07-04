<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'buyer_id','seller_id','store_id','address_id',
        'delivery_method','subtotal','delivery_fee','discount_amount',
        'tax_amount','total_amount','status','voucher_code',
        'promo_code','driver_id','refunded','overdue_at'
    ];
    protected $casts = ['overdue_at' => 'datetime', 'refunded' => 'boolean'];

    public function buyer()           { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller()          { return $this->belongsTo(User::class, 'seller_id'); }
    public function driver()          { return $this->belongsTo(User::class, 'driver_id'); }
    public function store()           { return $this->belongsTo(Store::class); }
    public function address()         { return $this->belongsTo(Address::class); }
    public function items()           { return $this->hasMany(OrderItem::class); }
    public function statusHistories() { return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at','desc'); }

    public function updateStatus(string $status, string $note = ''): void
    {
        $this->update(['status' => $status]);
        $this->statusHistories()->create(['status' => $status, 'note' => $note]);
    }
}
