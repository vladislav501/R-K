<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Store;
use App\Models\Supply;
use App\Models\SupplyItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            ->where('status', 'sent_to_store')
            ->with(['items.product', 'items.color', 'items.size'])
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
            ->with(['items.product', 'items.color', 'items.size'])
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
            'items' => 'required|array',
            'items.*.id' => 'required|exists:supply_items,id',
            'items.*.is_fully_received' => 'required|boolean',
            'items.*.received_quantity' => 'required_if:items.*.is_fully_received,0|integer|min:0',
        ]);

        return DB::transaction(function () use ($supply, $validated, $store) {
            foreach ($validated['items'] as $itemData) {
                $supplyItem = SupplyItem::findOrFail($itemData['id']);
                $supplyItem->update([
                    'is_fully_received' => $itemData['is_fully_received'],
                    'received_quantity' => $itemData['is_fully_received'] ? $supplyItem->quantity : ($itemData['received_quantity'] ?? 0),
                ]);

                if ($itemData['is_fully_received']) {
                    $store->products()->syncWithoutDetaching([
                        $supplyItem->product_id => [
                            'quantity' => DB::raw('quantity + ' . $supplyItem->quantity),
                        ],
                    ]);

                    ProductColorSize::where('product_id', $supplyItem->product_id)
                        ->where('color_id', $supplyItem->color_id)
                        ->where('size_id', $supplyItem->size_id)
                        ->increment('quantity', $supplyItem->quantity);
                }
            }

            $status = $supply->isFullyReceived() ? 'received' : 'partially_received';
            $supply->update(['status' => $status]);

        return redirect('/manager/supplies')->with('success', 'Поставка подтверждена.');        });
    }

    public function downloadSupplyList(Supply $supply)
    {
        $store = Store::find(Auth::user()->store_id);
        if (!$store || $supply->store_id !== $store->id) {
            abort(403, 'Доступ запрещён.');
        }

        try {
            $pdf = Pdf::loadView('supplyList', ['supply' => $supply->load('items.product', 'items.color', 'items.size'), 'store' => $store])
                ->setOptions(['defaultFont' => 'DejaVu Sans']);
            return $pdf->download('supply_' . $supply->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Ошибка генерации PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось сгенерировать PDF.');
        }
    }
}