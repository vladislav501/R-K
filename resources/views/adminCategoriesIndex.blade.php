@extends('layouts.app')

@section('title', 'Управление категориями')

@section('content')
    <h1>Управление категориями</h1>
    @forelse($categories as $category)
        <div>
            <p>{{ $category->name }}</p>
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <label>
                    <input type="checkbox" name="is_active" {{ $category->is_active ? 'checked' : '' }}>
                    Активна
                </label>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    @empty
        <p>Категорий нет.</p>
    @endforelse
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection