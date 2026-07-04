<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password'];
    protected $hidden   = ['password', 'remember_token'];

    public function userRoles()    { return $this->hasMany(UserRole::class); }
    
    public function store()        { return $this->hasOne(Store::class); }
    //public function wallet()       { return $this->hasOne(Wallet::class); }
    //public function addresses()    { return $this->hasMany(Address::class); }
    
    public function getRoleNames(): array
    {
        return $this->userRoles()->pluck('role')->toArray();
    }

    public function hasRole(string $role): bool
    {
        return $this->userRoles()->where('role', $role)->exists();
    }
    
    //public function getOrCreateWallet(): Wallet
    //{
        //return $this->wallet ?? Wallet::create(['user_id' => $this->id, 'balance' => 0]);
    //}
}
