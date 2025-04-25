<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Type;

class AdminController extends Controller {
    
    public function index() {
        return view('admin');
    }

    public function indexNewProduct() {
        $brands = Brand::all();
        $collections = Collection::all();
        $categories = Category::all();
        $types = Type::all();
        return view('addProduct', compact('brands', 'collections', 'categories', 'types'));        
    }

    public function indexNewBrand() {
        return view('addBrand');        
    }

    public function indexNewCollection() {
        return view('addCollection');        
    }
}
