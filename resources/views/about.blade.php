@extends('layouts.app')

@section('title', 'О нас')

@section('content')
<div class="about-page-wrapper">
    <div class="about-main-content">
        <h1>О нас</h1>
        <div class="about-section">
            <h2>Наша история</h2>
            <p>Мы — компания, основанная в 2020 году, с миссией предоставлять стильную и качественную одежду для всех. Наша команда стремится к тому, чтобы каждый клиент чувствовал себя уверенно и комфортно в наших товарах.</p>
        </div>
        <div class="about-section">
            <h2>Наша миссия</h2>
            <p>Продавать модную одежду, которая сочетает в себе качество, доступность и стиль.</p>
        </div>
        <div class="about-section">
            <h2>Свяжитесь с нами</h2>
            <p>Email: tvorovskyVlad@gmail.com</p>
            <p>Телефон: +375 (29) 123-45-67</p>
            <p>Адрес: ул. Советская, 123, Гродно, Беларусь</p>
        </div>
        <a href="{{ route('home') }}" class="about-back-link">Вернуться на главную</a>
    </div>
</div>
@endsection