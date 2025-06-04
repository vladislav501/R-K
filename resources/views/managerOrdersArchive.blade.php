@extends('layouts.app')

@section('title', 'Архив заказов')

@section('content')
    <h1>Архив заказов для {{ $store->name }}</h1>
    @if($orders->isEmpty())
        <p>Архив пуст.</p>
    @else
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
                        <td>{{ $order->total }} ₽</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <a href="{{ route('manager.orders.show', $order) }}">Подробности</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('manager.orders.index') }}">Вернуться к активным заказам</a>
    <button class="back-to-top">
        <i class="fi fi-rr-arrow-small-up"></i>
    </button>
    <script>
        document.querySelector('.back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection