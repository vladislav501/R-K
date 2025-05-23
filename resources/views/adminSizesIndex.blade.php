@extends('layouts.app')

@section('title', 'Управление размерами')

@section('content')
    <h1>Управление размерами</h1>
    <a href="{{ route('admin.sizes.create') }}">Добавить размер</a>
    @forelse($sizes as $size)
        <div>
            <p>{{ $size->name }}</p>
            <form action="{{ route('admin.sizes.destroy', $size) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Размеров нет.</p>
    @endforelse
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection