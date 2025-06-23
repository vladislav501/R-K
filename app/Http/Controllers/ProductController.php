<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ClothingType;
use App\Models\Collection;
use App\Models\Color;
use App\Models\PickupPoint;
use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

public function index(Request $request)
{
    if ($request->has('pickup_point_id')) {
        $ppId = $request->input('pickup_point_id');
        if ($ppId === null || $ppId === 'null' || $ppId === '') {
            session()->forget('pickup_point_id');
        } else {
            session(['pickup_point_id' => $ppId]);
        }
    }

    $pickupPointId = session('pickup_point_id');

    $query = Product::query()->with([
        'brand', 'category', 'collection', 'clothingType', 'colors', 'sizes', 'colorSizes', 'pickupPoints'
    ]);

    if ($pickupPointId) {
        $query->whereHas('pickupPoints', function ($q) use ($pickupPointId) {
            $q->where('pickup_points.id', $pickupPointId)
              ->where('product_pickup_point.quantity', '>', 0);
        });
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }

    if ($request->filled('collection_id')) {
        $query->where('collection_id', $request->collection_id);
    }

    if ($request->filled('color_id')) {
        $query->whereHas('colors', function ($q) use ($request) {
            $q->where('colors.id', $request->color_id);
        });
    }

    if ($request->filled('size_id')) {
        $query->whereHas('sizes', function ($q) use ($request) {
            $q->where('sizes.id', $request->size_id);
        });
    }

    if ($request->filled('query')) {
        $query->where('name', 'like', '%' . $request->query('query') . '%');
    }

    if ($request->is('admin/*')) {
        $products = $query->get();
    } else {
    $products = $query->paginate(9)->appends($request->query());
    }

    $products->transform(function ($product) use ($pickupPointId) {
        $product->is_in_cart = $product->isInCart();
        $product->is_favorite = auth()->check() && $product->favorites()->where('user_id', auth()->id())->exists();
        if ($pickupPointId) {
            $pivot = $product->pickupPoints()->where('pickup_point_id', $pickupPointId)->first()?->pivot;
            $product->available_quantity = $pivot?->quantity ?? 0;
            $product->available_status = $product->available_quantity > 0 ? 'В наличии' : 'Нет в наличии';
        } else {
            $product->available_quantity = null; 
            $product->available_status = 'В наличии';
        }
        return $product;
    });

    $categories = Category::where('is_active', true)->get();
    $brands = Brand::all();
    $collections = Collection::all();
    $colors = Color::all();
    $sizes = Size::all();
    $totalQuantity = \App\Models\SupplyItem::getTotalAvailableQuantity($pickupPointId);

    if ($request->is('admin/*')) {
        return view('adminProductsIndex', compact('products', 'categories', 'brands', 'collections', 'colors', 'sizes'));
    }

    return view('productsIndex', array_merge(
        compact('products', 'categories', 'brands', 'collections', 'colors', 'sizes'),
        ['totalQuantity' => $totalQuantity]
    ));
}


    public function category(Request $request, Category $category)
    {
        $pickupPointId = session('pickup_point_id');
        $query = Product::query()->with(['brand', 'category', 'collection', 'clothingType', 'colors', 'sizes'])
            ->where('category_id', $category->id);

        if ($pickupPointId) {
            $query->whereHas('pickupPoints', function ($q) use ($pickupPointId) {
                $q->where('pickup_points.id', $pickupPointId)
                  ->where('product_pickup_point.quantity', '>', 0);
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('collection_id')) {
            $query->where('collection_id', $request->collection_id);
        }
        if ($request->filled('color_id')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->where('colors.id', $request->color_id);
            });
        }
        if ($request->filled('size_id')) {
            $query->whereHas('sizes', function ($q) use ($request) {
                $q->where('sizes.id', $request->size_id);
            });
        }
        if ($request->filled('query')) {
            $query->where('name', 'like', '%' . $request->query('query') . '%');
        }

        $products = $query->paginate(12)->appends($request->query());
        $products->getCollection()->transform(function ($product) use ($pickupPointId) {
            $product->is_in_cart = $product->isInCart();
            $product->is_favorite = auth()->check() && $product->favorites()->where('user_id', auth()->id())->exists();
            if ($pickupPointId) {
                $product->available_quantity = $product->pickupPoints()
                    ->where('pickup_point_id', $pickupPointId)
                    ->first()
                    ->pivot
                    ->quantity ?? 0;
            } else {
                $product->available_quantity = $product->colorSizes()->sum('quantity');
            }
            return $product;
        });

        $brands = Brand::all();
        $collections = Collection::all();
        $colors = Color::all();
        $sizes = Size::all();
        $totalQuantity = \App\Models\SupplyItem::getTotalAvailableQuantity($pickupPointId);

        return view('productsCategory', compact('products', 'category', 'brands', 'collections', 'colors', 'sizes', 'totalQuantity'));
    }

    public function show(Product $product)
    {
        $pickupPointId = session('pickup_point_id');
        $product->load(['brand', 'category', 'collection', 'clothingType', 'colors', 'sizes']);
        $product->is_in_cart = $product->isInCart();
        $product->is_favorite = auth()->check() && $product->favorites()->where('user_id', auth()->id())->exists();
        if ($pickupPointId) {
            $product->available_quantity = $product->pickupPoints()
                ->where('pickup_point_id', $pickupPointId)
                ->first()
                ->pivot
                ->quantity ?? 0;
        } else {
            $product->available_quantity = $product->colorSizes()->sum('quantity');
        }
        return view('productsShow', compact('product'));
    }

    public function search(Request $request)
    {
        return redirect()->route('products.index', array_merge($request->query(), ['query' => $request->input('query')]));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::where('is_active', true)->get();
        $collections = Collection::all();
        $clothingTypes = ClothingType::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('adminProductsCreate', compact('brands', 'categories', 'collections', 'clothingTypes', 'colors', 'sizes'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'=>'required|string|max:255',
        'price'=>'required|numeric|min:0',
        'brand_id'=>'required|exists:brands,id',
        'category_id'=>'required|exists:categories,id',
        'collection_id'=>'nullable|exists:collections,id',
        'clothing_type_id'=>'required|exists:clothing_types,id',
        'colors'=>'required|array|min:1',
        'colors.*'=>'exists:colors,id',
        'sizes'=>'required|array|min:1',
        'sizes.*'=>'exists:sizes,id',
        'image_1'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'image_2'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'image_3'=>'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'is_available'=>'boolean',
    ]);

    DB::transaction(function () use ($validated, $request) {
        $imgs = [];
        foreach (['image_1','image_2','image_3'] as $f) {
            if ($request->hasFile($f)) {
                $imgs[$f] = $request->file($f)->store('product_images','public');
            }
        }

        $product = Product::create(array_merge([
            'name'=>$validated['name'],
            'price'=>$validated['price'],
            'brand_id'=>$validated['brand_id'],
            'category_id'=>$validated['category_id'],
            'collection_id'=>$validated['collection_id'],
            'clothing_type_id'=>$validated['clothing_type_id'],
            'is_available'=>$request->boolean('is_available'),
        ], $imgs));

        // создаем ProductColorSize с quantity = 0
        foreach ($validated['colors'] as $col) {
            foreach ($validated['sizes'] as $sz) {
                ProductColorSize::create([
                    'product_id'=>$product->id,
                    'color_id'=>$col,
                    'size_id'=>$sz,
                    'quantity'=>0,
                ]);
            }
        }
    });

    return redirect()->route('admin.products.index')->with('success','Товар создан');
}


public function edit($id)
{
    $product = Product::with('pickupPoints')->findOrFail($id);
    $colors = Color::all();
    $sizes = Size::all();
    $stores = PickupPoint::all();
    $brands = Brand::all();
    $categories = Category::all();
    $collections = Collection::all();
    $clothingTypes = ClothingType::all();
    $clothingTypeOptions = $clothingTypes ? $clothingTypes->pluck('name', 'id') : collect();

    $colorSizes = [];

    foreach ($colors as $color) {
        foreach ($sizes as $size) {
            $quantity = $product->colorSizes()
                ->where('color_id', $color->id)
                ->where('size_id', $size->id)
                ->first()
                ->quantity ?? 0;

            $colorSizes[$color->id][$size->id] = (object)['quantity' => $quantity];
        }
    }

    return view('adminProductsEdit', [
        'product' => $product,
        'colors' => $colors,
        'sizes' => $sizes,
        'colorSizes' => $colorSizes,
        'stores' => $stores,
        'brands' => $brands,
        'categories' => $categories,
        'collections' => $collections,
        'clothingTypes' => $clothingTypes,
    ]);
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
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_image_1' => 'nullable|boolean',
            'remove_image_2' => 'nullable|boolean',
            'remove_image_3' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($validated, $request, $product) {
            $imagePaths = [];
            foreach (['image_1', 'image_2', 'image_3'] as $imageField) {
                if ($request->hasFile($imageField)) {
                    if ($product->$imageField) {
                        Storage::disk('public')->delete($product->$imageField);
                    }
                    $imagePaths[$imageField] = $request->file($imageField)->store('product_images', 'public');
                } elseif ($request->input("remove_image_$imageField")) {
                    if ($product->$imageField) {
                        Storage::disk('public')->delete($product->$imageField);
                    }
                    $imagePaths[$imageField] = null;
                } else {
                    $imagePaths[$imageField] = $product->$imageField;
                }
            }

            $product->update(array_merge([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'brand_id' => $validated['brand_id'],
                'category_id' => $validated['category_id'],
                'collection_id' => $validated['collection_id'],
                'clothing_type_id' => $validated['clothing_type_id'],
                'is_available' => $validated['is_available'],
            ], $imagePaths));

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

            return redirect()->route('admin.products.index')->with('success', 'Товар обновлён.');
        });
    }

    public function destroy(Product $product)
    {
        foreach (['image_1', 'image_2', 'image_3'] as $imageField) {
            if ($product->$imageField) {
                Storage::disk('public')->delete($product->$imageField);
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар удален.');
    }

    public function admin()
    {
        return view('admin');
    }
}