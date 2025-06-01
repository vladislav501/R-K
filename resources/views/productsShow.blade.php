@extends('layouts.app')

@section('title', 'Товар: ' . $product->name)

@section('content')
    <div class="product-page">
        <a href="{{ route('products.index') }}" class="back-link">Назад к списку товаров</a>
        <div class="product-container">
            <div class="product-images">
                <div class="main-image"></div>
                <div class="thumbnail-container">
                    <div class="thumbnail"></div>
                    <div class="thumbnail"></div>
                    <div class="thumbnail"></div>
                </div>
            </div>
            <div class="product-details">
                <h1>{{ $product->name }}</h1>
                <p><strong>Цена:</strong> {{ $product->price }} ₽</p>
                <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                <p><strong>Тип:</strong> {{ $product->clothingType->name }}</p>
                @if($product->collection)
                    <p><strong>Коллекция:</strong> {{ $product->collection->name }}</p>
                @else
                    <p><strong>Коллекция:</strong> Отсутствует</p>
                @endif
                <p><strong>Цвета:</strong> {{ $product->colors->pluck('name')->join(', ') }}</p>
                <p><strong>Размеры:</strong> {{ $product->sizes->pluck('name')->join(', ') }}</p>
                <p><strong>Наличие:</strong> {{ $product->is_available ? 'В наличии' : 'Нет в наличии' }}</p>
                <form method="POST" action="{{ route('cart.add', $product) }}" class="product-form">
                    @csrf
                    <label for="quantity">Количество:</label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                    <label for="color_id">Цвет:</label>
                    <select id="color_id" name="color_id" required>
                        @foreach($product->colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                    <label for="size_id">Размер:</label>
                    <select id="size_id" name="size_id" required>
                        @foreach($product->sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit">Добавить в корзину</button>
                </form>
                @auth
                    <form method="POST" action="{{ route('favorites.add', $product) }}" class="product-form">
                        @csrf
                        <button type="submit" class="favorite-button" {{ $product->is_favorite ? 'disabled' : '' }}>
                            {{ $product->is_favorite ? 'В избранном' : 'В избранное' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="favorite-button">Войти, чтобы добавить в избранное</a>
                @endauth
            </div>
        </div>
    </div>
@endsection