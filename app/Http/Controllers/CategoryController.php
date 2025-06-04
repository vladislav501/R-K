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

    public function create()
    {
        return view('adminCategoriesCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно создана.');
    }

    public function edit(Category $category)
    {
        return view('adminCategoriesEdit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $category->update(array_filter($validated));
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно обновлена.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно удалена.');
    }

    public function toggleActive(Request $request, Category $category)
    {
        $category->update([
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Активность категории успешно изменена.');
    }
}