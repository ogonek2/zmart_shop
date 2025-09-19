@extends('layouts.app')

@section('styles')
    <style>
        .container-success {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        svg {
            width: 100%;
            height: 100%;
        }

        path {
            stroke-dasharray: 99.47578430175781;
            stroke-dashoffset: -99.47578430175781;
            fill: transparent;
        }

        svg.animate path {
            animation: 1.7s ease forwards draw;
            opacity: 1;
        }

        @keyframes draw {
            0% {
                opacity: 1;
                stroke-dashoffset: -99.47578430175781;
                fill: transparent;
                transform: translateY(0);
            }

            35% {
                stroke-dashoffset: 0;
                fill: transparent;
            }

            60% {
                fill: #3da35a;
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                fill: #3da35a;
                stroke-dashoffset: 0;
                opacity: 1;
            }
        }
    </style>
@endsection

@section('seo')
    <title>Сторінка подяки - Дякую за замовлення!</title>
@endsection

@section('content')
    <div class="vh-50 my-4 d-flex justify-content-center align-items-center">
        <div class="card col-md-12 bg-white shadow-md p-5 border-0">
            <div class="mb-4 text-center">
                <div class="container-success">
                    <svg viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M 18 32.34 l -8.34 -8.34 -2.83 2.83 11.17 11.17 24 -24 -2.83 -2.83 z" stroke="#3da35a"
                            fill="transparent" />
                    </svg>
                </div>
            </div>
            <div class="text-center">
                <h1>Дякую !</h1>
                <p>Ми отримали ваше замовлення і найблищим часом відправимо!</p>
                <a class="btn btn-outline-success" href="{{ route('welcome') }}">Повернутись на головну</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var container = $('.container-success');
        var checkmark = $('svg');
        var className = "animate";

        $(document).ready(function() {
            $(checkmark).addClass(className);
        })
    </script>
    @if(session('purchase_js'))
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({!! json_encode(session('purchase_js'), JSON_UNESCAPED_UNICODE) !!});
        </script>
    @endif
@endsection