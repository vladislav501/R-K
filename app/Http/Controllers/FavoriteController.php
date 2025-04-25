<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller {
    
    public function index() {
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }
    
        $userId = Auth::id();
        $favorites = Favorite::where('userId', $userId)->get(); 
    
        $totalPrice = 0;
        $products = []; 
    
        if ($favorites->isEmpty()) {
            return view('favorite', compact('favorites', 'products'));
        }
    
        foreach ($favorites as $favorite) {
            $product = Product::find($favorite->productId);
            if ($product) {
                $totalPrice += $product->price;
                $products[] = $product; 
            }
        }
    
        return view('favorite', compact('favorites', 'totalPrice', 'products'));
    }

    public function addToFavorites($productId)
    {
        $userId = Auth::id();

        if (!Favorite::where('userId', $userId)->where('productId', $productId)->exists()) {
            Favorite::create([
                'userId' => $userId,
                'productId' => $productId,
            ]);
        }

        return redirect()->back();
    }

    public function showFavorites()
    {
        $userId = Auth::id();
        $favorites = Favorite::with('product')->where('userId', $userId)->get();

        return view('favorite', compact('favorites'));
    }

    public function removeFromFavorites($productId)
{
    $userId = Auth::id();

    $favorite = Favorite::where('userId', $userId)->where('productId', $productId)->first();

    if ($favorite) {
        $favorite->delete();
    }

    return redirect()->back();
}
}
