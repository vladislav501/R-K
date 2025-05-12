@extends('layouts.asset')
@section('content')
    <div class="content">
        <div class="authBlock">
            <h1 class="contentTitle">Логин</h1>
            <form action="{{ route('authentication') }}" method="post">
                @csrf
                <input name="email" type="email" placeholder="Почта">
                <input name="password" type="password" placeholder="Пароль">
                <button type="submit">Войти</button>
            </form>
            <p>Если у вас нет аккаунта, <a href="{{ route('register.index') }}">зарегистрируйтесь</a></p>    
        </div>
    </div>
@endsection