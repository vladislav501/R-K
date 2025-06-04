@extends('layouts.app')

@section('title', 'Создать коллекцию')

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
            <h1>Создать коллекцию</h1>
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
                <form method="POST" action="{{ route('admin.collections.store') }}">
                    @csrf
                    <div>
                        <label for="name">Название:</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                        @error('name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="description">Описание:</label>
                        <textarea name="description" id="description">{{ old('description') }}</textarea>
                        @error('description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit">Создать</button>
                </form>
            </div>
            <a href="{{ route('admin.index') }}" class="admin-back">Назад</a>
        </div>
    </div>
@endsection