@extends('layouts.app')

@section('title', 'Товары категории {{ $category->name }}')

@section('content')
    <h1>Товары категории {{ $category->name }}</h1>
    <form method="GET" action="{{ route('products.category', $category) }}">
        <div>
            <label for="brand_id">Бренд:</label>
            <select name="brand_id" id="brand_id">
                <option value="">Все бренды</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="collection_id">Коллекция:</label>
            <select name="collection_id" id="collection_id">
                <option value="">Все коллекции</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ request('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Цвет:</label>
            @foreach($colors as $color)
                <label>
                    <input type="checkbox" name="color_id" value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'checked' : '' }}> {{ $color->name }}
                </label>
            @endforeach
        </div>
        <div>
            <label>Размер:</label>
            @foreach($sizes as $size)
                <label>
                    <input type="checkbox" name="size_id" value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'checked' : '' }}> {{ $size->name }}
                </label>
            @endforeach
        </div>
        <button type="submit">Фильтровать</button>
    </form>
    @if($products->isEmpty())
        <p>Товары не найдены.</p>
    @else
        <div>
            @foreach($products as $product)
                <div>
                    <h2>{{ $product->name }}</h2>
                    <p>Цена: {{ $product->price }} ₽</p>
                    <p>Бренд: {{ $product->brand->name }}</p>
                    @if($product->collection)
                        <p>Коллекция: {{ $product->collection->name }}</p>
                    @endif
                    <p>Тип одежды: {{ $product->clothingType->name }}</p>
                    <a href="{{ route('products.show', $product) }}">Подробнее</a>
                </div>
            @endforeach
        </div>
    @endif
@endsection