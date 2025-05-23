@extends('layouts.app')

@section('title', 'Управление коллекциями')

@section('content')
    <h1>Управление коллекциями</h1>
    <a href="{{ route('admin.collections.create') }}">Добавить коллекцию</a>
    @forelse($collections as $collection)
        <div>
            <p>{{ $collection->name }}</p>
            <p>{{ $collection->description }}</p>
            <a href="{{ route('admin.collections.edit', $collection) }}">Редактировать</a>
            <form action="{{ route('admin.collections.destroy', $collection) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Коллекций нет.</p>
    @endforelse
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection