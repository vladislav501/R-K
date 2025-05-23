@extends('layouts.app')

@section('title', 'Архив поставок')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Архив поставок</h1>
        @forelse($supplies as $supply)
            <div class="border p-4 mb-4">
                <p><strong>Товар:</strong> {{ $supply->product->name }}</p>
                <p><strong>Цвет:</strong> {{ $supply->color ? $supply->color->name : '-' }}</p>
                <p><strong>Размер:</strong> {{ $supply->size ? $supply->size->name : '-' }}</p>
                <p><strong>Пункт выдачи:</strong> {{ $supply->store->name }}</p>
                <p><strong>Количество отправлено:</strong> {{ $supply->quantity }}</p>
                <p><strong>Количество получено:</strong> {{ $supply->received_quantity ?? 0 }}</p>
                <p><strong>Статус:</strong> {{ $supply->status }}</p>
                <p><strong>Получена полностью:</strong> {{ $supply->is_fully_received ? 'Да' : 'Нет' }}</p>
            </div>
        @empty
            <p>Поставок в архиве нет.</p>
        @endforelse
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection