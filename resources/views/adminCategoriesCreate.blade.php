@extends('layouts.app')

@section('title', 'Создать категорию')

@section('content')
    <h1>Создать категорию</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}> Активна
            </label>
        </div>
        <button type="submit">Создать</button>
    </form>
    <a href="{{ route('admin.categories.index') }}">Назад</a>
@endsection