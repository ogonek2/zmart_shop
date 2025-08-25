@extends('layouts.app')

@section('styles')
@endsection

@section('seo')
    <title>
        {{ $category->name }}
    </title>
@endsection

@section('content')
    <section>
        <div class="container">
            <div class="row">
                @include('includes.main.sidebar')
                <!-- Offcanvas Sidebar -->
                <div class="offcanvas offcanvas-start" tabindex="-1" id="categoryOffcanvas" aria-labelledby="categoryOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="categoryOffcanvasLabel">Категорії</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Закрити"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        <ul class="list-group">
                            @foreach (get_all_category() as $item)
                                <li class="list-group-item border-0 bg-white hover-effect-2">
                                    <a class="nav-link d-flex justify-content-between w-100"
                                    href="{{ route('catalog_category_page', $item->url) }}">
                                        <span>
                                            {{ $item->name }}
                                        </span>
                                        <span>
                                            <b>{{ $item->products()->count() }}</b>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Контент -->
                <main class="col-12 col-md-9">
                    <header class="py-4 d-flex align-items-center justify-content-between gap-4">
                        <!-- Бургер-кнопка -->
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoryOffcanvas" aria-controls="categoryOffcanvas">
                            <i class="fa-solid fa-list"></i>
                        </button>
                
                        <h3 class="mb-0">{{ $category->name }}</h3>
                    </header>
                    
                    <catalog-product-list :products='@json($products->items())'
                        :pagination='@json($paginationData)'>
                    </catalog-product-list>

                    {{-- Пагинация Laravel по ссылке --}}
                    <div class="mt-4">
                        {!! $products->links() !!}
                    </div>
                </main>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
