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

@section('content')
    <div class="container">
        <div class="row">
            @include('includes.main.sidebar')

            <!-- Контент -->
            <main class="col-12 col-md-9">
                <div class="row m-0 mt-4">
                    <h4 class="mb-3 h4 font-weight-bolder p-0"><b>Рекомендовані товари</b></h4>
                    <div class="swiper recomendedSwiper">
                        <div class="swiper-wrapper">
                            <div
                                class="swiper-slide card border-0 rounded-0 position-relative d-flex flex-column bg-white p-2 border-end border-bottom">
                                <a _ngcontent-rz-client-c1254070812="" rzrelnofollow="" apprzroute=""
                                    class="tile-image-host d-block w-full"
                                    href="https://rozetka.com.ua/ua/pop-mart-6931571071929/p515998349/"
                                    title="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                    rel="nofollow"><img _ngcontent-rz-client-c1254070812="" rzimage="" placeholder=""
                                        fill="" class="tile-image"
                                        alt="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                        loading="lazy" fetchpriority="auto" ng-img="true"
                                        src="https://content1.rozetka.com.ua/goods/images/preview/556320385.jpg"
                                        sizes="100vw"
                                        style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;"><!----></a>
                                <div class="card-body p-0 d-flex flex-column justify-content-between">
                                    <a href="#" class="nav-link truncated-text"
                                        style="font-size: 14px;">Іграшка-сюрприз
                                        Pop Mart Плюшевый брелок Mart Big into
                                        Energy Art The Monsters (6931571071929) </a>
                                </div>
                                <div
                                    class="card-footer p-0 mt-4 border-0 d-flex align-items-center justify-content-between bg-white">
                                    <b class="d-flex flex-column">
                                        <span style="font-size: 12px">799 грн</span>
                                        <small class="old-price-dr">1000 грн</small>
                                    </b>
                                    <button class="btn border border-secondary">
                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <button class="btn fs-5" style="position: absolute; top: 0; right: 0;" type="button"
                                    aria-label="Перемістити в список бажань">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <div class="card-label-duration">
                                    -35%
                                </div>
                            </div>
                            <div
                                class="swiper-slide card border-0 rounded-0 position-relative d-flex flex-column bg-white p-2 border-end border-bottom">
                                <a _ngcontent-rz-client-c1254070812="" rzrelnofollow="" apprzroute=""
                                    class="tile-image-host d-block w-full"
                                    href="https://rozetka.com.ua/ua/pop-mart-6931571071929/p515998349/"
                                    title="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                    rel="nofollow"><img _ngcontent-rz-client-c1254070812="" rzimage="" placeholder=""
                                        fill="" class="tile-image"
                                        alt="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                        loading="lazy" fetchpriority="auto" ng-img="true"
                                        src="https://content1.rozetka.com.ua/goods/images/preview/556320385.jpg"
                                        sizes="100vw"
                                        style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;"><!----></a>
                                <div class="card-body p-0 d-flex flex-column justify-content-between">
                                    <a href="#" class="nav-link truncated-text"
                                        style="font-size: 14px;">Іграшка-сюрприз
                                        Pop Mart Плюшевый брелок Mart Big into
                                        Energy Art The Monsters (6931571071929) </a>
                                </div>
                                <div
                                    class="card-footer p-0 mt-4 border-0 d-flex align-items-center justify-content-between bg-white">
                                    <b class="d-flex flex-column">
                                        <span style="font-size: 12px">799 грн</span>
                                    </b>
                                    <button class="btn border border-secondary">
                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <button class="btn fs-5" style="position: absolute; top: 0; right: 0;" type="button"
                                    aria-label="Перемістити в список бажань">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <div class="row m-0 mt-4 ">
                    <h4 class="mb-3 h4 font-weight-bolder p-0"><b>Інші товари</b></h4>
                    <div class="row p-0 m-0">
                        <div
                            class="card border-0 rounded-0 col-6 col-md-2 p-2 position-relative d-flex flex-column bg-white border-end border-bottom card-product-item">
                            <a class="p-3" href=""
                                title="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                rel="nofollow">
                                <img alt="Іграшка-сюрприз Pop Mart Плюшевый брелок Mart Big into Energy Art The Monsters (6931571071929)"
                                    loading="lazy" fetchpriority="auto" ng-img="true"
                                    src="https://content1.rozetka.com.ua/goods/images/preview/556320385.jpg"
                                    style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;"><!----></a>
                            <div class="card-body p-0 d-flex flex-column justify-content-between">
                                <a href="#" class="nav-link truncated-text" style="font-size: 14px;">Іграшка-сюрприз
                                    Pop Mart Плюшевый брелок Mart Big into
                                    Energy Art The Monsters (6931571071929) </a>
                            </div>
                            <div
                                class="card-footer p-0 mt-4 border-0 d-flex align-items-center justify-content-between bg-white">
                                <b style="font-size: 14px" class="d-flex flex-column">
                                    <small class="old-price-dr">1000 грн</small>
                                    <span style="font-size: 14px">799 грн</span>
                                </b>
                                <button class="btn border border-secondary">
                                    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                </button>
                            </div>
                            <button class="btn fs-5" style="position: absolute; top: 0; right: 0;" type="button"
                                aria-label="Перемістити в список бажань">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <div class="card-label-duration">
                                -35%
                            </div>
                        </div>
                        @foreach ($all_products as $product)
                            <div class="card border-0 rounded-0 col-6 col-md-2 p-2 position-relative d-flex flex-column bg-white border-end border-bottom card-product-item">
                                <a class="p-3" href=""
                                    title="{{ $product->name }}"
                                    rel="nofollow">
                                    <img alt="{{ $product->image_path }}"
                                        loading="lazy" fetchpriority="auto" ng-img="true"
                                        src="{{ $product->image_path }}"
                                        style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;"><!----></a>
                                <div class="card-body p-0 d-flex flex-column justify-content-between">
                                    <a href="#" class="nav-link truncated-text"
                                        style="font-size: 14px;">{{ $product->name }}</a>
                                </div>
                                <div
                                    class="card-footer p-0 mt-4 border-0 d-flex align-items-center justify-content-between bg-white">
                                    <b style="font-size: 14px" class="d-flex flex-column">
                                        <span style="font-size: 14px">{{ $product->price }}₴</span>
                                    </b>
                                    <button class="btn border border-secondary">
                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <button class="btn fs-5" style="position: absolute; top: 0; right: 0;" type="button"
                                    aria-label="Перемістити в список бажань">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
