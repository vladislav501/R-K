<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index()
{
    $pickupPointId = session('pickup_point_id');

    $favorites = auth()->user()->favorites()
        ->with(['brand', 'category', 'colors', 'sizes', 'pickupPoints'])
        ->get();

    $favorites->transform(function ($product) use ($pickupPointId) {
        if ($pickupPointId) {
            $pivot = $product->pickupPoints()->where('pickup_point_id', $pickupPointId)->first()?->pivot;
            $product->available_quantity = $pivot?->quantity ?? 0;
            $product->available_status = $product->available_quantity > 0 ? 'В наличии' : 'Нет в наличии';
        } else {
            $product->available_quantity = null;
            $product->available_status = 'В наличии';
        }
        return $product;
    });

    return view('favoritesIndex', compact('favorites'));
}


    public function add(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->attach($product->id);
            return redirect()->back()->with('success', 'Товар добавлен в избранное.');
        }
        return redirect()->back()->with('error', 'Товар уже в избранном.');
    }

    public function destroy($favoriteId)
    {
        $user = Auth::user();
        $user->favorites()->detach($favoriteId);
        return redirect()->back()->with('success', 'Товар удален из избранного.');
    }
}