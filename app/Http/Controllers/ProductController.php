<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ClothingType;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Size;
use App\Models\Store;
use App\Models\Supply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $query = Product::query()->with(['brand', 'category', 'collection', 'clothingType', 'colors', 'sizes']);
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->has('collection_id')) {
            $query->where('collection_id', $request->collection_id);
        }
        if ($request->has('color_id')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('colors.id', $request->color_id);
            });
        }
        if ($request->has('size_id')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->where('sizes.id', $request->size_id);
            });
        }

        $products = $query->get();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::all();
        $collections = Collection::all();
        $colors = Color::all();
        $sizes = Size::all();

        if ($request->is('admin/*')) {
            return view('adminProductsIndex', compact('products', 'categories', 'brands', 'collections', 'colors', 'sizes'));
        }

        return view('productsIndex', compact('products', 'categories', 'brands', 'collections', 'colors', 'sizes'));
    }

    public function category(Request $request, Category $category)
    {
        $query = Product::query()->with(['brand', 'category', 'collection', 'clothingType', 'colors', 'sizes'])
            ->where('category_id', $category->id);
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->has('collection_id')) {
            $query->where('collection_id', $request->collection_id);
        }
        if ($request->has('color_id')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('colors.id', $request->color_id);
            });
        }
        if ($request->has('size_id')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->where('sizes.id', $request->size_id);
            });
        }
        $products = $query->get();
        $brands = Brand::all();
        $collections = Collection::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('productsCategory', compact('products', 'category', 'brands', 'collections', 'colors', 'sizes'));
    }

    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'collection', 'clothingType', 'colors', 'sizes']);
        return view('productsShow', compact('product'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::where('is_active', true)->get();
        $collections = Collection::all();
        $clothingTypes = ClothingType::all();
        $colors = Color::all();
        $sizes = Size::all();
        $stores = Store::all();
        return view('adminProductsCreate', compact('brands', 'categories', 'collections', 'clothingTypes', 'colors', 'sizes', 'stores'));
    }

    public function store(Request $request)
    {
        Log::info('Attempting to store product', ['request_data' => $request->all()]);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'brand_id' => 'required|exists:brands,id',
                'category_id' => 'required|exists:categories,id',
                'collection_id' => 'nullable|exists:collections,id',
                'clothing_type_id' => 'required|exists:clothing_types,id',
                'is_available' => 'required|boolean',
                'colors' => 'required|array|min:1',
                'colors.*' => 'exists:colors,id',
                'sizes' => 'required|array|min:1',
                'sizes.*' => 'exists:sizes,id',
                'color_size_quantities' => 'required|array',
                'color_size_quantities.*.*' => 'nullable|integer|min:0',
                'stores' => 'required|array|min:1',
                'stores.*' => 'exists:stores,id',
                'store_quantities' => 'required|array',
                'store_quantities.*' => 'nullable|integer|min:0',
            ]);

            if (count($validated['stores']) !== count(array_filter($validated['store_quantities'], fn($q) => !is_null($q)))) {
                throw new \Exception('The number of stores and store quantities do not match.');
            }

            return DB::transaction(function () use ($validated, $request) {
                $product = Product::create([
                    'name' => $validated['name'],
                    'price' => $validated['price'],
                    'brand_id' => $validated['brand_id'],
                    'category_id' => $validated['category_id'],
                    'collection_id' => $validated['collection_id'],
                    'clothing_type_id' => $validated['clothing_type_id'],
                    'is_available' => $validated['is_available'],
                ]);

                $product->colors()->sync($validated['colors']);
                $product->sizes()->sync($validated['sizes']);

                foreach ($validated['colors'] as $color_id) {
                    foreach ($validated['sizes'] as $size_id) {
                        $quantity = $validated['color_size_quantities'][$color_id][$size_id] ?? 0;
                        if ($quantity > 0) {
                            ProductColorSize::create([
                                'product_id' => $product->id,
                                'color_id' => $color_id,
                                'size_id' => $size_id,
                                'quantity' => $quantity,
                            ]);
                        }
                    }
                }

                foreach ($validated['stores'] as $index => $store_id) {
                    $quantity = $validated['store_quantities'][$store_id] ?? 0;
                    if ($quantity > 0) {
                        $product->stores()->attach($store_id, ['quantity' => $quantity]);
                        Supply::create([
                            'store_id' => $store_id,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'status' => 'pending',
                        ]);
                    }
                }

                Log::info('Product created successfully', ['product_id' => $product->id]);
                return redirect()->route('admin.products.index')->with('success', 'Товар создан.');
            });
        } catch (ValidationException $e) {
            Log::error('Validation failed while storing product', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Failed to store product', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Ошибка при создании товара: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = Category::where('is_active', true)->get();
        $collections = Collection::all();
        $clothingTypes = ClothingType::all();
        $colors = Color::all();
        $sizes = Size::all();
        $stores = Store::all();
        $colorSizes = $product->colorSizes()->get()->groupBy(['color_id', 'size_id']);
        return view('adminProductsEdit', compact('product', 'brands', 'categories', 'collections', 'clothingTypes', 'colors', 'sizes', 'stores', 'colorSizes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'clothing_type_id' => 'required|exists:clothing_types,id',
            'is_available' => 'required|boolean',
            'colors' => 'required|array|min:1',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'exists:sizes,id',
            'color_size_quantities' => 'required|array',
            'color_size_quantities.*.*' => 'nullable|integer|min:0',
            'stores' => 'required|array|min:1',
            'stores.*' => 'exists:stores,id',
            'store_quantities' => 'required|array',
            'store_quantities.*' => 'nullable|integer|min:0',
        ]);

        return DB::transaction(function () use ($validated, $product) {
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'collection_id' => $validated['collection_id'],
                'clothing_type_id' => $validated['clothing_type_id'],
                'is_available' => $validated['is_available'],
            ]);

            $product->colors()->sync($validated['colors']);
            $product->sizes()->sync($validated['sizes']);
            $product->colorSizes()->delete();

            foreach ($validated['colors'] as $color_id) {
                foreach ($validated['sizes'] as $size_id) {
                    $quantity = $validated['color_size_quantities'][$color_id][$size_id] ?? 0;
                    if ($quantity > 0) {
                        ProductColorSize::create([
                            'product_id' => $product->id,
                            'color_id' => $color_id,
                            'size_id' => $size_id,
                            'quantity' => $quantity,
                        ]);
                    }
                }
            }

            $product->stores()->sync([]);
            foreach ($validated['stores'] as $store_id) {
                $quantity = $validated['store_quantities'][$store_id] ?? 0;
                if ($quantity > 0) {
                    $product->stores()->attach($store_id, ['quantity' => $quantity]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Товар обновлён.');
        });
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар удален.');
    }

    public function admin()
    {
        return view('admin');
    }
}