<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Auth;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view){
            $cartItems = [];

            if (Auth::check()) {
                $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())->get();
            }

            //pastikan ini collection, bukan array
            $view->with('cartItems', collect($cartItems));
        });
    }
}
