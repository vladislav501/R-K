@extends('layouts.app')

@section('title', 'Управление типами одежды')

@section('content')
    <h1>Управление типами одежды</h1>
    <a href="{{ route('admin.clothing_types.create') }}">Добавить тип</a>
    @forelse($clothingTypes as $clothingType)
        <div class="clothing-type">
            <p>{{ $clothingType->name }}</p>
            <a href="{{ route('admin.clothing_types.edit', $clothingType) }}">Редактировать</a>
            <form action="{{ route('admin.clothing_types.destroy', $clothingType) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @empty
        <p>Типов одежды нет.</p>
    @endforelse
@endsection