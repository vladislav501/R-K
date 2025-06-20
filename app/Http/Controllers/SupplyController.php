<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PickupPoint;
use App\Models\Supply;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $supplies = Supply::with(['pickupPoint', 'items.product', 'items.color', 'items.size'])->get();
        return view('adminSuppliesIndex', compact('supplies'));
    }

    public function create()
    {
        $products = Product::with(['colors', 'sizes'])->get();
        $pickupPoints = PickupPoint::all();
        Log::info('Supply create view loaded', [
            'pickup_points_count' => $pickupPoints->count(),
            'products_count' => $products->count(),
        ]);
        return view('adminSuppliesCreate', compact('products', 'pickupPoints'));
    }

    public function store(Request $request)
    {
        Log::info('Supply creation request', ['input' => $request->all()]);

        try {
            $validated = $request->validate([
                'pickup_point_id' => 'required|exists:pickup_points,id',
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.items' => 'required|array',
                'products.*.items.*.color_id' => 'required|exists:colors,id',
                'products.*.items.*.size_id' => 'required|exists:sizes,id',
                'products.*.items.*.quantity' => 'nullable|integer|min:0',
            ]);

            $filteredProducts = array_filter($validated['products'], function ($product) {
                $validItems = array_filter($product['items'], function ($item) {
                    return isset($item['quantity']) && $item['quantity'] > 0;
                });
                return !empty($validItems);
            });

            if (empty($filteredProducts)) {
                Log::warning('No valid products with quantity > 0', ['products' => $validated['products']]);
                return redirect()->back()->with('error', 'Выберите хотя бы один товар с количеством больше 0.')->withInput();
            }

            return DB::transaction(function () use ($filteredProducts, $validated) {
                $supply = Supply::create([
                    'pickup_point_id' => $validated['pickup_point_id'],
                    'status' => 'sent_to_store',
                ]);

                foreach ($filteredProducts as $product) {
                    foreach ($product['items'] as $item) {
                        if ($item['quantity'] > 0) {
                            SupplyItem::create([
                                'supply_id' => $supply->id,
                                'product_id' => $product['id'],
                                'color_id' => $item['color_id'],
                                'size_id' => $item['size_id'],
                                'quantity' => $item['quantity'],
                                'is_fully_received' => false,
                                'received_quantity' => 0,
                            ]);
                        }
                    }
                }

                Log::info('Supply created successfully', ['supply_id' => $supply->id]);
                return redirect()->route('admin.supplies.index')->with('success', 'Поставка создана.');
            });
        } catch (ValidationException $e) {
            Log::error('Validation failed for supply creation', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to create supply', [
                'error' => $e->getMessage(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Ошибка при создании поставки: ' . $e->getMessage())->withInput();
        }
    }

    public function archive()
    {
        $supplies = Supply::with(['pickupPoint', 'items.product', 'items.color', 'items.size'])
            ->whereIn('status', ['received', 'partially_received'])
            ->get();
        return view('adminSuppliesArchive', compact('supplies'));
    }

    public function downloadSupplyList(Supply $supply)
    {
        $pdf = Pdf::loadView('supplyList', ['supply' => $supply->load('items.product', 'items.color', 'items.size'), 'pickupPoint' => $supply->pickupPoint])
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('supply_' . $supply->id . '.pdf');
    }
}