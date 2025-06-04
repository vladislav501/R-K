@extends('layouts.app')

@section('title', 'Заказы')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Заказы для {{ $store->name }}</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($orders->isEmpty())
        <p>Нет активных заказов.</p>
    @else
        <table class="table-auto w-full">
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
                            <a href="{{ route('manager.orders.show', $order) }}" class="btn btn-secondary">Просмотр</a>
                            @if($order->status == 'assembling')
                                <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="assembled">
                                    <button type="submit" class="btn btn-primary">Отметить как собран</button>
                                </form>
                            @elseif($order->status == 'assembled')
                                <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="ready_for_pickup">
                                    <button type="submit" class="btn btn-primary">Готов к выдаче</button>
                                </form>
                            @elseif($order->status == 'ready_for_pickup' && $order->delivery_method == 'delivery')
                                <form action="{{ route('manager.orders.updateStatus', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="handed_to_courier">
                                    <button type="submit" class="btn btn-primary">Передан курьеру</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('manager.orders.archive') }}" class="btn btn-secondary mt-4">Архив заказов</a>
    <button class="back-to-top">
        <i class="fi fi-rr-arrow-small-up"></i>
    </button>
    <script>
        document.querySelector('.back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
@endsection