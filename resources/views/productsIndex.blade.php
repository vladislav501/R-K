@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
    <div class="catalog-content">
        <h1 class="catalog-title">Каталог товаров</h1>
        <div class="catalog-container">
            <form method="GET" action="{{ route('products.index') }}" class="catalog-filters">
                <div class="catalog-filter-group">
                    <label for="category_id" class="catalog-filter-label">Категория:</label>
                    <select name="category_id" id="category_id" class="catalog-filter-select">
                        <option value="">Все категории</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="catalog-filter-group">
                    <label for="brand_id" class="catalog-filter-label">Бренд:</label>
                    <select name="brand_id" id="brand_id" class="catalog-filter-select">
                        <option value="">Все бренды</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="catalog-filter-group">
                    <label for="collection_id" class="catalog-filter-label">Коллекция:</label>
                    <select name="collection_id" id="collection_id" class="catalog-filter-select">
                        <option value="">Все коллекции</option>
                        @foreach($collections as $collection)
                            <option value="{{ $collection->id }}" {{ request('collection_id') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="catalog-filter-group">
                    <label for="color_id" class="catalog-filter-label">Цвет:</label>
                    <select name="color_id" id="color_id" class="catalog-filter-select">
                        <option value="">Все цвета</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="catalog-filter-group">
                    <label for="size_id" class="catalog-filter-label">Размер:</label>
                    <select name="size_id" id="size_id" class="catalog-filter-select">
                        <option value="">Все размеры</option>
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}" {{ request('size_id') == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="catalog-filter-button">Фильтровать</button>
            </form>

            <div class="paginationContent">
                <div class="pagination pagination-bottom">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
                <div class="products">
                    @forelse($products as $product)
                        <div class="product">
                            <a href="{{ route('products.show', $product) }}" class="product-link">
                                <div class="product-image">
                                    @if($product->image_1)
                                        <img src="{{ Storage::url($product->image_1) }}" alt="{{ $product->name }}" class="product-image-img">
                                    @else
                                        <div class="product-image-placeholder">Нет изображения</div>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('products.show', $product) }}" class="product-link">
                                <div class="product-info">
                                    <h2>{{ $product->name }}</h2>
                                    <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                                    <h2><strong>Цена:</strong> {{ number_format($product->price, 2) }} byn</h2>
                                </div>
                            </a>
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="product-form hidden" id="cart-form-{{ $product->id }}">
                                @csrf
                                <div class="product-cart-form">
                                    <select name="size_id" required class="product-select">
                                        <option value="">Размер</option>
                                        @foreach($product->sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="color_id" required class="product-select">
                                        <option value="">Цвет</option>
                                        @foreach($product->colors as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="quantity" value="1" min="1" class="product-input" placeholder="Выберите количество">
                                </div>
                                <button type="submit" class="product-button">
                                    Добавить в корзину
                                </button>
                            </form>
                            <div class="product-actions">
                                @auth
                                    <button class="toggle-cart-button" data-product-id="{{ $product->id }}" {{ $product->is_in_cart ? 'disabled' : '' }}>
                                        {{ $product->is_in_cart ? 'В корзине' : 'В корзину' }}
                                    </button>
                                    <form action="{{ route('favorites.add', $product) }}" method="POST" class="product-form" id="favorite-form-{{ $product->id }}">
                                        @csrf
                                        <button type="submit" class="product-button" {{ $product->is_favorite ? 'disabled' : '' }}>
                                            {{ $product->is_favorite ? 'В избранном' : 'В избранное' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="product-button">Войти, чтобы добавить в корзину или избранное</a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="no-products">Товары не найдены.</p>
                    @endforelse
                </div>
                <div class="pagination pagination-bottom">
                    {{ $products->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
    <button class="back-to-top">
        <i class="fi fi-rr-arrow-small-up"></i>
    </button>
    <script>
        document.querySelector('.back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-cart-button');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const form = document.getElementById(`cart-form-${productId}`);
                    const toggleButton = this;
                    const favoriteForm = document.querySelector(`#favorite-form-${productId}`);
                    if (form.classList.contains('hidden')) {
                        form.classList.remove('hidden');
                        form.classList.add('visible');
                        toggleButton.classList.add('hidden');
                        if (favoriteForm) favoriteForm.classList.add('hidden');
                    }
                });
                const addToCartButton = document.querySelector(`#cart-form-${button.getAttribute('data-product-id')} .product-button`);
                if (addToCartButton) {
                    addToCartButton.addEventListener('click', function (event) {
                        event.preventDefault();
                        const form = this.closest('form');
                        form.submit();
                        form.classList.remove('visible');
                        form.classList.add('hidden');
                        button.classList.remove('hidden');
                        const favoriteForm = document.querySelector(`#favorite-form-${button.getAttribute('data-product-id')}`);
                        if (favoriteForm) favoriteForm.classList.remove('hidden');
                        button.disabled = true;
                        button.textContent = 'В корзине';
                    });
                }
            });
        });
    </script>
@endsection

