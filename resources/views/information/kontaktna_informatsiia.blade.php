@extends('layouts.app')

@section('styles')
@endsection

@section('seo')
    <title>Магазин Zmart - Контактна інформація</title>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3">
                @include('includes.main.information_bar')
            </div>
            <div class="col-md-9">
                <nav class="breadcrumbs" itemscope="" itemtype="https://schema.org/BreadcrumbList">
                    <div class="breadcrumbs-i" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">
                        <span itemprop="item" content="https://uasignal.com.ua/kontaktna-informatsiia/"><span
                                itemprop="name">Контактна інформація</span></span>
                        <meta itemprop="position" content="2">
                    </div>
                </nav>
                <h1 class="main-h">Контактна інформація</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card rounded-0 border-0">
                            <div class="card-header bg-transparent border-0">
                                <h4>Телефон</h4>
                            </div>
                            <div class="card-body">
                                <a href="tel:0730777572 " class="nav-link p-0 text-body-secondary"><i class="fa fa-phone"
                                        aria-hidden="true"></i> +38 073-077-75-72</a>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <button class="btn btn-outline-success">
                                    Замовити дзвінок
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card rounded-0 border-0">
                            <div class="card-header bg-transparent border-0">
                                <h4>Адреса</h4>
                            </div>
                            <div class="card-body">
                                <a href="#" class="nav-link p-0 text-body-secondary"><i class="fa fa-location-arrow"
                                        aria-hidden="true"></i> Одесса, пром рынок "7 км",
                                    ул.Фабричная, маг. №2178</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card rounded-0 border-0">
                            <div class="card-header bg-transparent border-0">
                                <h4>Пошта</h4>
                            </div>
                            <div class="card-body">
                                <a href="mailto:zmartcomua@gmail.com" class="nav-link p-0 text-body-secondary"><i
                                        class="fas fa-envelope"></i>
                                    zmartcomua@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
