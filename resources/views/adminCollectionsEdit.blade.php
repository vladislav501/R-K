@extends('layouts.app')

@section('title', 'Редактировать коллекцию')

@section('content')
    <h1>Редактировать коллекцию</h1>
    <form method="POST" action="{{ route('admin.collections.update', $collection) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $collection->name }}" required>
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="description">Описание:</label>
            <textarea name="description" id="description">{{ $collection->description }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Сохранить</button>
    </form>
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection