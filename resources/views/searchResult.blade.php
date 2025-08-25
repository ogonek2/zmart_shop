@extends('layouts.app') {{-- или ваш основной макет --}}

@section('styles')
@endsection

@section('seo')
    <title>
        Zmart - Результати пошуку: "{{ $query }}" | Пошук
    </title>
@endsection

@section('content')
    <section>
        <div class="container">
            <div class="row">
                @include('includes.main.sidebar')

                <!-- Контент -->
                <main class="col-12 col-md-9">
                    <div class="block-head mt-4">
                        <h4>Результати пошуку «<strong class="text-primary">{{ $originalQuery }}</strong>»</h4>
                        @if (!empty($suggestions))
                            <div class="d-flex align-items-center">
                                <h5>Може, ви мали на увазі: @foreach ($suggestions as $suggestion)
                                        <a class="text-black" style="text-decoration: unset" href="{{ url('/search?q=' . urlencode($suggestion)) }}">«<strong class="text-info">{{ $suggestion }}</strong>» </a>
                                    @endforeach </h5>
                            </div>
                        @endif
                        @if ($usedAlternative && $query !== $originalQuery)
                            <p>Показано результати для <strong>{{ $query }}</strong>.</p>
                        @endif
                    </div>

                    @if ($getProducts->isEmpty())
                        <p>Ничего не найдено.</p>
                    @else
                        <catalog-product-list :products='@json($getProducts->items())'
                            :pagination='@json($paginationData)'>
                        </catalog-product-list>
                    @endif

                    {{-- Пагинация Laravel по ссылке --}}
                    @if (
                        $getProducts instanceof \Illuminate\Pagination\LengthAwarePaginator ||
                            $getProducts instanceof \Illuminate\Pagination\Paginator)
                        <div class="mt-4">
                            {!! $getProducts->links() !!}
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
