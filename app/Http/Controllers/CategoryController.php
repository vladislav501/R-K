<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $categories = Category::all();
        return view('adminCategoriesIndex', compact('categories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно обновлена.');
    }
}