@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
    <h1>Мои заказы</h1>
    @forelse($orders as $order)
        <div>
            <h2>Заказ #{{ $order->id }}</h2>
            <p>Статус: {{ $order->status }}</p>
            <p>Способ получения: {{ $order->delivery_method }}</p>
            <p>Общая сумма: {{ $order->total }} BYN</p>
            <h3>Товары:</h3>
            <ul>
                @foreach($order->items as $item)
                    <li>{{ $item->product->name }} - {{ $item->quantity }} шт. ({{ $item->price }} BYN)</li>
                @endforeach
            </ul>
        </div>
    @empty
        <p>У вас нет заказов.</p>
    @endforelse
    <a href="{{ route('products.index') }}">Вернуться к товарам</a>
@endsection