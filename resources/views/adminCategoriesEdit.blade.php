@extends('layouts.app')

@section('title', 'Редактировать категорию')

@section('content')
    <h1>Редактировать категорию</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}> Активна
            </label>
        </div>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ route('admin.categories.index') }}">Назад</a>
@endsection