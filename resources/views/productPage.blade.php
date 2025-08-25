@extends('layouts.app')

@section('styles')
    <style>
        .swiper-slide {
            width: 150px !important;
            /* Принудительно устанавливает ширину */
        }

        .truncated-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Ограничение в 2 строки */
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: normal;
        }
    </style>
@endsection

@section('seo')
    <title>
        {{ $product->name }} - Деталі про товар. Замовити онлайн {{ $product->categories->first()?->name }}
    </title>
@endsection

@section('content')
    @php
        if (!function_exists('cleanMsWordHtml')) {
            function cleanMsWordHtml($html)
            {
                // Удаление мусорных классов Word
                $html = preg_replace('/class="Mso[^"]*"/i', '', $html);

                // Удаление атрибутов с mso-
                $html = preg_replace('/\s*mso-[^=]+="[^"]*"/i', '', $html);

                // Удаление стилей с mso-
                $html = preg_replace('/mso-[^:;"]+:[^;""]+;?/i', '', $html);

                // Удаление сломанных атрибутов вроде arial",sans-serif
        $html = preg_replace('/\s+[^\s=]+",[^=;>]+;?=""?/i', '', $html);

        // Удаление пустых span'ов и некорректных атрибутов
                $html = preg_replace('/<span[^>]*>\s*<\/span>/i', '', $html);

                // Удаление двойных кавычек внутри style
                $html = preg_replace_callback(
                    '/style="[^"]*"/i',
                    function ($matches) {
                        return preg_replace('/"([^"]*:[^";]*)"([^"]*:[^"]*)"/', '$1;$2', $matches[0]);
                    },
                    $html,
                );

                return $html;
            }
        }
    @endphp
    <section class="py-2">
        <div class="container">
            <div class="row">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                            type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Усе про
                            товар</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-black" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                            type="button" role="tab" aria-controls="profile-tab-pane"
                            aria-selected="false">Характеристики</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-black" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane"
                            type="button" role="tab" aria-controls="contact-tab-pane"
                            aria-selected="false">Відгуки</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                        tabindex="0">
                        <div class="row gx-5">
                            <aside class="col-lg-6">
                                @if (empty($product->image_path))
                                    <div class="d-flex bg-light align-items-center flex-column justify-content-center"
                                        style="width: 80%; aspect-ratio: 1 / 1; object-fit: contain; margin: auto;">
                                        <small class="text-secondary"><i class="fas fa-image"></i></small>
                                    </div>
                                    <div class="d-flex justify-content-start mb-3 product-image-thumbs mt-2">
                                        <div data-fslightbox="mygalley"
                                            class="border mx-1 rounded-2 product-image-thumb active bg-light d-flex bg-light align-items-center flex-column justify-content-center"
                                            style="width: 60px; height: 60px;">
                                            <small class="text-secondary"><i class="fas fa-image"></i></small>
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-0 mb-1 d-flex justify-content-center">
                                        <img style="width: 80%; aspect-ratio: 1 / 1; object-fit: contain; margin: auto;"
                                            class="rounded-4 fit product-image" src="{{ $product->image_path }}" />
                                    </div>
                                    <div class="d-flex justify-content-start mb-3 product-image-thumbs flex-wrap gap-1">
                                        @foreach ($images as $item)
                                            <div data-fslightbox="mygalley"
                                                class="border rounded-2 product-image-thumb">
                                                <img width="60" height="60" class="rounded-2"
                                                    src="{{ $item->src }}" />
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <!-- thumbs-wrap.// -->
                                <!-- gallery-wrap .end// -->
                            </aside>
                            <main class="col-lg-6 py-4">
                                <div class="ps-lg-3">
                                    <h4 class="title text-dark">
                                        {{ $product->name }}
                                    </h4>
                                    {{-- <div class="d-flex flex-row my-3">
                                        <div class="text-warning mb-1 me-2">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <span class="ms-1">
                                                4.5
                                            </span>
                                        </div>
                                        <span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1"></i>154 orders</span>
                                        <span class="text-success ms-2">In stock</span>
                                    </div> --}}
                                    @php
                                        $finalPrice =
                                            $product->discount > 0
                                                ? $product->price - ($product->price * $product->discount) / 100
                                                : $product->price;
                                    @endphp

                                    <p>{{ strip_tags($product->description) }}</p>
                                    <div class="my-3">
                                        @if ($product->discount > 0)
                                            <div class="mb-4">
                                                <b class="p-2 bg-danger text-white">
                                                    -{{ $product->discount }}%
                                                </b>
                                            </div>
                                            <div>
                                                <span class="h5">{{ $finalPrice }} ₴</span>
                                                <small class="text-danger ms-2">
                                                    <del>{{ $product->price }} ₴</del>
                                                </small>
                                            </div>
                                        @else
                                            <span class="h5">{{ $product->price }} ₴</span>
                                        @endif
                                    </div>

                                    <cart-button id="{{ $product->id }}" name="{{ $product->name }}"
                                        price="{{ $finalPrice }}" image="{{ $product->image_path }}" articule="{{ $product->articule }}"></cart-button>

                                    {{-- <a href="#" class="btn btn-outline-success shadow-0 ms-2"> Замовити зараз </a> --}}
                                </div>
                                <div class="card mt-4 p-3 border-0 rounded-0 bg-light">
                                    <h4 class="title text-dark">
                                        Доставка
                                    </h4>
                                    <div class="d-flex flex-column mt-4 gap-3">
                                        <div class="d-flex align-items-center">
                                            <svg version="1.1" id="Шар_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                viewBox="0 0 755 755.1" style="enable-background:new 0 0 755 755.1;"
                                                xml:space="preserve" width="20px" height="20px">
                                                <path fill="#DA291C" class="st0"
                                                    d="M443.9,612.8v-157H310.7v157H208.9l123.4,123.7c24.8,24.8,65,24.8,89.9,0l123.7-123.7L443.9,612.8L443.9,612.8z   M142.3,546.2V208.9L18.6,332.4c-24.8,24.8-24.8,65,0,89.9L142.3,546.2z M310.7,142.1v157h133.2v-157h102L422.2,18.6  c-24.8-24.8-65-24.8-89.9,0L208.9,142.1H310.7z M736,332.4L612.5,208.9v337l123.7-123.7c24.8-24.6,25.1-64.8,0.8-89.6  c0,0,0,0-0.3-0.3c0,0,0,0-0.5,0H736z" />
                                            </svg>
                                            <span class="ms-2">
                                                Нова Пошта
                                            </span>
                                            <b class="ms-auto">Відправимо завтра</b>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="20" viewBox="0 0 14 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_1694_20)">
                                                    <path opacity="0.999" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M7.04081 -0.00561523C7.31303 -0.00561523 7.58526 -0.00561523 7.85747 -0.00561523C9.85277 0.157498 11.4647 1.00848 12.6933 2.54733C13.4716 3.58099 13.9052 4.74108 13.9941 6.02758C13.9941 6.27944 13.9941 6.53131 13.9941 6.78317C13.9118 7.974 13.5288 9.06157 12.845 10.0459C12.7774 10.1389 12.7056 10.2285 12.6291 10.3149C12.5775 10.3409 12.523 10.3486 12.4658 10.3378C11.4581 10.1145 10.4509 9.88932 9.44414 9.66239C9.38724 9.61302 9.38336 9.55959 9.43247 9.50211C10.2497 8.98982 10.7922 8.2743 11.06 7.35558C11.3694 6.09368 11.1011 4.96794 10.255 3.97836C9.46012 3.13427 8.47429 2.72405 7.29747 2.74768C6.08785 2.83061 5.12535 3.34769 4.40997 4.29891C3.6987 5.3528 3.55093 6.47855 3.96664 7.67612C4.4303 8.81665 5.26058 9.56651 6.45747 9.9257C8.75539 10.4388 11.0537 10.9502 13.3525 11.4598C13.488 11.4911 13.5289 11.5693 13.475 11.6944C11.5065 14.4388 9.54265 17.1864 7.58331 19.9371C7.56038 19.9606 7.5351 19.9797 7.50747 19.9944C7.47636 19.9944 7.44526 19.9944 7.41414 19.9944C7.38465 19.9828 7.35937 19.9638 7.33831 19.9371C5.35007 17.1456 3.35896 14.356 1.36497 11.5685C0.54533 10.4169 0.0883855 9.13854 -0.00585938 7.73337C-0.00585938 7.45861 -0.00585938 7.18385 -0.00585938 6.9091C0.150986 4.82493 1.02599 3.09815 2.61914 1.72879C3.89743 0.682223 5.37133 0.104089 7.04081 -0.00561523Z"
                                                        fill="#FABC25" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1694_20">
                                                        <rect width="14" height="20" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <span class="ms-2">
                                                Укрпошта
                                            </span>
                                            <b class="ms-auto">Відправимо завтра</b>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_1695_3)">
                                                    <path opacity="0.989" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.8855 -0.052002C7.7605 -0.052002 9.6355 -0.052002 11.5105 -0.052002C13.359 3.25569 15.1646 6.58902 16.9272 9.948C15.1221 13.2803 13.3165 16.6136 11.5105 19.948C9.6355 19.948 7.7605 19.948 5.8855 19.948C7.69153 16.6136 9.49708 13.2803 11.3022 9.948C9.53956 6.58902 7.73401 3.25569 5.8855 -0.052002Z"
                                                        fill="#E50032" />
                                                    <path opacity="0.972" fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M2.96875 7.13554C4.46342 7.10089 5.95647 7.13561 7.44792 7.2397C7.93403 8.14248 8.42014 9.04526 8.90625 9.94804C8.42014 10.8508 7.93403 11.7536 7.44792 12.6564C5.95647 12.7604 4.46342 12.7952 2.96875 12.7605C2.96875 10.8855 2.96875 9.01054 2.96875 7.13554Z"
                                                        fill="#0060AE" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_1695_3">
                                                        <rect width="20" height="20" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <span class="ms-2">
                                                Meest Express
                                            </span>
                                            <b class="ms-auto">Відправимо завтра</b>
                                        </div>
                                        <p class="mt-2 text-secondary">
                                            *Оплата за доставку, відбувається відповідно тарифам обраної служби доставки.
                                        </p>
                                    </div>
                                </div>
                                <div class="card mt-2 p-3 border-0 rounded-0 bg-light">
                                    <h4 class="title text-dark">
                                        Оплата
                                    </h4>
                                    <ol class="p-0" style="list-style-position: inside;">
                                        <li>Предоплата на карту банку.</li>
                                        <li>Накладним платежем, при отриманні замовлення в службі доставки.</li>
                                        <li>Готівкою на нашому складі.</li>
                                    </ol>
                                </div>
                            </main>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                        tabindex="0">
                        <div class="row py-2">
                            <ul class="col-md-12 py-2">
                                @foreach ($product->package as $item)
                                    <li class="d-flex justify-content-between align-items-center w-100 py-2 border-bottom">
                                        <span>
                                            {{ $item->name }}
                                        </span>
                                        <span>
                                            {{ $item->value }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row py-2">
                            <div>
                                {!! strip_tags($product->complectation, '<p><br><ul><ol><li><strong><em><b><i><a>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab"
                        tabindex="0">...</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.product-image-thumb').on('click', function() {
                var $image_element = $(this).find('img')
                $('.product-image').prop('src', $image_element.attr('src'))
                $('.product-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })
        })
    </script>
@endsection
