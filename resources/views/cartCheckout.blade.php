@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
    <div class="new-cart-content">
        <h1 class="new-cart-title">Оформление заказа</h1>
        <div class="new-cart-container">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('cart.place-order') }}" method="POST" class="new-checkout-form">
                @csrf
                <div class="new-checkout-details">
                    <h2>Детали заказа</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цвет</th>
                                <th>Размер</th>
                                <th>Количество</th>
                                <th>Цена</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cart)
                                <tr>
                                    <td>{{ $cart->product->name }}</td>
                                    <td>{{ $cart->color->name }}</td>
                                    <td>{{ $cart->size->name }}</td>
                                    <td>{{ $cart->quantity }}</td>
                                    <td>{{ $cart->product->price }} ₽</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="new-total-amount">Общая сумма: <span>{{ $totalSum }} ₽</span></p>
                </div>

                <div class="new-delivery-options">
                    <h2>Выбор способа доставки</h2>
                    <div>
                        <label>
                            <input type="radio" name="delivery_method" value="delivery" required {{ old('delivery_method') === 'delivery' ? 'checked' : '' }}>
                            Доставка
                        </label>
                        <input type="text" name="delivery_address" placeholder="Адрес доставки" value="{{ old('delivery_address') ?? Auth::user()->delivery_address }}" class="new-input-field delivery-address" {{ old('delivery_method') !== 'delivery' ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label>
                            <input type="radio" name="delivery_method" value="pickup" required {{ old('delivery_method') === 'pickup' ? 'checked' : '' }}>
                            Самовывоз
                        </label>
                        <select name="store_id" class="new-input-field pickup-store" {{ old('delivery_method') !== 'pickup' ? 'disabled' : '' }}>
                            <option value="">Выберите пункт выдачи</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="new-checkout-button">Оформить заказ</button>
            </form>
        </div>
    </div>
    <a href="{{ route('cart.index') }}">Вернуться в корзину</a>

    <script>
        document.querySelectorAll('input[name="delivery_method"]').forEach((radio) => {
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
        document.querySelector('input[name="delivery_method"]:checked')?.dispatchEvent(new Event('change'));
    </script>
@endsection