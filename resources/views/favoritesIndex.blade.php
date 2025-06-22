<meta charset="UTF-8">
    @extends('layouts.app')

@section('title', 'Избранное')

@section('content')
<style>
    .modal {
        display: none;
        position: fixed;
        top;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        overflow-y: auto;
        padding: 10px;
    }
    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
    }
    .modal-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .modal-button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        flex: 1;
        min-width: 100px;
        text-align: center;
    }
    .modal-button.cancel {
        background-color: #ccc;
    }
    .modal-button.submit {
        background-color: #28a745;
        color: white;
    }
    .product-cart-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .product-select, .product-input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }
    .favorites-cart-button {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }
    .favorites-cart-button:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    @media (max-width: 600px) {
        .modal-content {
            padding: 15px;
            max-width: 90%;
        }
        .modal-button {
            padding: 8px 15px;
            min-width: 80px;
        }
        .modal-actions {
            flex-direction: column;
            gap: 8px;
        }
        .modal-button {
            flex: none;
            width: 100%;
        }
        .product-select, .product-input {
            padding: 6px;
        }
    }
    @media (max-width: 400px) {
        .modal-content {
            padding: 10px;
        }
        .modal-button {
            padding: 6px 10px;
            font-size: 14px;
        }
        .product-cart-form {
            gap: 8px;
        }
    }
</style>
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
                        <button class="favorites-cart-button" data-product-id="{{ $product->id }}" {{ $product->is_in_cart || $product->available_quantity == 0 ? 'disabled' : '' }}>
                            {{ $product->is_in_cart ? 'В корзине' : ($product->available_quantity == 0 ? 'Нет в наличии' : 'В корзину') }}
                        </button>
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