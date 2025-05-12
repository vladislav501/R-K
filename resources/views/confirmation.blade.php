@extends('layouts.asset')
@section('content')
    <div class="order-confirmation">
        <h1 class="confirmation-title">Ваш заказ успешно отправлен!</h1>
        <p class="confirmation-message">Спасибо за покупку. Ваш заказ находится на подтверждении.</p>
        <a href="{{ route('cart.index') }}" class="back-to-cart">Вернуться в корзину</a>
        <br>
        <a href="{{ route('order.invoice.download', $preOrder->id) }}" class="btn download-invoice">Скачать чек</a>
    </div>
@endsection