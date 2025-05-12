<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Product;
use Illuminate\Http\Request;
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
                $totalSum += $product->price * $cart->quantity;
                $images[$cart->productId] = $product->images;
            }
        }
    
        return view('cart', compact('carts', 'totalSum', 'images'));
    }

    public function addToCart($productId) {
        $userId = Auth::id();
    
        $cart = Cart::where('userId', $userId)->where('productId', $productId)->first();
    
        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            Cart::create([
                'userId' => $userId,
                'productId' => $productId,
                'quantity' => 1,
            ]);
        }
    
        return redirect()->back();
    }

    public function updateQuantity(Request $request, $productId) {
        $userId = Auth::id();
        $cart = Cart::where('userId', $userId)->where('productId', $productId)->first();
    
        if ($cart) {
            $action = $request->input('action');
            if ($action === 'increment') {
                $cart->quantity += 1;
                $cart->save();
            } elseif ($action === 'decrement' && $cart->quantity > 1) {
                $cart->quantity -= 1;
                $cart->save();
            } elseif ($action === 'decrement' && $cart->quantity == 1) {
                $cart->delete();
            }
        }
    
        return redirect()->route('cart.index');
    }

    public function checkout(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login.index');
        }
    
        $rules = [
            'receivingMethod' => 'required|in:delivery,pickup',
        ];

        if ($request->input('receivingMethod') === 'delivery') {
            $rules['deliveryAddress'] = 'required|string|max:255';
        } elseif ($request->input('receivingMethod') === 'pickup') {
            $rules['pickupPoint'] = [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $validPickupPoints = [
                        'ул. Ленина, 10',
                        'пр. Победителей, 25',
                        'ул. Октябрьская, 50',
                        'ул. Советская, 15',
                        'пр. Независимости, 100'
                    ];
                    if (!in_array($value, $validPickupPoints)) {
                        $fail('Выберите действительный пункт самовывоза.');
                    }
                },
            ];
        }

        $validated = $request->validate($rules);
    
        $userId = Auth::id();
        $carts = Cart::where('userId', $userId)->get();
    
        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Корзина пуста.');
        }
    
        $totalSum = 0;
        $products = [];
    
        foreach ($carts as $cart) {
            $product = Product::find($cart->productId);
            if ($product) {
                $totalSum += $product->price * $cart->quantity;
                $products[] = [
                    'productId' => $product->id,
                    'quantity' => $cart->quantity,
                ];
            }
        }

        $address = $validated['receivingMethod'] === 'delivery' ? $validated['deliveryAddress'] : $validated['pickupPoint'];
    
        $preOrder = PreOrder::create([
            'userId' => $userId,
            'products' => json_encode($products),
            'receivingMethod' => $validated['receivingMethod'] == 'delivery' ? 'Доставка' : 'Самовывоз',
            'deliveryAddress' => $address,
            'totalSum' => $totalSum,
            'status' => 'Ожидание подтверждения',
        ]);
    
        Order::create([
            'userId' => $userId,
            'products' => json_encode($products),
            'receivingMethod' => $validated['receivingMethod'] == 'delivery' ? 'Доставка' : 'Самовывоз',
            'deliveryAddress' => $address,
            'totalSum' => $totalSum,
            'status' => 'Подтвержден',
        ]);
    
        Cart::where('userId', $userId)->delete();
    
        return redirect()->route('order.confirmation', ['preOrderId' => $preOrder->id]);
    }

    public function confirmation($preOrderId) {
        $preOrder = PreOrder::findOrFail($preOrderId); 
        return view('confirmation', compact('preOrder')); 
    }

    public function removeFromCart($productId) {
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