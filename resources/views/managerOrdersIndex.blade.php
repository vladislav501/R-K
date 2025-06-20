@extends('layouts.app')

@section('title', 'Заказы')

@section('content')
    <div class="orders-manager">
        <div class="orders-manager-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.orders.index') }}">Заказы</a></li>
                <li><a href="{{ route('manager.orders.archive') }}">Архив заказов</a></li>
                <li><a href="{{ route('manager.products.index') }}">Товары</a></li>
            </ul>
        </div>
        <div class="orders-manager-content">
            <h1>Заказы для {{ $pickupPoint->name }}</h1>
            @if(session('success'))
                <div class="orders-manager-success">{{ session('success') }}</div>
            @endif
            @if($orders->isEmpty())
                <div class="orders-manager-empty">Нет активных заказов.</div>
            @else
                <div class="orders-manager-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Номер заказа</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                    <td>{{ $order->total }} BYN</td>
                                    <td>{{ $order->status_label }}</td>
                                    <td>
                                        <a href="{{ route('manager.orders.show', $order) }}">Просмотр</a>
                                        @if($order->status == 'assembling')
                                            <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="assembled">
                                                <button type="submit">Отметить как собран</button>
                                            </form>
                                        @elseif($order->status == 'assembled')
                                            <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="ready_for_pickup">
                                                <button type="submit">Готов к выдаче</button>
                                            </form>
                                        @elseif($order->status == 'ready_for_pickup' && $order->delivery_method == 'delivery')
                                            <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="handed_to_courier">
                                                <button type="submit">Передан курьеру</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('manager.orders.archive') }}" class="orders-manager-archive">Архив заказов</a>
            <button class="orders-manager-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.orders-manager-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection