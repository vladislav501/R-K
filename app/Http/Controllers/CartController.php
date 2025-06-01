<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $carts = $user->carts()->with(['product', 'size', 'color'])->whereNull('order_id')->get();
        $totalSum = $carts->sum('order_amount');
        return view('cartIndex', compact('carts', 'totalSum'));
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
        ]);

        if ($validated['quantity'] === 0) {
            return redirect()->route('cart.index')->with('info', 'Количество равно 0, товар не добавлен.');
        }

        return DB::transaction(function () use ($request, $product, $validated) {
            $colorSize = ProductColorSize::where('product_id', $product->id)
                ->where('color_id', $validated['color_id'])
                ->where('size_id', $validated['size_id'])
                ->first();

            if (!$colorSize || $colorSize->quantity < $validated['quantity']) {
                return redirect()->back()->with('error', 'Недостаточно товара в наличии.');
            }

            $existingCart = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('size_id', $validated['size_id'])
                ->where('color_id', $validated['color_id'])
                ->whereNull('order_id')
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $validated['quantity'];
                if ($colorSize->quantity < $newQuantity) {
                    return redirect()->back()->with('error', 'Недостаточно товара в наличии.');
                }
                $existingCart->update([
                    'quantity' => $newQuantity,
                    'order_amount' => $product->price * $newQuantity,
                ]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $validated['quantity'],
                    'size_id' => $validated['size_id'],
                    'color_id' => $validated['color_id'],
                    'order_amount' => $product->price * $validated['quantity'],
                ]);
            }

            $colorSize->update(['quantity' => $colorSize->quantity - $validated['quantity']]);
            $product->update(['is_in_cart' => true]);

            return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину.');
        });
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещён.');
        }

        $action = $request->input('action');
        $validated = $request->validate([
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
        ]);

        $colorSize = ProductColorSize::where('product_id', $cart->product_id)
            ->where('color_id', $validated['color_id'])
            ->where('size_id', $validated['size_id'])
            ->first();

        if (!$colorSize) {
            return redirect()->back()->with('error', 'Недостаточно товара в наличии.');
        }

        $currentQuantity = $cart->quantity;
        $newQuantity = $currentQuantity;

        if ($action === 'increment') {
            $newQuantity = $currentQuantity + 1;
            if ($colorSize->quantity < $newQuantity - $currentQuantity) {
                return redirect()->back()->with('error', 'Недостаточно товара в наличии.');
            }
        } elseif ($action === 'decrement' && $currentQuantity > 1) {
            $newQuantity = $currentQuantity - 1;
        } elseif ($action === 'decrement' && $currentQuantity == 1) {
            $cart->delete();
            $remainingCarts = Cart::where('product_id', $cart->product_id)->count();
            if ($remainingCarts === 0) {
                $cart->product->update(['is_in_cart' => false]);
            }
            return redirect()->route('cart.index')->with('success', 'Товар удалён из корзины.');
        }

        return DB::transaction(function () use ($cart, $colorSize, $newQuantity, $currentQuantity) {
            $cart->update([
                'quantity' => $newQuantity,
                'order_amount' => $cart->product->price * $newQuantity,
            ]);
            $colorSize->update([
                'quantity' => $colorSize->quantity + $currentQuantity - $newQuantity,
            ]);
            return redirect()->route('cart.index')->with('success', 'Корзина обновлена.');
        });
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещён.');
        }

        $product = $cart->product;
        $colorSize = ProductColorSize::where('product_id', $cart->product_id)
            ->where('color_id', $cart->color_id)
            ->where('size_id', $cart->size_id)
            ->first();

        return DB::transaction(function () use ($cart, $colorSize, $product) {
            if ($colorSize) {
                $colorSize->update(['quantity' => $colorSize->quantity + $cart->quantity]);
            }

            $cart->delete();
            $remainingCarts = Cart::where('product_id', $product->id)->count();
            if ($remainingCarts === 0) {
                $product->update(['is_in_cart' => false]);
            }

            return redirect()->route('cart.index')->with('success', 'Товар удалён из корзины.');
        });
    }

    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stores = Store::all();
        $carts = $user->carts()->with(['product', 'size', 'color'])->whereNull('order_id')->get();
        $totalSum = $carts->sum('order_amount');
        return view('cartCheckout', compact('user', 'stores', 'carts', 'totalSum'));
    }

    public function placeOrder(Request $request)
    {
        Log::info('placeOrder method called', ['request' => $request->all()]);

        try {
            $validated = $request->validate([
                'delivery_method' => 'required|in:delivery,pickup',
                'delivery_address' => 'required_if:delivery_method,delivery|string|nullable',
                'store_id' => 'required_if:delivery_method,pickup|exists:stores,id',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            $user = Auth::user();
            if (!$user instanceof \App\Models\User) {
                Log::warning('User authentication failed');
                return redirect()->route('cart.index')->with('error', 'Ошибка авторизации. Пожалуйста, войдите снова.');
            }

            if ($validated['delivery_method'] === 'delivery' && !$user->delivery_address) {
                $user->update(['delivery_address' => $validated['delivery_address']]);
                Log::info('Delivery address updated for user', ['user_id' => $user->id]);
            }

            $carts = $user->carts()->with(['product', 'size', 'color'])->whereNull('order_id')->get();
            if ($carts->isEmpty()) {
                Log::warning('Cart is empty');
                return redirect()->route('cart.index')->with('error', 'Корзина пуста.');
            }

            Log::info('Before transaction', ['cart_count' => $carts->count()]);

            return DB::transaction(function () use ($validated, $user, $carts) {
                $store_id = $validated['delivery_method'] === 'pickup'
                    ? $validated['store_id']
                    : Store::whereHas('products', function ($query) use ($carts) {
                        $query->whereIn('products.id', $carts->pluck('product_id'));
                    })->first()->id ?? Store::first()->id;

                if (!$store_id) {
                    Log::error('No suitable store found for order');
                    throw new \Exception('Не удалось найти подходящий склад для заказа.');
                }

                $total = $carts->sum('order_amount');
                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $total,
                    'status' => 'assembling',
                    'delivery_method' => $validated['delivery_method'],
                    'delivery_address' => $validated['delivery_method'] === 'delivery' ? $validated['delivery_address'] : null,
                    'store_id' => $store_id,
                    'order_date' => now(),
                ]);

                foreach ($carts as $cart) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cart->product_id,
                        'quantity' => $cart->quantity,
                        'price' => $cart->product->price,
                        'size_id' => $cart->size_id,
                        'color_id' => $cart->color_id,
                    ]);
                    $cart->update(['order_id' => $order->id]);
                    $cart->product->update(['is_in_cart' => false]);
                }

                Log::info('Order created', ['order_id' => $order->id, 'items_count' => $carts->count()]);

                try {
                    $pdf = Pdf::loadView('cartReceipt', ['order' => $order, 'user' => $user])
                        ->setOptions(['defaultFont' => 'DejaVu Sans']);
                    $pdfPath = storage_path('app/public/receipts/receipt_' . $order->id . '_' . now()->timestamp . '.pdf');
                    $pdf->save($pdfPath);

                    Log::info('PDF generated successfully', ['pdf_path' => $pdfPath]);

                    return redirect()->route('cart.confirmation', ['cartId' => $order->id])
                        ->with('success', 'Заказ успешно оформлен. Чек доступен для скачивания.');
                } catch (\Exception $e) {
                    Log::error('PDF generation failed: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
                    return redirect()->route('cart.confirmation', ['cartId' => $order->id])
                        ->with('error', 'Заказ оформлен, но не удалось сгенерировать чек. Свяжитесь с поддержкой.');
                }
            });
        } catch (\Exception $e) {
            Log::error('Error in placeOrder: ' . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return redirect()->route('cart.index')->with('error', 'Не удалось оформить заказ. Попробуйте снова.');
        }
    }

    public function confirmation($orderId)
    {
        $order = Order::with(['items.product', 'items.size', 'items.color', 'user'])->findOrFail($orderId);
        return view('cartConfirmation', compact('order'));
    }

    public function downloadReceipt($orderId)
    {
        $order = Order::with(['items.product', 'items.size', 'items.color', 'user'])->findOrFail($orderId);
        $user = $order->user;
        $pdf = Pdf::loadView('cartReceipt', compact('order', 'user'))
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
        return $pdf->download('receipt_' . $orderId . '_' . now()->format('YmdHis') . '.pdf');
    }
}