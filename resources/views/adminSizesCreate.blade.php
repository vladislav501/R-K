@extends('layouts.app')

@section('title', 'Создать размер')

@section('content')
    <h1>Создать размер</h1>
    <form method="POST" action="{{ route('admin.sizes.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" required>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Создать</button>
    </form>
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection