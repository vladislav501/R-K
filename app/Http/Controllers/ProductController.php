<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller {

    public function index() {
        $products = Product::all();
        return view('products', compact('products'));
    }

    public function indexWomans() {
        $products = Product::all();
        return view('womans', compact('products'));
    }

    public function indexMans() {
        $products = Product::all();
        $categories = Category::all();
        return view('mans', compact('products', 'categories'));
    }

    public function indexAccessory() {
        $products = Product::all();
        return view('accessory', compact('products'));
    }

    public function indexKids() {
        $products = Product::all();
        return view('kids', compact('products'));
    }

    public function indexShoes() {
        $products = Product::all();
        return view('shoes', compact('products'));
    }

    public function indexSale() {
        $products = Product::all();
        return view('sale', compact('products'));
    }

    public function create() {
        $brands = \App\Models\Brand::all();
        $types = \App\Models\Type::all();
        $categories = \App\Models\Category::all();
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
        $brands = \App\Models\Brand::all();
        $types = \App\Models\Type::all();
        $categories = \App\Models\Category::all();
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
}