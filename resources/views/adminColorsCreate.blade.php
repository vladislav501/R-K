@extends('layouts.app')

@section('title', 'Создать цвет')

@section('content')
    <h1>Создать цвет</h1>
    <form method="POST" action="{{ route('admin.colors.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" required>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="color_code">Код цвета (HEX):</label>
            <input type="text" name="color_code" id="color_code">
            @error('color_code')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Создать</button>
    </form>
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection