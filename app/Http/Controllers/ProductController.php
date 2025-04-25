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
        return view('addProduct');    
    }    

    public function store(Request $request) {
        $data = $request->validate([
            'brandId' => 'required',
            'sex' => 'required',
            'typeId' => 'required',
            'collectionId' => 'required',
            'categoryId' => 'required',
            'title' => 'string',
            'shortTitle' => 'required',
            'description' => 'required',
            'color' => 'required',
            'price' => 'required',
            'composition' => 'required',
            'designCountry' => 'required',
            'manufacturenCountry' => 'required',
            'importer' => 'required',
            'availability' => 'string',
            'previewImage' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image1' => 'image|mimes:jpeg,png,jpg|max:2048', 
            'image2' => 'image|mimes:jpeg,png,jpg|max:2048', 
            'image3' => 'image|mimes:jpeg,png,jpg|max:2048',
            'image4' => 'image|mimes:jpeg,png,jpg|max:2048', 
        ]);
    
        if (empty($data['article'])) {
            $data['article'] = Str::random(10);
        }
    
        $product = Product::create($data);

        $imageData = [
            'productId' => $product->id,
            'previewImagePath' => $request->file('previewImage')->store('productImages', 'public'),
            'imagePath1' => $request->file('image1')->store('productImages', 'public'),
            'imagePath2' => $request->file('image2')->store('productImages', 'public'),
            'imagePath3' => $request->file('image3')->store('productImages', 'public'),
            'imagePath4' => $request->file('image4')->store('productImages', 'public'),
        ];
    
        $image = Image::create($imageData);

        $product->imageId = $image->id; 
        $product->save(); 

        return redirect()->route('products.index');
    }

    public function show(Product $product) {
        $images = $product->images;
        return view('showProduct', compact('product', 'images'));
    }

    public function edit(Product $product) {
        return view('editProduct', compact('product'));
    }

    public function update(Product $product) {
        $data = request()->validate([
            'brandId' => 'required',
            'sex' => 'required',
            'typeId' => 'required',
            'collectionId' => 'required',
            'categoryId' => 'required',
            'title' => 'string',
            'shortTitle' => 'required',
            'description' => 'required',
            'color' => 'required',
            'price' => 'required',
            'price' => 'required',
            'image' => 'required',
            'composition' => 'required',
            'designCountry' => 'required',
            'manufacturenCountry' => 'required',
            'importer' => 'required',
            'availability' => 'string',
        ]);
        $product->update($data);
        return redirect()->route('product.show', $product->id);
    }

    public function delete(Product $product) {
        $product->delete();
        return redirect()->route('products.index');
    }

    public function getProductsByCategory($categoryId) {
        $products = Product::where('category_id', $categoryId)->get();
        return response()->json($products);
    }
}