<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Чек заказа</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s ease-in;
        }
        .receipt-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, #0fa3b1, #f7a072);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #b5e2fa;
            padding-bottom: 15px;
        }
        .receipt-title {
            font-size: 2rem;
            color: #0fa3b1;
            margin: 0;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .receipt-title:hover {
            color: #f7a072;
        }
        .receipt-details {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .receipt-details p {
            margin: 8px 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .receipt-details strong {
            color: #0fa3b1;
            font-weight: 600;
            min-width: 120px;
        }
        .receipt-details svg {
            width: 20px;
            height: 20px;
            fill: #b5e2fa;
        }
        .receipt-table-container {
            overflow-x: auto;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .receipt-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        .receipt-table th,
        .receipt-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eddea4;
        }
        .receipt-table th {
            background-color: #b5e2fa;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .receipt-table td {
            font-size: 0.95rem;
            color: #333;
        }
        .receipt-table tr:last-child td {
            border-bottom: none;
        }
        .receipt-table tr:hover {
            background-color: rgba(181, 226, 250, 0.1);
        }
        .receipt-total {
            font-size: 1.2rem;
            font-weight: 600;
            text-align: right;
            margin: 20px 0;
            color: #0fa3b1;
        }
        .receipt-total span {
            color: #f7a072;
        }
        .receipt-footer {
            border-top: 1px dashed #b5e2fa;
            padding-top: 15px;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .receipt-footer svg {
            width: 18px;
            height: 18px;
            fill: #0fa3b1;
        }
        .decorative-divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(to right, transparent, #b5e2fa, transparent);
            margin: 20px 0;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                padding: 10px;
            }
            .receipt-container::before {
                display: none;
            }
        }
        @media (max-width: 600px) {
            .receipt-container {
                padding: 15px;
            }
            .receipt-title {
                font-size: 1.5rem;
            }
            .receipt-details p {
                font-size: 0.9rem;
            }
            .receipt-table th,
            .receipt-table td {
                padding: 8px;
                font-size: 0.85rem;
            }
            .receipt-total {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1 class="receipt-title">Чек заказа</h1>
        </div>
        <div class="receipt-details">
            <p>
                <strong>Пользователь:</strong> {{ $user->first_name ?? 'Не указан' }} {{ $user->last_name ?? '' }} ({{ $user->email }})
            </p>
            <p>
                <strong>Дата заказа:</strong> {{ $order->order_date->format('d.m.Y H:i:s') }}
            </p>
            @if($order->delivery_method === 'delivery')
                <p>
                    <strong>Адрес доставки:</strong> {{ $order->delivery_address ?? 'Не указан' }}
                </p>
            @else
                <p>
                    <strong>Пункт выдачи:</strong> {{ $order->store->name ?? 'Не указан' }}
                </p>
            @endif
        </div>
        <div class="decorative-divider"></div>
        <div class="receipt-table-container">
            <table class="receipt-table">
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
        </div>
        <p class="receipt-total">Общая сумма: <span>{{ number_format($order->total, 2) }} ₽</span></p>
        <div class="decorative-divider"></div>
        <div class="receipt-footer">
            Пожалуйста, предъявите этот чек при получении заказа.
        </div>
    </div>
</body>
</html>