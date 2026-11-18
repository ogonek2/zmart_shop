@extends('layouts.information')

@section('seo')
    <title>Про компанію - ZMART</title>
    <meta name="description" content="Дізнайтеся більше про ZMART — інтернет-магазин побутової техніки та господарських товарів.">
@endsection

@section('info_title', 'Про компанію')
@section('info_lead', 'Побутова техніка та господарські товари з доставкою по Україні та самовивозом з 7 км.')

@section('info_content')
    <div class="zinfo__section">
        <h2>Хто ми</h2>
        <p>
            <strong>ZMART</strong> — інтернет-магазин побутової техніки, електроніки та господарських товарів.
            Ми допомагаємо швидко знайти потрібний товар, оформити замовлення й отримати його зручним способом.
        </p>
    </div>

    <div class="zinfo__section">
        <h2>Що пропонуємо</h2>
        <div class="zinfo__grid">
            <div class="zinfo__item">
                <h3>Асортимент</h3>
                <p>Техніка для дому, кухні, аксесуари та господарські товари від перевірених брендів.</p>
            </div>
            <div class="zinfo__item">
                <h3>Доставка</h3>
                <p>Нова Пошта по Україні та самовивіз з магазину на 7 км в Одесі.</p>
            </div>
            <div class="zinfo__item">
                <h3>Оплата</h3>
                <p>Накладений платіж, оплата на картку або при самовивозі.</p>
            </div>
            <div class="zinfo__item">
                <h3>Підтримка</h3>
                <p>Консультація менеджера щодо наявності, характеристик і оформлення замовлення.</p>
            </div>
        </div>
    </div>

    <div class="zinfo__section">
        <h2>Контакти</h2>
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
        </div>
        <div class="zinfo__actions">
            <a href="{{ route('kontaktna_informatsiia') }}" class="zinfo__btn zinfo__btn--primary">Зв’язатися з нами</a>
            <a href="{{ route('catalog') }}" class="zinfo__btn">До каталогу</a>
        </div>
    </div>
@endsection
