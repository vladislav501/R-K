<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>P&K</title>
</head>
<body>
    <header>
        <div class="headerContainer">
            <div class="topHeader">
                <div class="topheaderLeft">
                    <div class="burgerContainer">
                        <a href="{{ route('products.index') }}">
                            <span class="burgerText">КАТАЛОГ</span>
                        </a>
                    </div>
                    <div class="phoneModal">
                        <span>+375 (69) 696-9-696</span>
                    </div>
                </div>

                <h1><a href="{{ route('home.index') }}" class="logo">P&K</a></h1>
                
                <div class="topheaderControls">
                    @if (Auth::check() && Auth::user()->name === 'Admin')
                        <div class="adminControl">
                            <button class="controlButton">
                                <a href="{{ route('admin.index') }}">
                                    <span class="controlButtonContainer">
                                        <img src="{{ asset('images/admin.svg') }}" alt="admin" class="controlImage">
                                        <span>Администратор</span>
                                    </span>
                                </a>
                            </button>
                        </div> 
                    @endif     
                    <div class="searchControl">
                        <button class="controlButton">
                            <span class="controlButtonContainer">
                                <img src="{{ asset('images/search.svg') }}" alt="search" class="controlImage">
                                <span>Поиск</span>
                            </span>
                        </button>
                    </div>  
                    @auth
                        <div class="loginControl">
                            <button class="controlButton">
                                <a href="{{ route('logout') }}">
                                    <span class="controlButtonContainer">
                                        <img src="{{ asset('images/door.svg') }}" alt="login" class="controlImage">
                                        <span>Выйти</span>
                                    </span>
                                </a>
                            </button>
                        </div> 
                        @else
                            <div class="loginControl">
                                <button class="controlButton">
                                    <a href="{{ route('login.index') }}">
                                        <span class="controlButtonContainer">
                                            <img src="{{ asset('images/profile.svg') }}" alt="login" class="controlImage">
                                            <span>Войти</span>
                                        </span>
                                    </a>
                                </button>
                            </div> 
                    @endauth              
                    <div class="favoriteControl">
                        <button class="controlButton">
                            <a href="{{ route('favorite.index') }}">
                                <span class="controlButtonContainer">
                                    <img src="{{ asset('images/favorite.svg') }}" alt="favorite" class="controlImage">
                                    <span>Избранное</span>
                                </span>
                            </a>
                        </button>
                    </div>                
                    <div class="cartControl">
                        <button class="controlButton">
                            <a href="{{ route('cart.index') }}">
                                <span class="controlButtonContainer">
                                    <img src="{{ asset('images/cart-broken.svg') }}" alt="cart" class="controlImage">
                                    <span>Корзина</span>
                                </span>
                            </a>
                        </button>
                    </div>                              
                </div>
            </div>
            
            <div class="downHeader">
                <div class="navBar">
                    <div class="saleNavItem">
                        <a href="{{ route('sale.index') }}">
                            <span>🎈 Акция</span>
                        </a>
                    </div>
                    <div class="navItem">
                        <a href="{{ route('womans.index') }}">
                            <span>Женщинам</span>
                        </a>
                    </div>
                    <div class="navItem">
                        <a href="{{ route('mans.index') }}">
                            <span>Мужчинам</span>
                        </a>
                    </div>
                    <div class="navItem">
                        <a href="{{ route('accessory.index') }}">
                            <span>Аксессуары</span>
                        </a>
                    </div>
                    <div class="navItem">
                        <a href="{{ route('shoes.index') }}">
                            <span>Обувь</span>
                        </a>
                    </div>
                    <div class="navItem">
                        <a href="{{ route('kids.index') }}">
                            <span>Детям</span>
                        </a>
                    </div>
                    {{-- <div class="navItem">
                        <a href="{{ route('brands.index') }}">
                            <span>Бренды</span>
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </header>

    <div class="contentContainer">
        @yield('content')
    </div>

    <footer>
        <hr class="footerHr">

        <div class="footerContainer">
            <div class="topFooter">
                <div class="popularCategories">
                    <h2 class="footerLabel">Популярные категории</h2>
                    <div class="categories">
                        <ul class="categoriesList">
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Новости</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Акции</span>
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="customer">
                    <h2 class="footerLabel">Покупателям</h2>
                    <div class="infoForCustomer">
                        <ul class="infoForCustomerList">
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Доставка и оплата</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Доставка и оплата</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Возврат товара</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Правила обращение о нарушениях прав потребителей</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Подарочные сертификаты P&K</span>
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="information">
                    <h2 class="footerLabel">Информация</h2>
                    <div class="infoLinks">
                        <ul class="infoLinksList">
                            <li>
                                <span>
                                    <a href="#">
                                        <span>О компании</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Наши контакты</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Адреса магазинов</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Облигации</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Публичная оферта</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Политика обработки персональных данных</span>
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span>
                                    <a href="#">
                                        <span>Согласие на обработку персональных данных</span>
                                    </a>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="socialMedia">
                    <h2 class="footerLabel">
                        <span class="number">
                            <img src="{{ asset('images/phone.svg') }}" alt="phone" class="phoneImage">
                            <span>+375 (69) 6969696</span>
                        </span>
                    </h2>
                    <pre class="numberText">
                        Время работы: пн-вс с 09:00 до 21:00,
                        Заказы через корзину круглосуточно
                    </pre>
                    <div class="subscribeContainer">
                        <h3 class="subscribeH3">Получайте уведомления об акциях и скидках:</h3>
                        <div class="emailInputContainer">
                            <input class="emailInput" placeholder="Ваш email">
<?php //TODO отправление почты в таблицу для рассылки, обновление страницы ?>
                            <button type=>
                                <img src="{{ asset('images/right.svg') }}" alt="right" class="rightArrowImage">
                            </button>
                        </div>
                        <div class="media">
                            <ul class="mediaList">
                                <li>
                                    <span>
                                        <a href="#">
                                            <img src="{{ asset('images/telegram.svg') }}" alt="telegram" class="mediaTelegramImage">
                                        </a>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <a href="#">
                                            <img src="{{ asset('images/instagram.svg') }}" alt="instagram" class="mediaInstagramImage">
                                        </a>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <a href="#">
                                            <img src="{{ asset('images/whatsapp.svg') }}" alt="telegram" class="mediaWhatappImage">
                                        </a>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <a href="#">
                                            <img src="{{ asset('images/facebook.svg') }}" alt="facebook" class="mediaFacebookImage">
                                        </a>
                                    </span>
                                </li>
                                <li>
                                    <span>
                                        <a href="#">
                                            <img src="{{ asset('images/pinterest.svg') }}" alt="pinterest" class="mediaPinterestImage">
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="downFooter">
                <div class="shopInfo">
                    <pre class="shopInfoText"> 
                        Интернет-магазин одежды, обуви и аксессуаров мировых брендов. Бесплатная доставка с примеркой по всей Беларуси*. Самовывоз из фирменных салонов сети. 
                        Быстрая доставка в Россию.
                        *Подробнее на странице «<a href="#"><span>Доставка и оплата</span></a>»
                    </pre>

                </div>

                <div class="payment">
                    <ul class="paymentList">
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/visa.svg') }}" alt="visa" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/verified_by_visa.svg') }}" alt="verified_by_visa" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/mastercard.svg') }}" alt="mastercard" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/mastercard_securedcode.svg') }}" alt="mastercard_securedcode" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/belcard.svg') }}" alt="belcard" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/belcard_parol.svg') }}" alt="belcard_parol" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/pay.svg') }}" alt="pay" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/apple_pay.svg') }}" alt="apple_pay" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/google_pay.svg') }}" alt="google_pay" class="paymentImage">
                            </span>
                        </li>
                        <li>
                            <span class="paymentItem">
                                <img src="{{ asset('images/bepaid.svg') }}" alt="bepaid" class="paymentImage">
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>