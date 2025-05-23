@extends('layouts.app')

@section('title', 'Редактировать тип одежды')

@section('content')
    <h1>Редактировать тип одежды</h1>
    <form method="POST" action="{{ route('admin.clothing_types.update', $clothingType) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="{{ $clothingType->name }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Сохранить</button>
    </form>
@endsection