@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Бренды</h1>
        <a href="{{ route('home.index') }}">Назад</a>
        @foreach ($brands as $brand)
            <div><a href="{{ route('brand.show', $brand->id) }}">{{ $brand->name }}</div>
        @endforeach
    </div>
@endsection