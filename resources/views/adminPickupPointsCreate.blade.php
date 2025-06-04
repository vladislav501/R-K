@extends('layouts.app')

@section('title', 'Создать пункт')

@section('content')
<div class="pickup-point-create-wrapper">
    <div class="pickup-point-create-nav">
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
    <div class="pickup-point-create-content">
        <h1>Создать пункт выдачи</h1>
        @if(session('success'))
            <div class="pickup-point-create-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="pickup-point-create-error">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <div class="pickup-point-create-form">
            <form method="POST" action="{{ route('admin.pickup_points.store') }}">
                @csrf
                <div>
                    <label for="name">Название:</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="address">Адрес:</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}" required>
                    @error('address') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="hours">Часы работы:</label>
                    <input type="text" name="hours" id="hours" value="{{ old('hours') }}" required>
                    @error('hours') <span class="pickup-point-create-error-text">{{ $message }}</span> @endif
                </div>
                <div>
                    <label for="manager_first_name">Имя менеджера:</label>
                    <input type="text" name="manager_first_name" id="manager_first_name" value="{{ old('manager_first_name') }}" required>
                    @error('manager_first_name') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="manager_last_name">Фамилия менеджера:</label>
                    <input type="text" name="manager_last_name" id="manager_last_name" value="{{ old('manager_last_name') }}" required>
                    @error('manager_last_name') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="manager_email">Email менеджера:</label>
                    <input type="email" name="manager_email" id="manager_email" value="{{ old('manager_email') }}" required>
                    @error('manager_email') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="manager_password">Пароль менеджера:</label>
                    <input type="password" name="manager_password" id="manager_password" required>
                    @error('manager_password') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="manager_password_confirmation">Подтверждение пароля:</label>
                    <input type="password" name="manager_password_confirmation" id="manager_password_confirmation" required>
                    @error('manager_password_confirmation') <span class="pickup-point-create-error-text">{{ $message }}</span> @enderror
                </div>
                <button type="submit">Создать</button>
            </form>
        </div>
        <a href="{{ route('admin.pickup_points.index') }}" class="pickup-point-create-back">Назад</a>
    </div>
</div>
@endsection