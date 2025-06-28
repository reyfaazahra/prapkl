<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $fillable = ['status', 'orders_code', 'user_id', 'total_price'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('qty', 'price')
            ->withTimestamps;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}