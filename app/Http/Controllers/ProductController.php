<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller {

    public function index(Request $request) {
        return $this->filterProducts($request, null, 'products');
    }

    public function indexWomans(Request $request) {
        return $this->filterProducts($request, 1, 'products');
    }

    public function indexMans(Request $request) {
        return $this->filterProducts($request, 2, 'products');
    }

    public function indexAccessory(Request $request) {
        return $this->filterProducts($request, 3, 'products');
    }

    public function indexKids(Request $request) {
        return $this->filterProducts($request, 4, 'products');
    }

    public function indexShoes(Request $request) {
        return $this->filterProducts($request, 5, 'products');
    }

    public function indexSale(Request $request) {
        return $this->filterProducts($request, 6, 'products');
    }

    protected function filterProducts(Request $request, $categoryId = null, $view) {
        try {
            $query = Product::query();
            Log::info('Filter request received', $request->all());

            // Apply category filter if specified
            if ($categoryId) {
                $query->where('categoryId', $categoryId);
                Log::info('Applied category filter: ' . $categoryId);
            }

            // Apply filters from request
            if ($request->has('colors') && !empty($request->input('colors'))) {
                $colors = $request->input('colors');
                $query->where(function ($q) use ($colors) {
                    foreach ($colors as $color) {
                        $q->orWhereJsonContains('color', $color);
                    }
                });
                Log::info('Applied color filter: ', $colors);
            }

            if ($request->has('sizes') && !empty($request->input('sizes'))) {
                $sizes = $request->input('sizes');
                $query->where(function ($q) use ($sizes) {
                    foreach ($sizes as $size) {
                        $q->orWhereJsonContains('size', $size);
                    }
                });
                Log::info('Applied size filter: ', $sizes);
            }

            if ($request->has('brands') && !empty($request->input('brands'))) {
                $brands = $request->input('brands');
                $query->whereIn('brandId', $brands);
                Log::info('Applied brand filter: ', $brands);
            }

            if ($request->has('sex') && !empty($request->input('sex'))) {
                $sex = $request->input('sex');
                $query->whereIn('sex', $sex);
                Log::info('Applied sex filter: ', $sex);
            }

            if ($request->has('min_price') && $request->has('max_price')) {
                $minPrice = $request->input('min_price');
                $maxPrice = $request->input('max_price');
                if ($minPrice !== '' && $maxPrice !== '') {
                    $query->whereBetween('price', [(float)$minPrice, (float)$maxPrice]);
                    Log::info('Applied price filter: ', ['min' => $minPrice, 'max' => $maxPrice]);
                }
            }

            if ($request->has('availability') && $request->input('availability') === 'true') {
                $query->where('availability', true);
                Log::info('Applied availability filter: true');
            }

            $products = $query->with('images')->get();
            $categories = Category::all();
            $brands = Brand::all();

            Log::info('Found products: ' . $products->count());

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json($products);
            }

            // For regular requests, return view
            return view($view, compact('products', 'categories', 'brands'));
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error occurred'], 500);
        }
    }

    public function create() {
        $brands = Brand::all();
        $types = \App\Models\Type::all();
        $categories = Category::all();
        $collections = \App\Models\Collection::all();
        return view('addProduct', compact('brands', 'types', 'categories', 'collections'));    
    }    

    public function store(Request $request) {
        $data = $request->validate([
            'brandId' => 'required|integer',
            'sex' => 'required|string',
            'typeId' => 'required|integer',
            'collectionId' => 'required|integer',
            'categoryId' => 'required|integer',
            'title' => 'required|string',
            'shortTitle' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|array',
            'color.*' => 'string|in:Red,Orange,Yellow,Green,Blue,Purple,Brown,Black',
            'size' => 'required|array',
            'size.*' => 'string|in:XS,S,M,L,XL,XXL',
            'price' => 'required|numeric',
            'composition' => 'required|string',
            'designCountry' => 'required|string',
            'manufacturenCountry' => 'required|string',
            'importer' => 'required|string',
            'availability' => 'nullable|string',
            'previewImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);
    
        if (empty($data['article'])) {
            $data['article'] = Str::random(10);
        }
    
        $product = Product::create($data);

        $imageData = [
            'productId' => $product->id,
            'previewImagePath' => $request->file('previewImage')->store('productImages', 'public'),
            'imagePath1' => $request->file('image1')->store('productImages', 'public'),
        ];
    
        $image = Image::create($imageData);

        $product->imageId = $image->id; 
        $product->save(); 

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function show(Product $product) {
        $images = $product->images;
        return view('showProduct', compact('product', 'images'));
    }

    public function edit(Product $product) {
        $brands = Brand::all();
        $types = \App\Models\Type::all();
        $categories = Category::all();
        $collections = \App\Models\Collection::all();
        return view('editProduct', compact('product', 'brands', 'types', 'categories', 'collections'));
    }

    public function update(Request $request, Product $product) {
        $data = $request->validate([
            'brandId' => 'required|integer',
            'sex' => 'required|string',
            'typeId' => 'required|integer',
            'collectionId' => 'required|integer',
            'categoryId' => 'required|integer',
            'title' => 'required|string',
            'shortTitle' => 'required|string',
            'description' => 'required|string',
            'color' => 'required|array',
            'color.*' => 'string|in:Red,Orange,Yellow,Green,Blue,Purple,Brown,Black',
            'size' => 'required|array',
            'size.*' => 'string|in:XS,S,M,L,XL,XXL',
            'price' => 'required|numeric',
            'composition' => 'required|string',
            'designCountry' => 'required|string',
            'manufacturenCountry' => 'required|string',
            'importer' => 'required|string',
            'availability' => 'nullable|string',
        ]);

        $product->update($data);
        return redirect()->route('product.show', $product->id)->with('success', 'Product updated successfully!');
    }

    public function delete(Product $product) {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function getProductsByCategory($categoryId) {
        $products = Product::where('categoryId', $categoryId)->get();
        return response()->json($products);
    }

    public function search(Request $request) {
        try {
            $query = $request->query('query', '');
            Log::info('Search query received: ' . $query);

            if (empty($query)) {
                Log::info('Empty query, returning empty response');
                return response()->json([]);
            }

            $products = Product::where('title', 'LIKE', "%{$query}%")
                               ->orWhere('article', 'LIKE', "%{$query}%")
                               ->get();

            Log::info('Found products: ' . $products->count());
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error occurred'], 500);
        }
    }
}