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

                <!-- Контент -->
                <main class="col-12 col-md-9">
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
