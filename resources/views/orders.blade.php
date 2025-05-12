@extends('layouts.asset')
@section('content')
    <h2>
        <span class="spanLink">
            <a href="{{ route('admin.preorders.index') }}">Модерация заказов</a>
        </span>
    </h2>
    <h1 class="contentTitle">Архив заказов</h1>
    <table class="pre-orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Товары</th>
                <th>Способ получения</th>
                <th>Адрес доставки / пункта выдачи</th>
                <th>Сумма</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->userId }}</td>
                    <td>
                        @foreach($order->getProducts() as $item)
                            @if($item['product'])
                                {{ $item['product']->shortTitle }} ({{ $item['quantity'] }} шт.)<br>
                            @endif
                        @endforeach
                    </td>
                    <td>{{ $preOrder->receivingMethod }}</td>
                    <td>{{ $preOrder->deliveryAddress }}</td>
                    <td>{{ $order->totalSum }}</td>
                    <td>{{ $order->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection