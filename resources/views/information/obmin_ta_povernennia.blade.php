@extends('layouts.information')

@section('seo')
    <title>Обмін та повернення - ZMART</title>
    <meta name="description" content="Умови обміну та повернення товарів у магазині ZMART.">
@endsection

@section('info_title', 'Обмін та повернення')
@section('info_lead', '14 днів на повернення відповідно до законодавства України.')

@section('info_content')
    <div class="zinfo__section">
        <h2>Умови повернення</h2>
        <div class="zinfo__grid">
            <div class="zinfo__item">
                <h3>Стан товару</h3>
                <p>Повертається новий товар без слідів використання, зі збереженим виглядом, комплектацією та упаковкою.</p>
            </div>
            <div class="zinfo__item">
                <h3>Термін</h3>
                <p>Повернення можливе протягом 14 днів після отримання замовлення.</p>
            </div>
        </div>
    </div>

    <div class="zinfo__section">
        <h2>Як повернути</h2>
        <ol class="zinfo__steps">
            <li>
                <div>
                    <h3>Зв’яжіться з нами</h3>
                    <p>Напишіть на <a href="mailto:zmartcomua@gmail.com">zmartcomua@gmail.com</a> або зателефонуйте
                        <a href="tel:0730777572">+38 073 077 75 72</a> /
                        <a href="tel:0770777582">+38 077 077 75 82</a>.</p>
                </div>
            </li>
            <li>
                <div>
                    <h3>Підготуйте товар</h3>
                    <p>Упакуйте товар в оригінальну упаковку з усіма аксесуарами та документами.</p>
                </div>
            </li>
            <li>
                <div>
                    <h3>Відправте Новою Поштою</h3>
                    <p>Вартість зворотної доставки оплачує покупець, якщо інше не погоджено з менеджером.</p>
                </div>
            </li>
            <li>
                <div>
                    <h3>Отримайте кошти</h3>
                    <p>Після перевірки товару повертаємо оплату протягом 3–5 робочих днів.</p>
                </div>
            </li>
        </ol>
    </div>

    <div class="zinfo__section">
        <h2>Обмін</h2>
        <ul>
            <li>товар не підійшов за характеристиками;</li>
            <li>виявлено заводський брак;</li>
            <li>товар не відповідає опису на сайті.</li>
        </ul>
        <p>Обмін можливий протягом 14 днів за умови збереження товарного вигляду та упаковки. При обміні на дорожчий товар різницю доплачує покупець.</p>
        <div class="zinfo__actions">
            <a href="tel:0730777572" class="zinfo__btn zinfo__btn--primary">+38 073 077 75 72</a>
            <a href="mailto:zmartcomua@gmail.com" class="zinfo__btn">Написати на email</a>
        </div>
    </div>
@endsection
