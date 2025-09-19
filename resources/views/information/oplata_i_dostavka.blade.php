@extends('layouts.app')

@section('styles')
    <style>
        .privacy-container {
            background: #fff;
            padding: 40px;
            margin-top: 40px;
            margin-bottom: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h1,
        h2 {
            color: #1C214B;
        }

        .text-muted small {
            font-size: 0.9rem;
        }

        ul,
        ol {
            list-style-position: inside;
        }
    </style>
@endsection

@section('seo')
    <title>Магазин Zmart - Оплата і доставка</title>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3">
                @include('includes.main.information_bar')
            </div>
            <div class="col-md-9">
                <nav class="breadcrumbs" itemscope="" itemtype="https://schema.org/BreadcrumbList">
                    <div class="breadcrumbs-i" itemprop="itemListElement" itemscope=""
                        itemtype="https://schema.org/ListItem">
                        <span itemprop="item" content="https://uasignal.com.ua/oplata-i-dostavka/"><span
                                itemprop="name">Оплата
                                і доставка</span></span>
                        <meta itemprop="position" content="2">
                    </div>
                </nav>
                <h1 class="main-h">Оплата і доставка</h1>
                <div class="page-content">
                    <div class="article-text __fullWidth text">
                        <h1><span style="font-size:22px;"><strong>Способи оплати замовлень:</strong></span><br>
                            <span style="font-size:18px;"><strong>- Банківською карткою Visa або Mastercard на
                                    сайті</strong></span>
                        </h1>

                        <p style="margin-left: 40px;">Оплата замовлення здійснюється безпосередньо на сайті банківською
                            карткою
                            Visa або Mastercard.<br>
                            Відправка товару здійснюється після підтвердження платежу.</p>

                        <p><br>
                            <span style="font-size:18px;"><strong>- Оплата за реквізитами</strong></span>
                        </p>

                        <p style="margin-left: 40px;"><br>
                            Оплату замовлення за реквізитами можна здійснити у відділенні будь-якого банку, зі свого
                            поточного
                            рахунку або з поточного рахунку компанії.<br>
                            У випадку безготівкової оплати відправка замовлення відбувається після надходження коштів на наш
                            рахунок.</p>

                        <h3><br>
                            - Післяплата у відділенні Нової пошти</h3>

                        <p style="margin-left: 40px;"><br>
                            Замовлення можна оплатити післі того, як Ви отримали і оглянули товар на Новій пошті.<br>
                            Зверніть увагу! Поштовий оператор може стягувати додаткові кошти за переказ грошей. Вартість
                            переказу заздалегідь уточнюйте в оператора.</p>

                        <h2><br>
                            <strong><span style="font-size:22px;">Правила відправки і доставки:</span></strong>
                        </h2>

                        <p>Відправки замовлень, здійснених<u><strong> і підтверджених!</strong></u> до 16.00 з понеділка по
                            п'ятницю відправляються в той же день.<br>
                            Замовлення, які були прийняті в п'ятницю після 16.00, а також, у вихідні дні, відправляються у
                            найближчий робочий день.</p>

                        <p><br>
                            Терміни доставки в середньому займають 1-3 дні і залежать від місця призначення замовлення.<br>
                            Більш детальну інформацію про терміни доставки можна отримати в поштового оператора Нова пошта.
                        </p>

                        <p><span style="font-size:16px;"><strong>Підтвердженими</strong></span> вважаються замовлення:&nbsp;
                        </p>

                        <ul>
                            <li>підтверджені покупцем після телефонного дзвінка менеджера або в переписці у будь-якому
                                зручному
                                месенджері,</li>
                            <li>при виборі безготівкового розрахунку оплата за замовлення підтверджена та кошти надійшли на
                                рахунок продавця.<br>
                                &nbsp;</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
