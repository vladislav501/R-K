@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
    <div class="catalog-content">
        <h1 class="catalog-title">Каталог товаров</h1><br>
        <div class="productsQuantity">
            @if ($totalQuantity !== null)
                <div class="mb-4 text-sm text-gray-700">
                    Всего доступно товаров {{ session('pickup_point_id') ? 'в ПВЗ' : 'в общем каталоге' }}: {{ $totalQuantity }}
                </div>
            @endif
        </div>
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
                    <label for="color_id" class="catalog-filter-label-color">Цвет:</label>
                    <select name="color_id" id="color_id" class="catalog-filter-select">
                        <option value="">Все цвета</option>
                        @foreach($colors as $color)
                            <option value="${ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
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
                <input type="hidden" name="query" value="{{ request('query') }}">
                <input type="hidden" name="pickup_point_id" value="{{ session('pickup_point_id') }}">
                <button type="submit" class="catalog-filter-button">Фильтровать</button>
            </form>

            <div class="paginationContent">
                <div class="pagination pagination-bottom">
                    {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
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
                                    <h2><strong>Цена:</strong> {{ number_format($product->price, 2) }} BYN</h2>
                                    <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                                    <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                                    @if($product->collection)
                                        <p><strong>Коллекция:</strong> {{ $product->collection->name }}</p>
                                    @endif
                                </div>
                            </a>
                            <div class="product-actions">
                                @auth
                                    @php
                                        $inCart = session('cart', collect())->contains(function ($item) use ($product) {
                                            return $item['product_id'] == $product->id &&
                                                $item['pickup_point_id'] == session('pickup_point_id');
                                        });
                                    @endphp
                                    <button class="toggle-cart-button" data-product-id="{{ $product->id }}" {{ $inCart || $product->available_quantity == 0 ? 'disabled' : '' }}>
                                        {{ $inCart ? 'В корзине' : ($product->available_quantity == 0 ? 'Нет в наличии' : 'В корзину') }}
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
                        <div class="modal" id="cart-modal-{{ $product->id }}">
                            <div class="modal-content">
                                <h2>Добавить в корзину: {{ $product->name }}</h2>
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="product-form" id="cart-form-{{ $product->id }}">
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
                    @empty
                        <p class="no-products">Товары не найдены.</p>
                    @endforelse
                </div>
                <div class="pagination pagination-bottom">
                    {{ $products->appends(request()->query())->links('vendor.pagination.custom') }}
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
            const cancelButtons = document.querySelectorAll('.modal-button.cancel');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const productId = this.getAttribute('data-product-id');
                    const modal = document.getElementById(`cart-modal-${productId}`);
                    if (modal) {
                        modal.style.display = 'flex';
                        document.body.style.overflow = 'hidden'; // Prevent scrolling
                    }
                });
            });

            cancelButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const modalId = this.getAttribute('data-modal-id');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto'; // Restore scrolling
                    }
                });
            });

            const cartForms = document.querySelectorAll('.product-form');
            cartForms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    const modal = this.closest('.modal');
                    const toggleButton = document.querySelector(`.toggle-cart-button[data-product-id="${this.id.split('cart-form-')[1]}"]`);
                    const favoriteForm = document.querySelector(`#favorite-form-${this.id.split('cart-form-')[1]}`);
                    
                    this.submit();
                    if (modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                    if (toggleButton) {
                        toggleButton.disabled = true;
                        toggleButton.textContent = 'В корзине';
                    }
                    if (favoriteForm) {
                        favoriteForm.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
@endsection