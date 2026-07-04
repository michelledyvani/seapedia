<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    protected $fillable = ['code','discount_type','discount_value','expires_at'];
    protected $casts    = ['expires_at' => 'datetime'];

    public function isValid(): bool { return Carbon::now()->lt($this->expires_at); }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            return round($subtotal * ($this->discount_value / 100), 2);
        }
        return min((float)$this->discount_value, $subtotal);
    }
}
