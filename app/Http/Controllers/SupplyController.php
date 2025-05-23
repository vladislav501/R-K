<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class SupplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Gate::allows('is-admin')) {
                abort(403, 'Доступ запрещён.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        try {
            $supplies = Supply::with(['store', 'product'])->get();
            return view('adminSuppliesIndex', compact('supplies'));
        } catch (\Exception $e) {
            Log::error('Ошибка в index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось загрузить список поставок.');
        }
    }

    public function create()
    {
        try {
            $products = Product::all(); // Оптимизировано, так как with('store') не используется
            return view('adminSuppliesCreate', compact('products'));
        } catch (\Exception $e) {
            Log::error('Ошибка в create: ' . $e->getMessage());
            return redirect()->route('admin.supplies.index')->with('error', 'Не удалось загрузить форму создания.');
        }
    }

    public function storeStep1(Request $request)
    {
        try {
            $validated = $request->validate([
                'products' => 'required|array',
                'products.*' => 'exists:products,id',
            ]);

            $selectedProducts = Product::whereIn('id', $validated['products'])->get();
            return view('adminSuppliesStoreStep2', compact('selectedProducts'));
        } catch (\Exception $e) {
            Log::error('Ошибка в storeStep1: ' . $e->getMessage());
            return redirect()->route('admin.supplies.create')->with('error', 'Не удалось обработать первый шаг.');
        }
    }

    public function storeStep2(Request $request)
    {
        try {
            $validated = $request->validate([
                'store_id' => 'required|exists:stores,id',
                'products' => 'required|array',
                'products.*' => 'exists:products,id',
                'quantities' => 'required|array',
                'quantities.*' => 'required|integer|min:1',
            ]);

            $storeId = $validated['store_id'];
            $productIds = $validated['products'];
            $quantities = $validated['quantities'];

            if (count($productIds) !== count($quantities)) {
                return redirect()->back()->with('error', 'Количество товаров и их количеств не совпадает.');
            }

            return DB::transaction(function () use ($storeId, $productIds, $quantities) {
                foreach ($productIds as $index => $productId) {
                    $quantity = $quantities[$index] ?? 1;
                    Supply::updateOrCreate(
                        [
                            'store_id' => $storeId,
                            'product_id' => $productId,
                        ],
                        [
                            'quantity' => $quantity,
                            'status' => 'sent_to_store',
                        ]
                    );
                }
                return redirect()->route('admin.supplies.index')->with('success', 'Поставка отправлена.');
            });
        } catch (\Exception $e) {
            Log::error('Ошибка в storeStep2: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось завершить создание поставки.');
        }
    }

    public function archive()
    {
        try {
            $supplies = Supply::with(['store', 'product'])
                ->whereIn('status', ['confirmed', 'partially_received'])
                ->get();
            return view('adminSuppliesArchive', compact('supplies'));
        } catch (\Exception $e) {
            Log::error('Ошибка в archive: ' . $e->getMessage());
            return redirect()->route('admin.supplies.index')->with('error', 'Не удалось загрузить архив поставок.');
        }
    }
}