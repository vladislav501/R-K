@extends('layouts.app')

@section('title', 'Управление поставками')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Управление поставками</h1>
        <a href="{{ route('admin.supplies.create') }}" class="btn btn-primary mb-4">Создать поставку</a>
        @forelse($supplies as $supply)
            <div class="border p-4 mb-4">
                <p><strong>Товар:</strong> {{ $supply->product->name }}</p>
                <p><strong>Цвет:</strong> {{ $supply->color ? $supply->color->name : '-' }}</p>
                <p><strong>Размер:</strong> {{ $supply->size ? $supply->size->name : '-' }}</p>
                <p><strong>Пункт выдачи:</strong> {{ $supply->store->name }}</p>
                <p><strong>Количество:</strong> {{ $supply->quantity }}</p>
                <p><strong>Статус:</strong> {{ $supply->status }}</p>
            </div>
        @empty
            <p>Поставок нет.</p>
        @endforelse
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection