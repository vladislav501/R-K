@extends('layouts.app')

@section('title', 'Подробности заказа')

@section('content')
    <h1>Заказ №{{ $order->id }}</h1>
    <p>Клиент: {{ $order->user->first_name }} {{ $order->user->last_name }}</p>
    <p>Сумма: {{ $order->total }} ₽</p>
    <p>Статус: {{ $order->status }}</p>
    <p>Способ доставки: {{ $order->delivery_method == 'delivery' ? 'Доставка' : 'Самовывоз' }}</p>
    @if($order->delivery_address)
        <p>Адрес доставки: {{ $order->delivery_address }}</p>
    @endif
    @if($order->store)
        <p>Пункт выдачи: {{ $order->store->name }} ({{ $order->store->address }})</p>
    @endif
    <h2>Товары:</h2>
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
                    <td>{{ $item->price * $item->quantity }} ₽</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('manager.orders.index') }}">Вернуться к заказам</a>
@endsection