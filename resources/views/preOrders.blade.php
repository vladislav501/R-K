@extends('layouts.asset')
@section('content')
<h2>
    <span>Вперед:</span>
    <span class="spanLink">
        <a href="{{ route('admin.orders.index') }}">Завершенные заказы</a>
    </span>
</h2>
<h1 class="contentTitle">Модерация заказов</h1>
<table class="pre-orders-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Товар</th>
            <th>Сумма</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($preOrders as $preOrder)
            <tr>
                <td>{{ $preOrder->id }}</td>
                <td>{{ $preOrder->userId }}</td>
                <td>{{ $preOrder->productId }}</td>
                <td>{{ $preOrder->totalSum }}</td>
                <td>{{ $preOrder->status }}</td>
                <td>
                    <div class="action-container">
                        <form action="{{ route('admin.orders.update', $preOrder->id) }}" method="POST">
                            @csrf
                            <select name="status" class="status-select">
                                <option value="Ожидание подтверждения" {{ $preOrder->status == 'Ожидание подтверждения' ? 'selected' : '' }}>Ожидание подтверждения</option>
                                <option value="Подтвержден" {{ $preOrder->status == 'Подтвержден' ? 'selected' : '' }}>Подтвержден</option>
                                {{-- <option value="Отменен" {{ $preOrder->status == 'Отменен' ? 'selected' : '' }}>Отменен</option> --}}
                            </select>
                            <button type="submit" class="update-button">Обновить статус</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection