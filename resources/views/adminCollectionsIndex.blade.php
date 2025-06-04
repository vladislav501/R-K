@extends('layouts.app')

@section('title', 'Управление коллекциями')

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
            <h1>Управление коллекциями</h1>
            <a href="{{ route('admin.collections.create') }}" class="admin-add">Добавить коллекцию</a>
            <div class="admin-list">
                @forelse($collections as $collection)
                    <div class="admin-list-item">
                        <p>{{ $collection->name }}</p>
                        <p>{{ $collection->description }}</p>
                        <a href="{{ route('admin.collections.edit', $collection) }}">Редактировать</a>
                        <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Удалить</button>
                        </form>
                    </div>
                @empty
                    <p class="admin-empty">Коллекций нет.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.index') }}" class="admin-back">Назад</a>
        </div>
    </div>
@endsection