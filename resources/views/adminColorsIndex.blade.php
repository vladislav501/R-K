@extends('layouts.app')

@section('title', 'Управление цветами')

@section('content')
    <div class="admin-container">
        <div class="admin-sidebar">
            <h2>Админ Панель</h2>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
                <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
                <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li><a href="{{ route('admin.clothing_types.index') }}">Управление типами одежды</a></li>
                <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
                <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
                <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
                <li><a href="{{ route('admin.pickup_points.index') }}">Управление пунктами выдачи</a></li>
                <li><a href="{{ route('admin.supplies.index') }}">Управление поставками</a></li>
                <li><a href="{{ route('admin.supplies.archive') }}">Архив поставок</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <h1>Управление цветами</h1>
            <a href="{{ route('admin.colors.create') }}" class="admin-add">Добавить цвет</a>
            <div class="admin-list">
                @forelse($colors as $color)
                    <div class="admin-list-item">
                        <p>{{ $color->name }} ({{ $color->color_code ?? 'Без кода' }})</p>
                        <a href="{{ route('admin.colors.store', $color) }}">Редактировать</a>
                        <form action="{{ route('admin.colors.destroy', $color) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Удалить</button>
                        </form>
                    </div>
                @empty
                    <p class="admin-empty">Цветов нет.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.index') }}" class="admin-back">Назад</a>
        </div>
    </div>
@endsection