@extends('layouts.asset')

@section('content')
    <div class="new-cart-content">
        <h1 class="new-cart-title">Корзина</h1>
        <div class="new-cart-container">
            <div class="new-cart-items">
                @if($carts->isEmpty())
                    <div class="new-empty-cart-block">
                        <h2>Корзина пуста</h2>
                        <p>Добавьте товары в корзину, чтобы оформить заказ.</p>
                        <h1>
                            <a href="{{ route('products.index') }}" class="new-catalog-button">Перейти в Каталог</a>
                        </h1>
                    </div>
                @else
                    @foreach($carts as $cart)
                        @php
                            $product = \App\Models\Product::find($cart->productId);
                        @endphp
                        @if($product)
                            <div class="new-cart-product-item">
                                <div class="new-product-image">
                                    <a href="{{ route('product.show', $product->id) }}">
                                        @if($product->images)
                                            <img src="{{ Storage::url($product->images->previewImagePath) }}" alt="Preview Image" class="new-product-thumbnail">
                                        @else
                                            <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="new-product-thumbnail">
                                        @endif
                                    </a>
                                </div>
                                <div class="new-product-details">
                                    <h2 class="new-product-price">
                                        <span>{{ $product->price }}</span>
                                        <span>byn</span>
                                    </h2>
                                    <span class="new-product-title">{{ $product->shortTitle }}</span>
                                </div>
                                <div class="new-product-actions">
                                    <form action="{{ route('cart.remove', $product->id) }}" method="post" class="new-remove-cart-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="new-remove-cart-button">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="new-cart-summary" style="text-align: center; margin-top: 20px;">
                <p class="new-total-amount">Общая сумма: <span>{{ $totalSum }} byn</span></p>
                <form action="{{ route('cart.checkout') }}" method="post" class="new-checkout-form">
                    @csrf
                    <button type="submit" class="new-checkout-button">За казать</button>
                </form>
            </div>
        </div>
    </div>
@endsection