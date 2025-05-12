@extends('layouts.asset')

@section('content')
    <div class="new-cart-content">
        <h1 class="new-cart-title">Корзина</h1>
        <div class="new-cart-container">
            <div class="new-cart-items">
                @if($carts->isEmpty())
                    <div class="new-empty-cart-block">
                        <h2>Корзина пуста</h2>
                        <p>Добавьте товары в корзину, чтобы оформить заказ.</p>
                        <h1>
                            <a href="{{ route('products.index') }}" class="new-catalog-button">Перейти в Каталог</a>
                        </h1>
                    </div>
                @else
                    @foreach($carts as $cart)
                        @php
                            $product = \App\Models\Product::find($cart->productId);
                        @endphp
                        @if($product)
                            <div class="new-cart-product-item">
                                <div class="new-product-image">
                                    <a href="{{ route('product.show', $product->id) }}">
                                        @if($product->images)
                                            <img src="{{ Storage::url($product->images->previewImagePath) }}" alt="Preview Image" class="new-product-thumbnail">
                                        @else
                                            <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="new-product-thumbnail">
                                        @endif
                                    </a>
                                </div>
                                <div class="new-product-details">
                                    <h2 class="new-product-price">
                                        <span>{{ $product->price }}</span>
                                        <span>byn</span>
                                    </h2>
                                    <span class="new-product-title">{{ $product->shortTitle }}</span>
                                </div>
                                <div class="new-product-quantity">
                                    <form action="{{ route('cart.update', $product->id) }}" method="POST" class="new-quantity-controls">
                                        @csrf
                                        <button type="submit" name="action" value="decrement" class="new-quantity-button">-</button>
                                        <input type="number" name="quantity" value="{{ $cart->quantity }}" class="new-quantity-input" readonly>
                                        <button type="submit" name="action" value="increment" class="new-quantity-button">+</button>
                                    </form>
                                </div>
                                <div class="new-product-actions">
                                    <form action="{{ route('cart.remove', $product->id) }}" method="post" class="new-remove-cart-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="new-remove-cart-button">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="new-cart-summary" style="text-align: center; margin-top: 20px;">
                @if($carts->isNotEmpty())
                    <p class="new-total-amount">Общая сумма: <span>{{ $totalSum }} byn</span></p>
                    <form action="{{ route('cart.checkout') }}" method="POST" class="new-checkout-form" id="checkoutForm">
                        @csrf
                        
                        <div class="delivery-method">
                            <label for="receivingMethod">Способ получения:</label>
                            <select name="receivingMethod" id="receivingMethod" required>
                                <option value="">Выберите способ</option>
                                <option value="delivery" {{ old('receivingMethod') == 'delivery' ? 'selected' : '' }}>Доставка</option>
                                <option value="pickup" {{ old('receivingMethod') == 'pickup' ? 'selected' : '' }}>Самовывоз</option>
                            </select>
                            @error('receivingMethod')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div id="deliveryAddressField" style="display: none; margin-top: 10px;">
                            <label for="deliveryAddress">Адрес доставки:</label>
                            <input type="text" name="deliveryAddress" id="deliveryAddress" 
                                   value="{{ old('deliveryAddress') }}" 
                                   placeholder="Введите адрес доставки">
                            @error('deliveryAddress')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div id="pickupPointField" style="display: none; margin-top: 10px;">
                            <label for="pickupPoint">Пункт самовывоза:</label>
                            <select name="pickupPoint" id="pickupPoint">
                                <option value="">Выберите пункт выдачи</option>
                                <option value="ул. Ленина, 10" {{ old('pickupPoint') == 'ул. Ленина, 10' ? 'selected' : '' }}>ул. Ленина, 10</option>
                                <option value="пр. Победителей, 25" {{ old('pickupPoint') == 'пр. Победителей, 25' ? 'selected' : '' }}>пр. Победителей, 25</option>
                                <option value="ул. Октябрьская, 50" {{ old('pickupPoint') == 'ул. Октябрьская, 50' ? 'selected' : '' }}>ул. Октябрьская, 50</option>
                                <option value="ул. Советская, 15" {{ old('pickupPoint') == 'ул. Советская, 15' ? 'selected' : '' }}>ул. Советская, 15</option>
                                <option value="пр. Независимости, 100" {{ old('pickupPoint') == 'пр. Независимости, 100' ? 'selected' : '' }}>пр. Независимости, 100</option>
                            </select>
                            @error('pickupPoint')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="new-checkout-button">Заказать</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const methodSelect = document.getElementById('receivingMethod');
    const deliveryField = document.getElementById('deliveryAddressField');
    const pickupField = document.getElementById('pickupPointField');
    const deliveryInput = document.getElementById('deliveryAddress');
    const pickupSelect = document.getElementById('pickupPoint');
    const form = document.getElementById('checkoutForm');

    if (form) {
        function updateFields() {
            const method = methodSelect.value;
            
            deliveryField.style.display = 'none';
            pickupField.style.display = 'none';
            deliveryInput.removeAttribute('required');
            pickupSelect.removeAttribute('required');
            
            if (method === 'delivery') {
                deliveryField.style.display = 'block';
                deliveryInput.setAttribute('required', 'required');
                pickupSelect.value = ''; 
            } else if (method === 'pickup') {
                pickupField.style.display = 'block';
                pickupSelect.setAttribute('required', 'required');
                deliveryInput.value = ''; 
            } else {
                deliveryInput.value = '';
                pickupSelect.value = '';
            }
        }

        updateFields();

        methodSelect.addEventListener('change', updateFields);

        form.addEventListener('submit', function(event) {
            const method = methodSelect.value;
            if (method === 'delivery' && !deliveryInput.value.trim()) {
                event.preventDefault();
                deliveryInput.focus();
                alert('Пожалуйста, введите адрес доставки');
            } else if (method === 'pickup' && !pickupSelect.value) {
                event.preventDefault();
                pickupSelect.focus();
                alert('Пожалуйста, выберите пункт самовывоза');
            }
        });
    }
});
    </script>
@endsection