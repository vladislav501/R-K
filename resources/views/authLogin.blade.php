@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Вход</h1>
        <form action="{{ route('login') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" required>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" name="password" id="password" class="form-input" required>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
        <div class="auth-footer">
            Нет аккаунта? <a href="{{ route('register') }}" class="auth-link">Зарегистрироваться</a>
        </div>
    </div>
</div>
@endsection