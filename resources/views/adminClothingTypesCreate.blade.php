@extends('layouts.app')

@section('title', 'Создать тип одежды')

@section('content')
    <h1>Создать тип одежды</h1>
    <form method="POST" action="{{ route('admin.clothing_types.store') }}">
        @csrf
        <div>
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Создать</button>
    </form>
@endsection