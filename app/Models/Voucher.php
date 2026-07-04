<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = ['code','discount_type','discount_value','max_usage','used_count','expires_at'];
    protected $casts    = ['expires_at' => 'datetime'];

    public function isValid(): bool
    {
        return $this->used_count < $this->max_usage && Carbon::now()->lt($this->expires_at);
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            return round($subtotal * ($this->discount_value / 100), 2);
        }
        return min((float)$this->discount_value, $subtotal);
    }

    public function markUsed(): void { $this->increment('used_count'); }
}
