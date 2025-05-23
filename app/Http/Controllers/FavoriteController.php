<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
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
        $favorites = Auth::user()->favorites()->with('product')->get();
        return view('favoritesIndex', compact('favorites'));
    }

    public function add(Product $product)
    {
        $existingFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingFavorite) {
            return redirect()->route('favorites.index')->with('info', 'Товар уже в избранном.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return redirect()->route('favorites.index')->with('success', 'Товар добавлен в избранное.');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещён.');
        }

        $favorite->delete();
        return redirect()->route('favorites.index')->with('success', 'Товар удалён из избранного.');
    }
}