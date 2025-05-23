<?php

namespace App\Http\Controllers;

use App\Models\ClothingType;
use Illuminate\Http\Request;

class ClothingTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $clothingTypes = ClothingType::all();
        return view('adminClothingTypesIndex', compact('clothingTypes'));
    }

    public function create()
    {
        return view('adminClothingTypesCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:clothing_types',
        ]);

        ClothingType::create($validated);
        return redirect()->route('admin.clothing_types.index')->with('success', 'Тип одежды успешно создан.');
    }

    public function edit(ClothingType $clothingType)
    {
        return view('adminClothingTypesEdit', compact('clothingType'));
    }

    public function update(Request $request, ClothingType $clothingType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:clothing_types,name,' . $clothingType->id,
        ]);

        $clothingType->update($validated);
        return redirect()->route('admin.clothing_types.index')->with('success', 'Тип одежды успешно обновлён.');
    }

    public function destroy(ClothingType $clothingType)
    {
        $clothingType->delete();
        return redirect()->route('admin.clothing_types.index')->with('success', 'Тип одежды успешно удалён.');
    }
}