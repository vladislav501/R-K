@extends('layouts.app')

@section('title', 'Политика конфиденциальности')

@section('content')
<div class="static-page">
    <h1>Политика конфиденциальности</h1>
    <p>Мы уважаем вашу конфиденциальность и обязуемся защищать ваши персональные данные.</p>
    <p>Собираемые данные включают имя, адрес электронной почты и информацию о заказах. Эти данные используются для обработки заказов и улучшения обслуживания.</p>
    <p>Для получения дополнительной информации свяжитесь с нами через <a href="{{ route('contact') }}">форму обратной связи</a>.</p>
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