<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function user()         { return $this->belongsTo(User::class); }
    public function transactions() { return $this->hasMany(WalletTransaction::class); }

    public function topUp(float $amount, string $desc = 'Top-up saldo'): void
    {
        $this->increment('balance', $amount);
        $this->transactions()->create(['type' => 'topup', 'amount' => $amount, 'description' => $desc]);
    }

    public function deduct(float $amount, string $desc = 'Pembayaran pesanan'): void
    {
        if ($this->balance < $amount) throw new \Exception('Saldo tidak cukup.');
        $this->decrement('balance', $amount);
        $this->transactions()->create(['type' => 'payment', 'amount' => $amount, 'description' => $desc]);
    }

    public function refund(float $amount, string $desc = 'Refund pesanan'): void
    {
        $this->increment('balance', $amount);
        $this->transactions()->create(['type' => 'refund', 'amount' => $amount, 'description' => $desc]);
    }
}
