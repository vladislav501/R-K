@extends('layouts.app')

@section('title', 'Управление категориями')

@section('content')
    <div class="admin-container">
        <div class="admin-sidebar">
            <h2>Админ Панель</h2>
            <ul>
                <li><a href="{{ route('admin.products.index') }}">Управление товарами</a></li>
                <li><a href="{{ route('admin.brands.index') }}">Управление брендами</a></li>
                <li><a href="{{ route('admin.categories.index') }}">Управление категориями</a></li>
                <li><a href="">Управление типами одежды</a></li>
                <li><a href="{{ route('admin.collections.index') }}">Управление коллекциями</a></li>
                <li><a href="{{ route('admin.colors.index') }}">Управление цветами</a></li>
                <li><a href="{{ route('admin.sizes.index') }}">Управление размерами</a></li>
                <li><a href="">Управление пунктами выдачи</a></li>
                <li><a href="">Управление поставками</a></li>
                <li><a href="">Архив поставок</a></li>
                <li><a href="">Управление заказами</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <h1>Управление категориями</h1>
            <a href="{{ route('admin.categories.create') }}" class="admin-add">Добавить категорию</a>
            <div class="admin-list">
                @forelse($categories as $category)
                    <div class="admin-list-item">
                        <p>{{ $category->name }}</p>
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label>
                                <input type="checkbox" name="is_active" {{ $category->is_active ? 'checked' : '' }}>
                                Активна
                            </label>
                            <button type="submit">Сохранить</button>
                        </form>
                        <a href="{{ route('admin.categories.edit', $category) }}">Редактировать</a>
                    </div>
                @empty
                    <p class="admin-empty">Категорий нет.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.index') }}" class="admin-back">Назад</a>
        </div>
    </div>
@endsection