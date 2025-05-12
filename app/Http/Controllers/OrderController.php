<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PreOrder;
use Illuminate\Http\Request;

class OrderController extends Controller {

    public function index() {
        $preOrders = PreOrder::all(); 
        return view('preOrders', compact('preOrders'));
    }

    public function updateStatus(Request $request, $id) {
        $preOrder = PreOrder::findOrFail($id);
        $preOrder->status = $request->input('status');
        $preOrder->save();

        if ($preOrder->status === 'Подтвержден') {
            Order::create([
                'userId' => $preOrder->userId,
                'products' => $preOrder->products,
                'totalSum' => $preOrder->totalSum,
                'status' => 'Подтвержден',
            ]);
            $preOrder->delete();
        }

        return redirect()->route('admin.preorders.index');
    }

    public function completedOrders() {
        $orders = Order::all(); 
        return view('orders', compact('orders'));
    }
}