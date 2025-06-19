<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\Supply;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        try {
            $supplies = Supply::with(['store', 'items.product', 'items.color', 'items.size'])->get();
            return view('adminSuppliesIndex', compact('supplies'));
        } catch (\Exception $e) {
            Log::error('Ошибка в index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось загрузить список поставок.');
        }
    }

    public function create()
    {
        try {
            $products = Product::with(['colors', 'sizes'])->get();
            $stores = Store::all();
            return view('adminSuppliesCreate', compact('products', 'stores'));
        } catch (\Exception $e) {
            Log::error('Ошибка в create: ' . $e->getMessage());
            return redirect()->route('admin.supplies.index')->with('error', 'Не удалось загрузить форму создания.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Received supply creation request:', $request->all());

            $validated = $request->validate([
                'store_id' => 'required|exists:stores,id',
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.items' => 'required|array',
                'products.*.items.*.color_id' => 'required|exists:colors,id',
                'products.*.items.*.size_id' => 'required|exists:sizes,id',
                'products.*.items.*.quantity' => 'required|integer|min:1',
            ]);

            // Filter out products with no valid items (all quantities <= 0)
            $filteredProducts = array_filter($validated['products'], function ($product) {
                $validItems = array_filter($product['items'], function ($item) {
                    return isset($item['quantity']) && $item['quantity'] > 0;
                });
                return !empty($validItems);
            });

            if (empty($filteredProducts)) {
                Log::warning('No valid products with quantities > 0 found.');
                return redirect()->back()->with('error', 'Выберите хотя бы один товар с количеством больше 0.')->withInput();
            }

            Log::info('Filtered products:', $filteredProducts);

            return DB::transaction(function () use ($filteredProducts, $validated) {
                $supply = Supply::create([
                    'store_id' => $validated['store_id'],
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

                Log::info('Supply created successfully:', ['supply_id' => $supply->id]);
                return redirect()->route('admin.supplies.index')->with('success', 'Поставка создана.');
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = collect($e->errors())->flatten()->implode(', ');
            Log::error('Validation error in store: ' . $errorMessages);
            return redirect()->back()->with('error', 'Не удалось создать поставку: ' . $errorMessages)->withInput();
        } catch (\Exception $e) {
            Log::error('General error in store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось создать поставку: ' . $e->getMessage())->withInput();
        }
    }

    public function archive()
    {
        try {
            $supplies = Supply::with(['store', 'items.product', 'items.color', 'items.size'])
                ->whereIn('status', ['received', 'partially_received'])
                ->get();
            return view('adminSuppliesArchive', compact('supplies'));
        } catch (\Exception $e) {
            Log::error('Ошибка в archive: ' . $e->getMessage());
            return redirect()->route('admin.supplies.index')->with('error', 'Не удалось загрузить архив поставок.');
        }
    }

    public function downloadSupplyList(Supply $supply)
    {
        try {
            $pdf = Pdf::loadView('supplyList', ['supply' => $supply->load('items.product', 'items.color', 'items.size'), 'store' => $supply->store])
                ->setOptions(['defaultFont' => 'DejaVu Sans']);
            return $pdf->download('supply_' . $supply->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Ошибка генерации PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось сгенерировать PDF.');
        }
    }
}