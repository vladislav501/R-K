@extends('layouts.app')

@section('title', '{{ $product->name }}')

@section('content')
<div class="product-show-wrapper">
    <div class="product-gallery">
        @if($product->image_1 || $product->image_2 || $product->image_3)
            <div class="product-gallery-main">
                <img src="{{ Storage::url($product->image_1 ?? $product->image_2 ?? $product->image_3) }}" alt="{{ $product->name }}" class="product-gallery-main-img" id="main-image">
            </div>
            <div class="product-gallery-thumbnails">
                @foreach(['image_1', 'image_2', 'image_3'] as $imageField)
                    @if($product->$imageField)
                        <img src="{{ Storage::url($product->$imageField) }}" alt="{{ $product->name }}" class="product-gallery-thumbnail" data-image="{{ Storage::url($product->$imageField) }}">
                    @endif
                @endforeach
            </div>
        @else
            <div class="product-gallery-placeholder">Нет изображений</div>
        @endif
    </div>
    <div class="product-details">
        <h1>{{ $product->name }}</h1>
        <div class="product-info">
            <p><strong>Цена:</strong> BYR {{ number_format($product->price, 2) }}</p>
            <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
            <p><strong>Категория:</strong> {{ $product->category->name }}</p>
            <p><strong>Коллекция:</strong> {{ $product->collection->name ?? 'Нет' }}</p>
            <p><strong>Тип одежды:</strong> {{ $product->clothingType->name }}</p>
            <p><strong>Цвета:</strong> {{ $product->colors->pluck('name')->join(', ') }}</p>
            <p><strong>Размеры:</strong> {{ $product->sizes->pluck('name')->join(', ') }}</p>
            <p><strong>В наличии:</strong> {{ $product->is_available ? 'Да' : 'Нет' }}</p>
            @if(auth()->check())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="product-cart-form">
                    @csrf
                    <div class="product-cart-options">
                        <select name="size_id" required class="product-select">
                            <option value="">Выберите размер</option>
                            @foreach($product->sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        <select name="color_id" required class="product-select">
                            <option value="">Выберите цвет</option>
                            @foreach($product->colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity" value="1" min="1" class="product-input" placeholder="Количество">
                    </div>
                    <button type="submit" class="product-cart-button">Добавить в корзину</button>
                </form>
                <form action="{{ route('favorites.add', $product) }}" method="POST" class="product-favorite-form">
                    @csrf
                    <button type="submit" class="product-favorite-button" {{ $product->is_favorite ? 'disabled' : '' }}>
                        {{ $product->is_favorite ? 'В избранном' : 'В избранное' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="product-login-button">Войти, чтобы добавить в корзину или избранное</a>
            @endif
        </div>
    </div>
    <a href="{{ route('products.index') }}" class="product-back-link">Назад к каталогу</a>
</div>
<script>
    document.querySelectorAll('.product-gallery-thumbnail').forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            const mainImage = document.getElementById('main-image');
            mainImage.src = this.getAttribute('data-image');
        });
    });
</script>
@endsection