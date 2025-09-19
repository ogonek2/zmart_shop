@extends('layouts.app')

@section('styles')
    <style>
        .section-title {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('seo')
    <title>Магазин Zmart - Про нас</title>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3">
                @include('includes.main.information_bar')
            </div>
            <div class="col-md-9">
                <div class="text-left mb-5">
                    <h1 class="section-title">Про <span class="highlight">нас</span></h1>
                    <p class="lead">Ваш надійний інтернет-магазин електроніки та побутової техніки</p>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <h4 class="text-primary">Наша місія</h4>
                        <p>
                            Ми створили <strong>Zmart</strong>, щоб зробити якісну техніку доступною кожному українцю. Наша
                            мета — забезпечити вас сучасними товарами за розумною ціною з максимально зручним сервісом.
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="text-primary mt-4">Що ми пропонуємо</h4>
                        <ul>
                            <li>Побутова техніка для дому та кухні</li>
                            <li>Аксесуари та електроніка</li>
                            <li>Професійна консультація та підтримка</li>
                            <li>Швидка доставка по Україні</li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-6">
                        <h4 class="text-primary">Чому обирають Zmart</h4>
                        <ul>
                            <li>✅ Великий вибір перевірених товарів</li>
                            <li>✅ Конкурентні ціни та акції</li>
                            <li>✅ Позитивні відгуки покупців</li>
                            <li>✅ Просте та швидке оформлення замовлень</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-primary">Контакти</h4>
                        <p><strong>Email:</strong> <a href="mailto:zmartcomua@gmail.com"
                        class="p-0 text-body-secondary"> zmartcomua@gmail.com</a></p>
                        <p><strong>Телефон:</strong> +38 073-077-75-72</p>
                        <p><strong>Адреса:</strong> Одесса, пром рынок "7 км",
                        ул.Фабричная, маг. №2178</p>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted">Дякуємо, що обираєте <strong>Zmart</strong>. Ми працюємо для вас 💙</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
