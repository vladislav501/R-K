@extends('layouts.app')

@section('title', 'Избранное')

@section('content')
    <h1>Избранное</h1>
    @forelse($favorites as $favorite)
        <div class="favorite-item">
            <h2>{{ $favorite->product->name }}</h2>
            <p>Цена: {{ $favorite->product->price }} ₽</p>
            <p>Наличие: {{ $favorite->is_available ? 'В наличии' : 'Нет в наличии' }}</p>
            <p>Сумма: {{ $favorite->total_amount }} ₽</p>
            <form action="{{ route('favorites.destroy', $favorite) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Избранное пусто.</p>
    @endforelse
@endsection