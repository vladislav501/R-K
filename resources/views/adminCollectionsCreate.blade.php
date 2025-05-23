@extends('layouts.app')

@section('title', 'Создать коллекцию')

@section('content')
    <h1>Создать коллекцию</h1>
    <form method="POST" action="{{ route('admin.collections.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" required>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="description">Описание:</label>
            <textarea name="description" id="description"></textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Создать</button>
    </form>
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection