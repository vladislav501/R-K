@extends('layouts.app')

@section('content')
<div class="auth-register-container">
    <div class="auth-card">
        <h1 class="auth-title">Регистрация</h1>
        <form action="{{ route('register') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="first_name" class="form-label">Имя<span class="requiredMark">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-input" required>
                @error('first_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name" class="form-label">Фамилия<span class="requiredMark">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-input" required>
                @error('last_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="middle_name" class="form-label">Отчество (опционально)</label>
                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-input">
                @error('middle_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email<span class="requiredMark">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input" required>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="delivery_address" class="form-label">Адрес доставки (опционально)</label>
                <input type="text" name="delivery_address" id="delivery_address" value="{{ old('delivery_address') }}" class="form-input">
                @error('delivery_address')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Пароль<span class="requiredMark">*</span></label>
                <input type="password" name="password" id="password" class="form-input" required>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Подтверждение пароля<span class="requiredMark">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <div class="auth-footer">
            Уже есть аккаунт? <a href="{{ route('login') }}" class="auth-link">Войти</a>
        </div>
    </div>
        <div class="quote-section">
        <h2 class="quote-text">"Стиль — это язык, который говорит без слов"</h2>
    </div>
</div>
@endsection