<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['store_id', 'name', 'description', 'price', 'stock', 'image_url'];
    public function store()     { return $this->belongsTo(Store::class); }
    public function cartItems() { return $this->hasMany(CartItem::class); }
}
