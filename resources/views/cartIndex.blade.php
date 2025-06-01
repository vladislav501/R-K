@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
    <div class="cart-page-container">
        <div class="cart-label">
            <a href="{{ route('products.index') }}" class="back-to-home">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z"/>
                </svg>
                На главную
            </a>
            <h1 class="cart-title">Корзина заказов</h1>
        </div>
        @if($carts->isEmpty())
            <div class="cart-empty-message">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0 0 20 3H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                <p>Ваша корзина пуста</p>
                <p>Добавляйте товары из каталога в корзину, чтобы оформить заказ!</p>
            </div>
        @else
            <div class="table-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th class="cart-table-header">Товар</th>
                            <th class="cart-table-header">Размер</th>
                            <th class="cart-table-header">Цвет</th>
                            <th class="cart-table-header">Количество</th>
                            <th class="cart-table-header">Цена</th>
                            <th class="cart-table-header">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $cart)
                            <tr class="cart-table-row">
                                <td class="cart-table-cell">{{ $cart->product->name }}</td>
                                <td class="cart-table-cell">{{ $cart->size->name }}</td>
                                <td class="cart-table-cell">{{ $cart->color->name }}</td>
                                <td class="cart-table-cell">
                                    <form action="{{ route('cart.update', $cart) }}" method="POST" class="cart-quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="size_id" value="{{ $cart->size_id }}">
                                        <input type="hidden" name="color_id" value="{{ $cart->color_id }}">
                                        <button type="submit" name="action" value="decrement" class="cart-quantity-button">-</button>
                                        <span class="cart-quantity-value">{{ $cart->quantity }}</span>
                                        <button type="submit" name="action" value="increment" class="cart-quantity-button">+</button>
                                    </form>
                                </td>
                                <td class="cart-table-cell">{{ $cart->order_amount }} ₽</td>
                                <td class="cart-table-cell">
                                    <form action="{{ route('cart.destroy', $cart) }}" method="POST" class="cart-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cart-delete-button">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="cart-summary">
                <p class="cart-total">Общая сумма: {{ $totalSum }} ₽</p>
                <a href="{{ route('cart.checkout') }}" class="cart-checkout-button">Оформить заказ</a>
            </div>
        @endif
    </div>
@endsection