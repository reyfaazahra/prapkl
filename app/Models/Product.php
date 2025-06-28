<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $fillable = ['name', 'slug', 'description', 'price', 'stock', 'image', 'category_id'];

    //relasi product ke category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    //relasi many to many
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('qty', 'price')
            ->withTimestamps;
    }
}