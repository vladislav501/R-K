@extends('layouts.asset')

@section('content')
<div class="content">
    <div class="productsPage flex flex-col md:flex-row gap-6 p-6">
        <!-- Filter Sidebar -->
        <div class="filters w-full md:w-1/4 bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Фильтры</h2>
            <form id="filterForm" class="space-y-4">
                <!-- Price Range -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Цена (BYN)</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" id="min_price" placeholder="От" class="w-1/2 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-600" min="0">
                        <input type="number" name="max_price" id="max_price" placeholder="До" class="w-1/2 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-600" min="0">
                    </div>
                </div>

                <!-- Colors -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Цвет</h3>
                    @foreach (['Red', 'Orange', 'Yellow', 'Green', 'Blue', 'Purple', 'Brown', 'Black'] as $color)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="colors[]" value="{{ $color }}" class="mr-2">
                            <span>{{ $color }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Sizes -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Размер</h3>
                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="sizes[]" value="{{ $size }}" class="mr-2">
                            <span>{{ $size }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Brands -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Бренд</h3>
                    @if ($brands->isEmpty())
                        <p class="text-gray-500">Бренды не найдены</p>
                    @else
                        @foreach ($brands as $brand)
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}" class="mr-2">
                                <span>{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>

                <!-- Sex -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Пол</h3>
                    @foreach (['Male', 'Female', 'Unisex'] as $sex)
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="sex[]" value="{{ $sex }}" class="mr-2">
                            <span>{{ $sex }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Availability -->
                <div class="filterGroup">
                    <h3 class="text-lg font-semibold mb-2">Наличие</h3>
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="availability" value="true" class="mr-2">
                        <span>В наличии</span>
                    </label>
                </div>

                <!-- Reset Button -->
                <button type="button" id="resetFilters" class="w-full bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300">Сбросить фильтры</button>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="products w-full md:w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="productsItem">
                    <div class="itemPhoto">
                        <a href="{{ route('product.show', $product->id) }}">
                            @if($product->images)
                                <img src="{{ Storage::url($product->images->previewImagePath) }}" alt="Preview Image" class="productItemImage">
                            @else
                                <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="productItemImage">
                            @endif
                        </a>
                    </div>
                    <div class="productsInfoContainer">
                        <div class="itemInfo">
                            <h1 class="price">
                                <span>{{ number_format($product->price, 2) }}</span>
                                <span class="text-sm">BYN</span>
                            </h1>
                            <h3>{{ $product->shortTitle }}</h3>
                        </div>
                    </div>
                    <div class="productsBtnsContainer">
                        <form action="{{ route('cart.add', $product->id) }}" method="post" class="productsCartBtnForm">
                            @csrf
                            <button class="productsCartBtn">
                                <span>В корзину</span>
                            </button>
                        </form>
                        <form action="{{ route('favorites.add', $product->id) }}" method="post" class="productsFavoriteBtnForm">
                            @csrf
                            <button class="productsFavoriteBtn">
                                <img src="{{ asset('images/favorite.svg') }}" alt="favoriteItemImage" class="favoriteItemImage">
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            @if ($products->isEmpty())
                <div class="col-span-full text-center text-gray-500 py-8">
                    Нет товаров, соответствующих выбранным фильтрам.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('filterForm');
    const productsContainer = document.querySelector('.products');
    const resetFiltersButton = document.getElementById('resetFilters');

    // Apply filters on input change
    filterForm.addEventListener('change', applyFilters);
    filterForm.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', debounce(applyFilters, 500));
    });

    // Reset filters
    resetFiltersButton.addEventListener('click', () => {
        filterForm.reset();
        applyFilters();
    });

    // Debounce function to limit rapid requests
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async function applyFilters() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        // Log form data for debugging
        const formDataObj = {};
        for (const [key, value] of formData) {
            if (key.endsWith('[]')) {
                if (!formDataObj[key]) formDataObj[key] = [];
                formDataObj[key].push(value);
            } else {
                formDataObj[key] = value;
            }
        }
        console.log('Form Data:', formDataObj);

        // Convert FormData to query string
        for (const [key, value] of formData) {
            params.append(key, value);
        }

        try {
            const url = '{{ request()->url() }}?' + params.toString();
            console.log('Fetching URL:', url);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('HTTP Error:', response.status, errorText);
                throw new Error(`HTTP error ${response.status}: ${errorText}`);
            }

            const products = await response.json();
            console.log('Received products:', products);
            renderProducts(products);
        } catch (error) {
            console.error('Filter error:', error);
            productsContainer.innerHTML = '<div class="col-span-full text-center text-red-500 py-8">Ошибка при загрузке товаров: ' + error.message + '</div>';
        }
    }

    function renderProducts(products) {
        productsContainer.innerHTML = '';
        if (products.length === 0) {
            productsContainer.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">Нет товаров, соответствующих выбранным фильтрам.</div>';
            return;
        }

        products.forEach(product => {
            const productHtml = `
                <div class="productsItem">
                    <div class="itemPhoto">
                        <a href="/products/${product.id}">
                            ${product.images ? 
                                `<img src="/storage/${product.images.previewImagePath}" alt="Preview Image" class="productItemImage">` : 
                                `<img src="/images/empty.svg" alt="emptyImage" class="productItemImage">`
                            }
                        </a>
                    </div>
                    <div class="productsInfoContainer">
                        <div class="itemInfo">
                            <h1 class="price">
                                <span>${parseFloat(product.price).toFixed(2)}</span>
                                <span class="text-sm">BYN</span>
                            </h1>
                            <h3>${product.shortTitle}</h3>
                        </div>
                    </div>
                    <div class="productsBtnsContainer">
                        <form action="/cart/add/${product.id}" method="post" class="productsCartBtnForm">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <button class="productsCartBtn">
                                <span>В корзину</span>
                            </button>
                        </form>
                        <form action="/favorites/add/${product.id}" method="post" class="productsFavoriteBtnForm">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <button class="productsFavoriteBtn">
                                <img src="/images/favorite.svg" alt="favoriteItemImage" class="favoriteItemImage">
                            </button>
                        </form>
                    </div>
                </div>
            `;
            productsContainer.insertAdjacentHTML('beforeend', productHtml);
        });
    }
});
</script>
@endsection