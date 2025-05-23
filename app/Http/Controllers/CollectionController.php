<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $collections = Collection::all();
        return view('adminCollectionsIndex', compact('collections'));
    }

    public function create()
    {
        return view('adminCollectionsCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections',
            'description' => 'nullable|string',
        ]);

        Collection::create($validated);
        return redirect()->route('admin.collections.index')->with('success', 'Коллекция успешно создана.');
    }

    public function edit(Collection $collection)
    {
        return view('adminCollectionsEdit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'description' => 'nullable|string',
        ]);

        $collection->update($validated);
        return redirect()->route('admin.collections.index')->with('success', 'Коллекция успешно обновлена.');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('admin.collections.index')->with('success', 'Коллекция успешно удалена.');
    }
}