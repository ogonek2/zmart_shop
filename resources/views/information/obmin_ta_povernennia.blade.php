@extends('layouts.app')

@section('styles')
@endsection

@section('seo')
    <title>Магазин Zmart - Обмін та повернення</title>
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
                        <span itemprop="item" content="https://uasignal.com.ua/obmin-ta-povernennia/"><span
                                itemprop="name">Обмін та повернення</span></span>
                        <meta itemprop="position" content="2">
                    </div>
                </nav>
                <h1 class="main-h">Обмін та повернення</h1>
                <div class="page-content">
                    <div class="article-text __fullWidth text">
                        <p>Поверненню підлягає новий&nbsp;товар без слідів експлуатації.</p>

                        <p>Повернення товару відбувається протягом 14 днів після покупки, відповідно до чинного
                            законодавства
                            України.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
