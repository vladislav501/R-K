@extends('layouts.asset')
@section('content')
    <div class="content">
        <h1 class="contentTitle">Избранное</h1>
        <div class="favorites">
            @if($favorites->isEmpty())
                <div class="emptyBlock">
                    <h2>У вас нет избранных товаров</h2>
                    <p>Добавьте товары в избранное, чтобы не потерять.</p>
                    <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="emptyImage">
                    <a href="{{ route('products.index') }}" class="btn">Перейти в Каталог</a>
                </div>
            @else
                @foreach($favorites as $favorite)
                    @php
                        $product = \App\Models\Product::find($favorite->productId);
                    @endphp
                    @if($product) 
                        <div class="productsItem">
                            <div class="itemPhoto">
                                <a href="{{ route('product.show', $product->id) }}">
                                    @if($product->images)
                                        <img src="{{ Storage::url($product->images->previewImagePath) }}" alt="Preview Image" class="productItemImage">
                                    @else
                                        <img src="{{ asset('images/empty.svg') }}" alt="emptyImage" class="productItemImage">
                                    @endif
                                </a>
                            </div>
                            <div class="itemInfo">
                                <h1>
                                    <span>{{ $product->price }}</span>
                                    <span>byn</span>
                                </h1>
                                <span>{{ $product->shortTitle }}</span>
                            </div>
                            <div class="productsBtnsContainer">
 
                                <form action="{{ route('cart.add', $product->id) }}" method="post" class="productsCartBtnForm">
                                    @csrf
                                    <button class="productsCartBtn">
                                        <span>В корзину</span>
                                    </button>
                                </form>
                                <form action="{{ route('favorites.remove', $product->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="productsFavoriteBtn">
                                        <img src="{{ asset('images/favorite.svg') }}" alt="favoriteItemImage" class="favoriteItemImage">
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection