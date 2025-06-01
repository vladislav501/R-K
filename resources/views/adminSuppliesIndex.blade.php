@extends('layouts.app')

@section('title', 'Управление поставками')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Управление поставками</h1>
        <a href="/admin/supplies/create" class="btn btn-primary mb-4">Создать поставку</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
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
                            <th>Количество</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supply->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->color ? $item->color->name : '-' }}</td>
                                <td>{{ $item->size ? $item->size->name : '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="/admin/supplies/download" class="btn btn-secondary">Скачать список</a>
            </div>
        @empty
            <p>Поставок нет.</p>
        @endforelse
        <a href="/admin/supplies/archive" class="btn btn-secondary">Архив поставок</a>
    </div>
@endsection