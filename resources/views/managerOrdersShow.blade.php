@extends('layouts.app')

@section('title', 'Подробности заказа')

@section('content')
    <div class="order-details">
        <div class="order-details-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.orders.index') }}">Заказы</a></li>
                <li><a href="{{ route('manager.orders.archive') }}">Архив заказов</a></li>
                <li><a href="{{ route('manager.products.index') }}">Товары</a></li>
            </ul>
        </div>
        <div class="order-details-content">
            <h1>Заказ №{{ $order->id }}</h1>
            <p><strong>Клиент:</strong> {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
            <p><strong>Сумма:</strong> {{ $order->total }} BYN</p>
            <p><strong>Статус:</strong> {{ $order->status_label }}</p>
            <p><strong>Способ доставки:</strong> {{ $order->delivery_method == 'delivery' ? 'Доставка' : 'Самовывоз' }}</p>
            @if($order->delivery_address)
                <p><strong>Адрес доставки:</strong> {{ $order->delivery_address }}</p>
            @endif
            @if($order->pickupPoint)
                <p><strong>Пункт выдачи:</strong> {{ $order->pickupPoint->name }} ({{ $order->pickupPoint->address }})</p>
            @endif
            <h2>Товары:</h2>
            <div class="order-details-table">
                <table>
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Размер</th>
                            <th>Цвет</th>
                            <th>Количество</th>
                            <th>Цена</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->size->name }}</td>
                                <td>{{ $item->color->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price * $item->quantity }} BYN</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('manager.orders.index') }}" class="order-details-back">Вернуться к заказам</a>
            <button class="order-details-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.order-details-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection