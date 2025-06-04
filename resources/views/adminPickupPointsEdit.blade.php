@extends('layouts.app')

@section('title', 'Редактировать пункт выдачи')

@section('content')
<div class="pickup-point-edit-wrapper">
    <div class="pickup-point-edit-nav">
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
    <div class="pickup-point-edit-content">
        <h1>Редактировать пункт выдачи</h1>
        @if(session('success'))
            <div class="pickup-point-edit-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="pickup-point-edit-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="pickup-point-edit-form">
            <form method="POST" action="{{ route('admin.pickup_points.update', $pickupPoint) }}">
                @csrf
                @method('PUT')
                <div>
                    <label for="name">Название:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $pickupPoint->name) }}" required>
                    @error('name') <span class="pickup-point-edit-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="address">Адрес:</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $pickupPoint->address) }}" required>
                    @error('address') <span class="pickup-point-edit-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="hours">Часы работы:</label>
                    <input type="text" name="hours" id="hours" value="{{ old('hours', $pickupPoint->hours) }}" required>
                    @error('hours') <span class="pickup-point-edit-error-text">{{ $message }}</span> @enderror
                </div>
                <button type="submit">Обновить</button>
            </form>
        </div>
        <a href="{{ route('admin.pickup_points.index') }}" class="pickup-point-edit-back">Назад</a>
    </div>
</div>
@endsection