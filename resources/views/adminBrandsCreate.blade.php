@extends('layouts.app')

@section('title', 'Создать бренд')

@section('content')
    <h1>Создать бренд</h1>
    <form method="POST" action="{{ route('admin.brands.store') }}">
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
@endsection@extends('layouts.app')

@section('title', 'Управление брендами')

@section('content')
    <h1>Управление брендами</h1>
    <a href="{{ route('admin.brands.create') }}">Добавить бренд</a>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Действия</th>
            </tr>@extends('layouts.app')

@section('title', 'Создать бренд')

@section('content')
    <h1>Создать бренд</h1>
    <form method="POST" action="{{ route('admin.brands.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name') <span>{{ $message }}</span> @enderror
        </div>
        <button type="submit">Создать</button>
    </form>
    <a href="{{ route('admin.brands.index') }}">Назад</a>
@endsection
        </thead>
        <tbody>
            @foreach($brands as $brand)
                <tr>
                    <td>{{ $brand->name }}</td>
                    <td>
                        <a href="{{ route('admin.brands.edit', $brand) }}">Редактировать</a>
                        <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.index') }}">Назад</a>
@endsection