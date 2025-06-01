@extends('layouts.app')

@section('title', 'Архив поставок')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Архив поставок</h1>
        @forelse($supplies as $supply)
            <div class="border p-4 mb-4">
                <p><strong>Поставка №:</strong> {{ $supply->id }}</p>
                <p><strong>Пункт выдачи:</strong> {{ $supply->store->name }}</p>
                <p><strong>Статус:</strong> {{ $supply->status }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цвет</th>
                            <th>Размер</th>
                            <th>Отправлено</th>
                            <th>Получено</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supply->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->color ? $item->color->name : '-' }}</td>
                                <td>{{ $item->size ? $item->size->name : '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->received_quantity ?? 0 }}</td>
                                <td>{{ $item->is_fully_received ? 'Полностью' : 'Частично/Не получено' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="admin/supplies/download" class="btn btn-secondary">Скачать список</a>
            </div>
        @empty
            <p>Поставок в архиве нет.</p>
        @endforelse
        <a href="/admin/supplies" class="btn btn-secondary">Назад</a>
    </div>
@endsection