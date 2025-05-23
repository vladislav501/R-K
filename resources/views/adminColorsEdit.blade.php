@extends('layouts.app')

@section('title', 'Редактировать цвет')

@section('content')
    <h1>Редактировать цвет</h1>
    <form method="POST" action="{{ route('admin.colors.update', $color) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $color->name }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ route('admin.colors.index') }}">Назад</a>
@endsection