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
            <a href="{{ route('products.show', $product) }}" class="favorite-link">
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
                    <p><strong>Цвета:</strong> {{ $product->colors->unique('id')->pluck('name')->join(', ') }}</p>
                    <p><strong>Размеры:</strong> {{ $product->sizes->unique('id')->pluck('name')->join(', ') }}</p>
                </div>
            </a>

            <div class="product-actions">
                @php
                    $inCart = session('cart', collect())->contains(function ($item) use ($product) {
                        return $item['product_id'] == $product->id &&
                            $item['pickup_point_id'] == session('pickup_point_id');
                    });
                @endphp
                <button class="toggle-cart-button favorites-cart-button"
                        data-product-id="{{ $product->id }}"
                        {{ $inCart || $product->available_quantity == 0 ? 'disabled' : '' }}>
                    {{ $inCart ? 'В корзине' : ($product->available_quantity == 0 ? 'Нет в наличии' : 'В корзину') }}
                </button>
            </div>
            <form action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="favorites-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="favorites-delete-button" onclick="return confirm('Удалить из избранного?')">Удалить</button>
            </form>
        </div>
        <div class="modal" id="cart-modal-{{ $product->id }}">
                <div class="modal-content">
                    <h2>Добавить в корзину: {{ $product->name }}</h2>
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="product-form" id="cart-form-{{ $product->id }}">
                        @csrf
                        <div class="product-cart-form">
                            <select name="size_id" required class="product-select">
                                <option value="">Размер</option>
                                @foreach($product->sizes->unique('id') as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                            <select name="color_id" required class="item-select">
                                <option value="">Выберите цвет</option>
                                @foreach($product->colors->unique('id') as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->available_quantity }}" class="product-input" placeholder="Выберите количество">
                            <input type="hidden" name="pickup_point_id" value="{{ session('pickup_point_id') }}">
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="modal-button cancel" data-modal-id="cart-modal-{{ $product->id }}">Отмена</button>
                            <button type="submit" class="modal-button submit">Добавить в корзину</button>
                        </div>
                    </form>
                </div>
            </div>
    @endforeach
</div>
    @endif
    <a href="{{ route('products.index') }}" class="favorites-back-link">Вернуться к каталогу</a>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.favorites-cart-button');
        const cancelButtons = document.querySelectorAll('.modal-button.cancel');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-product-id');
                const modal = document.getElementById(`cart-modal-${productId}`);
                if (modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        cancelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const modalId = this.getAttribute('data-modal-id');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });

        const cartForms = document.querySelectorAll('.product-form');
        cartForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const modal = this.closest('.modal');
                const toggleButton = document.querySelector(`.favorites-cart-button[data-product-id="${this.id.split('cart-form-')[1]}"]`);
                
                this.submit();
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
                if (toggleButton) {
                    toggleButton.disabled = true;
                    toggleButton.textContent = 'В корзине';
                }
            });
        });
    });
</script>
@endsection