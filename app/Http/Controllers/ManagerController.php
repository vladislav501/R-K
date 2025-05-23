<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:is-manager');
    }

    public function dashboard()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $activeOrders = Order::where('store_id', $store->id)
            ->whereIn('status', ['assembling', 'assembled', 'ready_for_pickup', 'handed_to_courier'])
            ->count();

        $completedOrders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->count();

        $pendingSupplies = Supply::where('store_id', $store->id)
            ->where('status', 'pending')
            ->count();

        return view('managerDashboard', compact('store', 'activeOrders', 'completedOrders', 'pendingSupplies'));
    }

    public function index()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $orders = Order::where('store_id', $store->id)
            ->whereIn('status', ['assembling', 'assembled', 'ready_for_pickup', 'handed_to_courier'])
            ->with('user')
            ->get();

        return view('managerOrdersIndex', compact('orders', 'store'));
    }

    public function ordersArchive()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $orders = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->with('user')
            ->get();

        return view('managerOrdersArchive', compact('orders', 'store'));
    }

    public function orderShow(Order $cart)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || $cart->store_id !== $store->id) {
            abort(403, 'Доступ запрещён.');
        }

        return view('managerOrdersShow', ['order' => $cart]);
    }

    public function updateOrderStatus(Request $request, Order $cart)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || $cart->store_id !== $store->id) {
            abort(403, 'Доступ запрещён.');
        }

        $request->validate([
            'status' => 'required|in:assembled,ready_for_pickup,handed_to_courier',
        ]);

        Log::info('Updating order status', ['order_id' => $cart->id, 'new_status' => $request->status]);

        $cart->update(['status' => $request->status]);

        Log::info('Order status updated', ['order_id' => $cart->id, 'status' => $request->status]);

        return redirect()->route('manager.orders.index')->with('success', 'Статус заказа обновлён.');
    }

    public function productsIndex()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $products = $store->products()->withPivot('quantity')->get();

        return view('managerProductsIndex', compact('products', 'store'));
    }

    public function updateProductAvailability(Request $request, Product $product)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || !$store->products()->where('product_id', $product->id)->exists()) {
            abort(403, 'Доступ запрещён.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        $store->products()->updateExistingPivot($product->id, [
            'quantity' => $validated['quantity'],
        ]);

        $product->update(['is_available' => $validated['is_available']]);

        return redirect()->route('manager.products.index')->with('success', 'Товар обновлён.');
    }

    public function suppliesIndex()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $supplies = Supply::where('store_id', $store->id)
            ->where('status', 'pending')
            ->with('product')
            ->get();

        return view('managerSuppliesIndex', compact('supplies', 'store'));
    }

    public function suppliesArchive()
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store) {
            abort(403, 'У вас нет назначенного пункта выдачи.');
        }

        $supplies = Supply::where('store_id', $store->id)
            ->whereIn('status', ['received', 'partially_received'])
            ->with('product')
            ->get();

        return view('managerSuppliesArchive', compact('supplies', 'store'));
    }

    public function showSupplyCheck(Supply $supply)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || $supply->store_id !== $store->id) {
            abort(403, 'Доступ запрещён.');
        }

        return view('managerSupplyCheck', compact('supply'));
    }

    public function confirmSupply(Request $request, Supply $supply)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || $supply->store_id !== $store->id) {
            abort(403, 'Доступ запрещён.');
        }

        $validated = $request->validate([
            'is_fully_received' => 'required|boolean',
        ]);

        return \DB::transaction(function () use ($supply, $validated, $store) {
            $status = $validated['is_fully_received'] ? 'received' : 'partially_received';
            $supply->update(['status' => $status]);

            if ($validated['is_fully_received']) {
                $store->products()->syncWithoutDetaching([
                    $supply->product_id => ['quantity' => \DB::raw('quantity + ' . $supply->quantity)],
                ]);
            }

            return redirect()->route('manager.supplies.index')->with('success', 'Поставка подтверждена.');
        });
    }
}