@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
    <h1>Ваша корзина</h1>
    @if($carts->isEmpty())
        <p>Корзина пуста.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Размер</th>
                    <th>Цвет</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr>
                        <td>{{ $cart->product->name }}</td>
                        <td>{{ $cart->size->name }}</td>
                        <td>{{ $cart->color->name }}</td>
                        <td>
                            <form action="{{ route('cart.update', $cart) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="size_id" value="{{ $cart->size_id }}">
                                <input type="hidden" name="color_id" value="{{ $cart->color_id }}">
                                <button type="submit" name="action" value="decrement">-</button>
                                <span>{{ $cart->quantity }}</span>
                                <button type="submit" name="action" value="increment">+</button>
                            </form>
                        </td>
                        <td>{{ $cart->order_amount }} ₽</td>
                        <td>
                            <form action="{{ route('cart.destroy', $cart) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Общая сумма: {{ $totalSum }} ₽</p>
        <a href="{{ route('cart.checkout') }}">Оформить заказ</a>
    @endif
@endsection