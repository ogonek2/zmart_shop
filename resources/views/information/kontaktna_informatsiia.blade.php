@extends('layouts.information')

@section('seo')
    <title>Контакти - ZMART</title>
    <meta name="description" content="Контактна інформація інтернет-магазину ZMART: телефони, email, адреса, графік роботи.">
@endsection

@section('info_title', 'Контакти')
@section('info_lead', 'Телефонуйте, пишіть або замовляйте зворотний дзвінок — відповімо в робочий час.')

@section('info_content')
    <div class="zinfo__section">
        <h2>Як зв’язатися</h2>
        <div class="zinfo__meta">
            <div class="zinfo__meta-row">
                <span>Телефон</span>
                <div>
                    <a href="tel:0730777572">+38 073 077 75 72</a><br>
                    <a href="tel:0770777582">+38 077 077 75 82</a>
                </div>
            </div>
            <div class="zinfo__meta-row">
                <span>Email</span>
                <a href="mailto:zmartcomua@gmail.com">zmartcomua@gmail.com</a>
            </div>
            <div class="zinfo__meta-row">
                <span>Адреса</span>
                <p>Одеса, промринок «7 км», вул. Фабрична, маг. №2523</p>
            </div>
            <div class="zinfo__meta-row">
                <span>Графік</span>
                <p>Пн–Чт, Сб–Нд: 06:00–14:00<br>П’ятниця — вихідний</p>
            </div>
        </div>
        <div class="zinfo__actions">
            <a href="tel:0730777572" class="zinfo__btn">Подзвонити</a>
        </div>
    </div>

    <div class="zinfo__section">
        <h2>Написати нам</h2>

        @if (session('contact_success'))
            <div class="zinfo__alert zinfo__alert--ok">{{ session('contact_success') }}</div>
        @elseif (session('contact_error'))
            <div class="zinfo__alert zinfo__alert--err">{{ session('contact_error') }}</div>
        @endif

        @if ($errors->any())
            <div class="zinfo__alert zinfo__alert--err">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="zinfo__form" method="POST" action="{{ route('contact_request') }}">
            @csrf
            <div class="zinfo__form-row">
                <div class="zinfo__field">
                    <label for="contact-name">Ім’я *</label>
                    <input id="contact-name" name="name" type="text" value="{{ old('name') }}" required placeholder="Ваше ім’я">
                    @error('name')<p class="is-error">{{ $message }}</p>@enderror
                </div>
                <div class="zinfo__field">
                    <label for="contact-phone">Телефон *</label>
                    <input id="contact-phone" name="phone" type="text" value="{{ old('phone') }}" required placeholder="+380...">
                    @error('phone')<p class="is-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="zinfo__field">
                <label for="contact-message">Повідомлення <span class="zinfo__muted">(необов’язково)</span></label>
                <textarea id="contact-message" name="message" rows="4" placeholder="Коротко опишіть питання">{{ old('message') }}</textarea>
                @error('message')<p class="is-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="zinfo__submit">Відправити</button>
        </form>
    </div>
@endsection
