@extends('layouts.app')

@section('title', 'Самовывоз')

@section('content')
<div class="static-page">
    <h1>Самовывоз</h1>
    <p>Вы можете забрать заказ из наших пунктов выдачи:</p>
    <ul>
        <li>Москва, ул. Тверская, д. 10 – ежедневно с 10:00 до 20:00.</li>
        <li>Санкт-Петербург, Невский пр., д. 50 – пн-пт с 11:00 до 19:00.</li>
    </ul>
    <p>Список всех магазинов доступен на странице <a href="{{ route('stores') }}">Адреса магазинов</a>.</p>
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