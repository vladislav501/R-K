<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список поставки №{{ $supply->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .checkbox { width: 20px; height: 20px; border: 1px solid #000; display: inline-block; }
    </style>
</head>
<body>
    <h1>Список поставки №{{ $supply->id }}</h1>
    <p><strong>Пункт выдачи:</strong> {{ $store->name }} ({{ $store->address }})</p>
    <p><strong>Дата:</strong> {{ now()->format('d.m.Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Товар</th>
                <th>Цвет</th>
                <th>Размер</th>
                <th>Количество</th>
                <th>Полностью</th>
                <th>Частично</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supply->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->color ? $item->color->name : '-' }}</td>
                    <td>{{ $item->size ? $item->size->name : '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td><span class="checkbox"></span></td>
                    <td><span class="checkbox"></span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Примечание:</strong> Пожалуйста, отметьте галочкой соответствующий статус получения и укажите количество для частично полученных товаров.</p>
    <button class="back-to-top">
        <i class="fi fi-rr-arrow-small-up"></i>
    </button>
    <script>
        document.querySelector('.back-to-top').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>