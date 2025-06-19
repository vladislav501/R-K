@extends('layouts.app')

@section('title', 'Избранное')

@section('content')
<div class="favorites-wrapper">
    <h1>Избранное</h1>
    @if(session('success'))
        <div class="favorites-success-notice">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="favorites-error-notice">{{ session('error') }}</div>
    @endif
    @if($favorites->isEmpty())
        <p class="favorites-empty-text">У вас нет избранных товаров.</p>
    @else
        <div class="favorites-grid">
            @foreach($favorites as $product)
                <div class="favorite-item">
                    <div class="favorite-image">
                        @if($product->image_1)
                            <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="favorite-item-image">
                        @else
                            <span class="favorite-image-placeholder">Нет изображения</span>
                        @endif
                    </div>
                    <div class="favorite-details">
                        <h3>{{ $product->name }}</h3>
                        <p><strong>Цена:</strong> BYN {{ number_format($product->price, 2) }}</p>
                        <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                        <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                        <p><strong>Цвета:</strong> {{ $product->colors->pluck('name')->join(', ') }}</p>
                        <p><strong>Размеры:</strong> {{ $product->sizes->pluck('name')->join(', ') }}</p>
                        <form action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="favorites-delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="favorites-delete-button" onclick="return confirm('Удалить из избранного?')">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <a href="{{ route('products.index') }}" class="favorites-back-link">Вернуться к каталогу</a>
</div>
@endsection