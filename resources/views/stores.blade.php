@extends('layouts.app')

@section('title', 'Адреса пунктов выдачи')

@section('content')
<div class="static-page">
    <h1>Адреса пунктов выдачи</h1>
    <p>Посетите наши пункты выдачи:</p>
    @if(\App\Models\PickupPoint::count() > 0)
        <ul>
            @foreach(\App\Models\PickupPoint::all() as $pickupPoint)
                <li><b>{{ $pickupPoint->name }}</b> - {{ $pickupPoint->address }} – {{ $pickupPoint->working_hours ?? 'ежедневно с 10:00 до 20:00' }}.</li>
            @endforeach
        </ul>
    @else
        <p>Пункты выдачи отсутствуют.</p>
    @endif
</div>
@endsection

@section('styles')
<style>
.static-page {
    max-width: 70%;
    margin: 2rem auto;
    font-family: "Outfit", sans-serif;
    color: #333;
    line-height: 1.6;
}
.static-page h1 {
    color: #0fa3b1;
    font-weight: 600;
    margin-bottom: 1rem;
}
.static-page p, .static-page ul {
    font-size: 1rem;
    margin-bottom: 1rem;
}
.static-page ul li {
    margin-bottom: 0.5rem;
}
.static-page a {
    color: #f7a072;
    text-decoration: none;
}
.static-page a:hover {
    text-decoration: underline;
}
</style>
@endsection