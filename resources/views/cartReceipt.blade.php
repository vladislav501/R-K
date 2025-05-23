<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Чек заказа</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Чек заказа</h1>
    <p><strong>Пользователь:</strong> {{ $user->first_name ?? 'Не указан' }} {{ $user->last_name ?? '' }} ({{ $user->email }})</p>
    <p><strong>Дата заказа:</strong> {{ $order->order_date->format('d.m.Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цвет</th>
                <th>Размер</th>
                <th>Количество</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Не указан' }}</td>
                    <td>{{ $item->color->name ?? 'Не указан' }}</td>
                    <td>{{ $item->size->name ?? 'Не указан' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }} ₽</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Общая сумма:</strong> {{ number_format($order->total, 2) }} ₽</p>
    @if($order->delivery_method === 'delivery')
        <p><strong>Адрес доставки:</strong> {{ $order->delivery_address ?? 'Не указан' }}</p>
    @else
        <p><strong>Пункт выдачи:</strong> {{ $order->store->name ?? 'Не указан' }}</p>
    @endif
    <p><strong>Важно:</strong> Пожалуйста, предъявите этот чек при получении заказа.</p>
</body>
</html>