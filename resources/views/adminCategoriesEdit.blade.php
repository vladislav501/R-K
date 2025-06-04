@extends('layouts.app')

@section('title', 'Редактировать категорию')

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
            <h1>Редактировать категорию</h1>
            @if(session('success'))
                <div class="admin-alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="admin-alert-danger">
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <div class="admin-form">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">Название:</label>
                        <input type="text" name="name" id="name" value="{{ $category->name }}" required>
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>
                            <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}> Активна
                        </label>
                    </div>
                    <button type="submit">Сохранить</button>
                </form>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="admin-back">Назад</a>
        </div>
    </div>
@endsection