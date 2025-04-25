<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice for payment</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: "DejaVu Sans", sans-serif;
        }

        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .invoice {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .invoice h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .invoice THEY WERE HERE p {
            color: #555;
            margin: 5px 0;
        }

        .invoice h2 {
            color: #4CAF50;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice th, .invoice td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        .invoice th {
            background-color: #f2f2f2;
            color: #333;
        }

        .invoice tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .invoice tr:hover {
            background-color: #f1f1f1;
        }

        .total {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <h1>Чек об оплате</h1>
        <p>Спасибо за ваш заказ!</p>
        <p>Номер заказа: <strong>{{ $preOrder->id }}</strong></p>
        <p>Сумма: <strong>{{ $preOrder->totalSum }} byn</strong></p>
        <h2>Список товаров:</h2>
        <table>
            <thead>
                <tr>
                    <th>Артикул товара</th>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Общая стоимость</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preOrder->getProducts() as $item)
                    @if($item['product'])
                        <tr>
                            <td>{{ $item['product']->article }}</td>
                            <td>{{ $item['product']->shortTitle }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ $item['product']->price }} byn</td>
                            <td>{{ $item['product']->price * $item['quantity'] }} byn</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <p class="total">Общая сумма: {{ $preOrder->totalSum }} byn</p>
    </div>
</body>
</html>