@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
<div class="static-page">
    <h1>Контакты</h1>
    <p>Свяжитесь с нами:</p>
    <ul>
        <li><strong>Телефон:</strong> +7 (495) 123-45-67</li>
        <li><strong>Email:</strong> support@luxurycomfort.ru</li>
        <li><strong>Адрес офиса:</strong> Москва, ул. Тверская, д. 10</li>
    </ul>
    <p>Мы также доступны в социальных сетях. Подробности в футере.</p>
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