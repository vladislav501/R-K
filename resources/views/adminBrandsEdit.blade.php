@extends('layouts.app')

@section('title', 'Редактировать бренд')

@section('content')
    <h1>Редактировать бренд</h1>
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $brand->name }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ route('admin.brands.index') }}">Назад</a>
@endsection