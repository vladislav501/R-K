@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
    <div class="checkout-content">
        <div class="checkout-label">
            <a href="{{ route('cart.index') }}" class="checkout-back-to-cart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z"/>
                </svg>
                Вернуться в корзину
            </a>
            <h1 class="checkout-title">Оформление заказа</h1>
        </div>
        <div class="checkout-container">
            <div class="checkout-details">
                @if(session('error'))
                    <div class="checkout-alert checkout-alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="checkout-alert checkout-alert-success">{{ session('success') }}</div>
                @endif
                <div class="checkout-details-section">
                    <h2 class="checkout-details-title">Детали заказа</h2>
                    <div class="checkout-table-container">
                        <table class="checkout-table">
                            <thead>
                                <tr class="checkout-table-row">
                                    <th class="checkout-table-header">Товар</th>
                                    <th class="checkout-table-header">Цвет</th>
                                    <th class="checkout-table-header">Размер</th>
                                    <th class="checkout-table-header">Количество</th>
                                    <th class="checkout-table-header">Цена</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carts as $cart)
                                    <tr class="checkout-table-row">
                                        <td class="checkout-table-cell">{{ $cart->product->name }}</td>
                                        <td class="checkout-table-cell">{{ $cart->color->name }}</td>
                                        <td class="checkout-table-cell">{{ $cart->size->name }}</td>
                                        <td class="checkout-table-cell">{{ $cart->quantity }}</td>
                                        <td class="checkout-table-cell">{{ $cart->product->price }} ₽</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="checkout-total-amount">Общая сумма: <span>{{ $totalSum }} ₽</span></p>
                </div>
            </div>
            <div class="checkout-divider"></div>
            <form action="{{ route('cart.place-order') }}" method="POST" class="checkout-form">
                @csrf
                <div class="checkout-delivery-options">
                    <h2 class="checkout-delivery-title">Выбор способа доставки</h2>
                    <div class="checkout-delivery-option">
                        <label class="checkout-radio-label">
                            <input type="radio" name="delivery_method" value="delivery" class="checkout-radio-input" required {{ old('delivery_method') === 'delivery' ? 'checked' : '' }}>
                            Доставка
                        </label>
                        <input type="text" name="delivery_address" placeholder="Адрес доставки" value="{{ old('delivery_address') ?? Auth::user()->delivery_address }}" class="checkout-input-field delivery-address" {{ old('delivery_method') !== 'delivery' ? 'disabled' : '' }}>
                    </div>
                    <div class="checkout-delivery-option">
                        <label class="checkout-radio-label">
                            <input type="radio" name="delivery_method" value="pickup" class="checkout-radio-input" required {{ old('delivery_method') === 'pickup' ? 'checked' : '' }}>
                            Самовывоз
                        </label>
                        <select name="store_id" class="checkout-input-field pickup-store" {{ old('delivery_method') !== 'pickup' ? 'disabled' : '' }}>
                            <option value="">Выберите пункт выдачи</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="checkout-submit-button">Оформить заказ</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.checkout-radio-input[name="delivery_method"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const deliveryAddress = document.querySelector('.delivery-address');
                const pickupStore = document.querySelector('.pickup-store');
                if (this.value === 'delivery') {
                    deliveryAddress.disabled = false;
                    pickupStore.disabled = true;
                    deliveryAddress.required = true;
                    pickupStore.required = false;
                } else {
                    deliveryAddress.disabled = true;
                    pickupStore.disabled = false;
                    deliveryAddress.required = false;
                    pickupStore.required = true;
                }
            });
        });
        document.querySelector('.checkout-radio-input[name="delivery_method"]:checked')?.dispatchEvent(new Event('change'));
    </script>
@endsection