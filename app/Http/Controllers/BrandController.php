<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $brands = Brand::all();
        return view('adminBrandsIndex', compact('brands'));
    }

    public function create()
    {
        return view('adminBrandsCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
        ]);

        Brand::create($validated);
        return redirect()->route('admin.brands.index')->with('success', 'Бренд успешно создан.');
    }

    public function edit(Brand $brand)
    {
        return view('adminBrandsEdit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
        ]);

        $brand->update($validated);
        return redirect()->route('admin.brands.index')->with('success', 'Бренд успешно обновлён.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Бренд успешно удалён.');
    }
}