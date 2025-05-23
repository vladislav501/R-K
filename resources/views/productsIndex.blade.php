@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
    <h1>Каталог товаров</h1>
    <form method="GET" action="{{ route('products.index') }}">
        <div>
            <label for="category_id">Категория:</label>
            <select name="category_id" id="category_id">
                <option value="">Все категории</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
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
            <label for="color_id">Цвет:</label>
            <select name="color_id" id="color_id">
                <option value="">Все цвета</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="size_id">Размер:</label>
            <select name="size_id" id="size_id">
                <option value="">Все размеры</option>
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Фильтровать</button>
    </form>

    <div class="products">
        @forelse($products as $product)
            <div class="product">
                <h2>{{ $product->name }}</h2>
                <p>Цена: {{ $product->price }} ₽</p>
                <p>Бренд: {{ $product->brand->name }}</p>
                <p>Категория: {{ $product->category->name }}</p>
                <p>Тип: {{ $product->clothingType->name }}</p>
                <p>Цвета: {{ $product->colors->pluck('name')->join(', ') }}</p>
                <p>Размеры: {{ $product->sizes->pluck('name')->join(', ') }}</p>
                <p>Наличие: {{ $product->is_available ? 'В наличии' : 'Нет в наличии' }}</p>
                <p>Пункты выдачи: {{ $product->stores->pluck('name')->join(', ') }}</p>
                <a href="{{ route('products.show', $product) }}">Подробнее</a>
                @auth
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1">
                        <select name="size_id" required>
                            <option value="">Выберите размер</option>
                            @foreach($product->sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        <select name="color_id" required>
                            <option value="">Выберите цвет</option>
                            @foreach($product->colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" {{ $product->is_in_cart ? 'disabled' : '' }}>
                            {{ $product->is_in_cart ? 'В корзине' : 'В корзину' }}
                        </button>
                    </form>
                    <form action="{{ route('favorites.add', $product) }}" method="POST">
                        @csrf
                        <button type="submit" {{ $product->is_favorite ? 'disabled' : '' }}>
                            {{ $product->is_favorite ? 'В избранном' : 'В избранное' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Войти, чтобы добавить в корзину или избранное</a>
                @endauth
            </div>
        @empty
            <p>Товары не найдены.</p>
        @endforelse
    </div>
@endsection