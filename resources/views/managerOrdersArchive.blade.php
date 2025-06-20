@extends('layouts.app')

@section('title', 'Архив заказов')

@section('content')
    <div class="orders-archive">
        <div class="orders-archive-sidebar">
            <h2>Менеджер Панель</h2>
            <ul>
                <li><a href="{{ route('manager.orders.index') }}">Заказы</a></li>
                <li><a href="{{ route('manager.orders.archive') }}">Архив заказов</a></li>
                <li><a href="{{ route('manager.products.index') }}">Товары</a></li>
            </ul>
        </div>
        <div class="orders-archive-content">
            <h1>Архив заказов для {{ $pickupPoint->name }}</h1>
            @if($orders->isEmpty())
                <div class="orders-archive-empty">Архив пуст.</div>
            @else
                <div class="orders-archive-table">
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
                                        <a href="{{ route('manager.orders.show', $order) }}">Подробности</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('manager.orders.index') }}" class="orders-archive-back">Вернуться к активным заказам</a>
            <button class="orders-archive-back-to-top">
                <i class="fi fi-rr-arrow-small-up"></i>
            </button>
        </div>
    </div>
    <script>
        document.querySelector('.orders-archive-back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection