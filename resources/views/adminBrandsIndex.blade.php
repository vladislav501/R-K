@extends('layouts.app')

@section('title', 'Управление брендами')

@section('content')
    <h1>Управление брендами</h1>
    <a href="{{ route('admin.brands.create') }}">Добавить бренд</a>
    @forelse($brands as $brand)
        <div>
            <p>{{ $brand->name }}</p>
            <a href="{{ route('admin.brands.edit', $brand) }}">Редактировать</a>
            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Брендов нет.</p>
    @endforelse
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection