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
    <title>Головна сторінка</title>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @include('includes.main.sidebar')
            <!-- Контент -->
            <main class="col-12 col-md-9">
                @include('includes.catalog.recomendet')
                <product-list></product-list>
            </main>
        </div>
    </div>
@endsection
