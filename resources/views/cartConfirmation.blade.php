@extends('layouts.app')

@section('title', 'Подтверждение заказа')

@section('content')
    <div class="new-cart-content">
        <h1 class="new-cart-title">Подтверждение заказа</h1>
        <div class="new-cart-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="new-order-confirmation">
                <h2>Детали заказа</h2>
                <p><strong>Номер заказа:</strong> {{ $order->id }}</p>
                <p><strong>Пользователь:</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} ({{ Auth::user()->email }})</p>
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
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->color->name }}</td>
                                <td>{{ $item->size->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="new-total-amount">Общая сумма: <span>{{ $order->total }} ₽</span></p>
                <p><strong>Способ получения:</strong> {{ $order->delivery_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</p>
                @if($order->delivery_method === 'delivery')
                    <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                @else
                    <p><strong>Пункт выдачи:</strong> {{ $order->store->name }}</p>
                @endif
                <p><strong>Дата заказа:</strong> {{ $order->order_date->format('d.m.Y H:i') }}</p>
                <p><strong>Важно:</strong> Чек был сгенерирован и доступен для скачивания. Пожалуйста, сохраните чек и предъявите его при получении заказа.</p>
                <a href="{{ route('cart.downloadReceipt', $order->id) }}" class="new-checkout-button">Скачать чек</a>
            </div>
        </div>
    </div>
    <a href="{{ route('products.index') }}">Вернуться к товарам</a>
@endsection