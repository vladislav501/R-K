@extends('layouts.app')

@section('title', 'Управление цветами')

@section('content')
    <h1>Управление цветами</h1>
    <a href="{{ route('admin.colors.create') }}">Добавить цвет</a>
    @forelse($colors as $color)
        <div>
            <p>{{ $color->name }} ({{ $color->color_code ?? 'Без кода' }})</p>
            <form action="{{ route('admin.colors.destroy', $color) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Цветов нет.</p>
    @endforelse
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection