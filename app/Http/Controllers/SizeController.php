<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $sizes = Size::all();
        return view('adminSizesIndex', compact('sizes'));
    }

    public function create()
    {
        return view('adminSizesCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes',
        ]);

        Size::create($validated);
        return redirect()->route('admin.sizes.index')->with('success', 'Размер успешно создан.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Размер успешно удалён.');
    }
}