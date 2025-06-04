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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $favorites = Auth::user()->favorites()->with(['brand', 'category', 'colors', 'sizes'])->get();
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