@extends('layouts.app')

@section('title', 'Подтверждение заказа')

@section('content')
    <div class="new-cart-content">
        <div class="cart-label">
            <a href="{{ route('products.index') }}" class="back-to-cart">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6-4 6z"/>
                </svg>
                Вернуться к товарам
            </a>
            <h1 class="new-cart-title">Подтверждение заказа</h1>
        </div>
        <div class="new-cart-container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="new-order-confirmation">
                <h2>Детали заказа</h2>
                <div class="receipt-info">
                    <p><strong>Номер заказа:</strong> {{ $order->id }}</p>
                    <p><strong>Пользователь:</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} ({{ Auth::user()->email }})</p>
                    <p><strong>Способ получения:</strong> {{ $order->delivery_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</p>
                    @if($order->delivery_method === 'delivery')
                        <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
                    @else
                        <p><strong>Пункт выдачи:</strong> {{ $order->store->name }}</p>
                    @endif
                    <p><strong>Дата заказа:</strong> {{ $order->order_date->format('d.m.Y H:i') }}</p>
                </div>
                <div class="decorative-divider"></div>
                <div class="confirmation-table-container">
                    <table class="confirmation-table">
                        <thead>
                            <tr>
                                <th class="confirmation-table-header">Товар</th>
                                <th class="confirmation-table-header">Цвет</th>
                                <th class="confirmation-table-header">Размер</th>
                                <th class="confirmation-table-header">Количество</th>
                                <th class="confirmation-table-header">Цена</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="confirmation-table-row">
                                    <td class="confirmation-table-cell">{{ $item->product->name }}</td>
                                    <td class="confirmation-table-cell">{{ $item->color->name }}</td>
                                    <td class="confirmation-table-cell">{{ $item->size->name }}</td>
                                    <td class="confirmation-table-cell">{{ $item->quantity }}</td>
                                    <td class="confirmation-table-cell">{{ $item->price }} BYN</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="new-total-amount">Общая сумма: <span>{{ $order->total }} BYN</span></p>
                <div class="decorative-divider"></div>
                <p><strong>Важно:</strong> Чек сгенерирован и доступен для скачивания. Пожалуйста, сохраните чек и предъявите его при получении заказа.</p>
                <a href="{{ route('cart.downloadReceipt', $order->id) }}" class="new-checkout-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 9h-5h-4V3H9v6H5l7 7-5 7zM5 18v2h14v-2H5z"/>
                    </svg>
                    Скачать чек
                </a>
            </div>
        </div>
    </div>
@endsection