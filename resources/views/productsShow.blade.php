@extends('layouts.app')

@section('title', 'Карточка товара')

@section('content')
<div class="item-page-container">
    <div class="item-content">
        <div class="item-image-section">
            @if($product->image_1 || $product->image_2 || $product->image_3)
                <div class="item-main-image-container">
                    <img src="{{ Storage::url($product->image_1 ?? $product->image_2 ?? $product->image_3) }}" alt="{{ $product->name }}" class="item-main-image" id="main-image">
                </div>
                <div class="item-thumbnails-container">
                    <div class="item-thumbnails">
                        @foreach(['image_1', 'image_2', 'image_3'] as $imageField)
                            @if($product->$imageField)
                                <img src="{{ Storage::url($product->$imageField) }}" alt="{{ $product->name }}" class="item-thumbnail" data-image="{{ Storage::url($product->$imageField) }}">
                            @endif
                        @endforeach
                    </div>
                </div>
            @else
                <div class="item-image-placeholder">Нет изображений</div>
            @endif
        </div>
        <div class="item-description">
            <h1>{{ $product->name }}</h1>
            <div class="item-specs">
                <p><strong>Цена:</strong> BYN {{ number_format($product->price, 2) }}</p>
                <p><strong>Бренд:</strong> {{ $product->brand->name }}</p>
                <p><strong>Категория:</strong> {{ $product->category->name }}</p>
                <p><strong>Коллекция:</strong> {{ $product->collection->name ?? 'Нет' }}</p>
                <p><strong>Тип одежды:</strong> {{ $product->clothingType->name }}</p>
                <p><strong>Цвета:</strong> {{ $product->colors->pluck('name')->join(', ') }}</p>
                <p><strong>Размеры:</strong> {{ $product->sizes->pluck('name')->join(', ') }}</p>
                <p><strong>В наличии:</strong> {{ $product->available_quantity > 0 ? 'Да' : 'Нет' }} ({{ $product->available_quantity }})</p>
            </div>
            @if(auth()->check())
                <form action="{{ route('cart.add', $product) }}" method="POST" class="item-add-to-cart-form">
                    @csrf
                    <div class="item-options">
                        <select name="size_id" required class="item-select">
                            <option value="">Выберите размер</option>
                            @foreach($product->sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        <select name="color_id" required class="item-select">
                            <option value="">Выберите цвет</option>
                            @foreach($product->colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="quantity" value="1" min="1" class="item-quantity-input" placeholder="Количество">
                    </div>
                    <button type="submit" class="item-cart-btn">Добавить в корзину</button>
                </form>
                <form action="{{ route('favorites.add', $product) }}" method="POST" class="item-favorite-form">
                    @csrf
                    <button type="submit" class="item-favorite-btn" {{ $product->is_favorite ? 'disabled' : '' }}>
                        {{ $product->is_favorite ? 'В избранном' : 'В избранное' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="item-login-btn">Войти, чтобы добавить в корзину или избранное</a>
            @endif
        </div>
    </div>
    <a href="{{ route('products.index') }}" class="item-back-to-catalog">Назад к каталогу</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.item-thumbnail');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = this.getAttribute('data-image');
                mainImage.style.opacity = '1';
            }, 300);
            thumbnails.forEach(t => t.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    const thumbnailContainer = document.querySelector('.item-thumbnails');
    const prevButton = document.querySelector('.thumbnail-nav-prev');
    const nextButton = document.querySelector('.thumbnail-nav-next');
    let currentPosition = 0;
    const thumbnailWidth = 90; // Thumbnail width + margin

    if (thumbnailContainer && prevButton && nextButton) {
        const thumbnailsCount = thumbnailContainer.children.length;
        const maxPosition = Math.max(0, thumbnailsCount - 3);

        prevButton.addEventListener('click', () => {
            if (currentPosition > 0) {
                currentPosition--;
                thumbnailContainer.style.transform = `translateX(-${currentPosition * thumbnailWidth}px)`;
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentPosition < maxPosition) {
                currentPosition++;
                thumbnailContainer.style.transform = `translateX(-${currentPosition * thumbnailWidth}px)`;
            }
        });
    }
});
</script>
@endsection