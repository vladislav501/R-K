@extends('layouts.app')

@section('title', 'Проверка поставки')

@section('content')
    <h1>Проверка поставки №{{ $supply->id }}</h1>
    <p>Товар: {{ $supply->product->name }}</p>
    <p>Количество: {{ $supply->quantity }}</p>
    <p>Статус: {{ $supply->status }}</p>
    <form action="{{ route('manager.supplies.confirm', $supply) }}" method="POST">
        @csrf
        <label>
            <input type="radio" name="is_fully_received" value="1" required> Полностью получено
        </label>
        <label>
            <input type="radio" name="is_fully_received" value="0" required> Частично получено
        </label>
        @error('is_fully_received')
            <span>{{ $message }}</span>
        @enderror
        <button type="submit">Подтвердить</button>
    </form>
    <a href="{{ route('manager.supplies.index') }}">Вернуться к поставкам</a>
@endsection