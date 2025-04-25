<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class CartController extends Controller {

    public function index() {
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }
    
        $userId = Auth::id();
        $carts = Cart::where('userId', $userId)->get();
    
        $totalSum = 0; 
        $images = []; 
    
        if ($carts->isEmpty()) {
            return view('cart', compact('carts', 'totalSum', 'images'));
        }
    
        foreach ($carts as $cart) {
            $product = Product::find($cart->productId);
            if ($product) {
                $totalSum += $product->price;
                $images[] = $product->images;
            }
        }
    
        return view('cart', compact('carts', 'totalSum', 'images'));
    }

    public function addToCart($productId)
    {
        $userId = Auth::id();

        if (!Cart::where('userId', $userId)->where('productId', $productId)->exists()) {
            Cart::create([
                'userId' => $userId,
                'productId' => $productId,
            ]);
        }

        return redirect()->back();
    }

    public function checkout() {
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }
    
        $userId = Auth::id();
        $carts = Cart::where('userId', $userId)->get();
    
        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Корзина пуста.');
        }
    
        $totalSum = 0;
        $productId = []; 
    
        foreach ($carts as $cart) {
            $product = Product::find($cart->productId);
            if ($product) {
                $totalSum += $product->price;
                $productId[] = $product->id;
            }
        }
    
        $preOrder = PreOrder::create([
            'userId' => $userId,
            'productId' => implode(',', $productId), 
            'totalSum' => $totalSum,
            'status' => 'Ожидание подтверждения',
        ]);
    
        Cart::where('userId', $userId)->delete();
    
        return redirect()->route('order.confirmation', ['preOrderId' => $preOrder->id]);
    }

    public function confirmation($preOrderId) {
        $preOrder = PreOrder::findOrFail($preOrderId); 
        return view('confirmation', compact('preOrder')); 
    }

    public function removeFromCart($productId)
    {
        $userId = Auth::id();

        $cartItem = Cart::where('userId', $userId)->where('productId', $productId)->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back();
    }

    public function downloadInvoice($preOrderId) {
        $preOrder = PreOrder::findOrFail($preOrderId);
    
        $pdf = FacadePdf::loadView('invoice', compact('preOrder'))
            ->setOptions(['defaultFont' => 'DejaVu Sans']); 
        return $pdf->download('invoice_' . $preOrder->id . '.pdf');
    }
}
