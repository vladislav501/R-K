@extends('layouts.app')

@section('title', 'Товар: ' . $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>
    <p>Цена: {{ $product->price }} ₽</p>
    <p>Бренд: {{ $product->brand->name }}</p>
    <p>Категория: {{ $product->category->name }}</p>
    <p>Тип: {{ $product->clothingType->name }}</p>
    @if($product->collection)
        <p>Коллекция: {{ $product->collection->name }}</p>
    @else
        <p>Коллекция: Отсутствует</p>
    @endif
    <p>Цвета: {{ $product->colors->pluck('name')->join(', ') }}</p>
    <p>Размеры: {{ $product->sizes->pluck('name')->join(', ') }}</p>
    <p>Наличие: {{ $product->is_available ? 'В наличии' : 'Нет в наличии' }}</p>
    <a href="{{ route('products.index') }}">Назад к списку товаров</a>
    <form method="POST" action="{{ route('cart.add', $product) }}">
    @csrf
    <label>Количество:</label>
    <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="1" required>
    <label>Цвет:</label>
    <select name="color_id" required>
        @foreach($product->colors as $color)
            <option value="{{ $color->id }}">{{ $color->name }}</option>
        @endforeach
    </select>
    <label>Размер:</label>
    <select name="size_id" required>
        @foreach($product->sizes as $size)
            <option value="{{ $size->id }}">{{ $size->name }}</option>
        @endforeach
    </select>
    <button type="submit">Добавить в корзину</button>
</form>
@endsection