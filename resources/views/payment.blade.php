@extends('layouts.app')

@section('title', 'Способы оплаты')

@section('content')
<div class="static-page">
    <h1>Способы оплаты</h1>
    <p>Мы принимаем следующие способы оплаты:</p>
    <ul>
        <li><strong>Банковская карта:</strong> Visa, MasterCard, Мир. Оплата онлайн через защищённый шлюз.</li>
        <li><strong>Наличные:</strong> Оплата при получении (только для курьерской доставки).</li>
        <li><strong>Электронные кошельки:</strong> Яндекс.Деньги, WebMoney, Qiwi.</li>
    </ul>
    <p>Все платежи защищены современными технологиями шифрования. Подробности на странице <a href="{{ route('privacy') }}">Политики конфиденциальности</a>.</p>
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