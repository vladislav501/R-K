@extends('layouts.app')

@section('title', 'Варианты доставки')

@section('content')
<div class="static-page">
    <h1>Варианты доставки</h1>
    <p>Мы предлагаем несколько способов доставки для вашего удобства:</p>
    <ul>
        <li><strong>Курьерская доставка:</strong> Доставка до двери в течение 1-3 рабочих дней. Стоимость от 300 ₽.</li>
        <li><strong>Почта России:</strong> Доставка в почтовое отделение. Срок 5-10 дней. Стоимость от 200 ₽.</li>
        <li><strong>Экспресс-доставка:</strong> Доставка в тот же день (для Москвы и Санкт-Петербурга). Стоимость от 500 ₽.</li>
    </ul>
    <p>Для получения подробной информации свяжитесь с нами через <a href="{{ route('contact') }}">форму обратной связи</a>.</p>
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