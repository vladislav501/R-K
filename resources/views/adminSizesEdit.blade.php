@extends('layouts.app')

@section('title', 'Редактировать размер')

@section('content')
    <h1>Редактировать размер</h1>
    <form method="POST" action="{{ route('admin.sizes.update', $size) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $size->name }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ route('admin.sizes.index') }}">Назад</a>
@endsection