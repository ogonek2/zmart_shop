@extends('layouts.app')

@php
    $endpoint = $endpoint ?? route('catalog');
    $categoryPayload = isset($category) && $category
        ? ['id' => $category->id, 'name' => $category->name, 'url' => $category->url]
        : null;

    $categoriesPayload = collect($categories ?? [])->map(function ($cat) {
        if (is_array($cat)) {
            return $cat;
        }
        return map_category_tree_node($cat);
    })->values();

    $subTreePayload = collect($subTree ?? [])->values();
    if ($subTreePayload->isEmpty() && !empty($subcategories)) {
        $subTreePayload = collect($subcategories)->map(function ($sub) {
            if (is_array($sub)) {
                return $sub;
            }
            return map_category_tree_node($sub);
        })->values();
    }

    $facetsPayload = collect($facets ?? [])->values();

    $productsPayload = collect($products->items())->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'discount' => $product->discount,
            'image_path' => $product->image_path,
            'url' => $product->url,
            'articule' => $product->articule,
            'availability' => $product->availability,
            'is_wholesale' => (bool) $product->is_wholesale,
            'wholesale_price' => $product->wholesale_price,
            'wholesale_min_quantity' => $product->wholesale_min_quantity,
            'packages' => $product->relationLoaded('packages')
                ? $product->packages->map(fn ($p) => ['value' => $p->value])->values()
                : [],
        ];
    })->values();

    $paginationPayload = [
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'per_page' => $products->perPage(),
        'total' => $products->total(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
    ];

    $attrsRaw = request()->input('attrs', []);
    $attrsPayload = [];
    if (is_array($attrsRaw)) {
        foreach ($attrsRaw as $key => $values) {
            $attrsPayload[$key] = array_values(array_filter(array_map('strval', (array) $values)));
        }
    }

    $filtersPayload = [
        'price_min' => request('price_min', ''),
        'price_max' => request('price_max', ''),
        'availability' => request()->has('availability'),
        'discount' => request()->has('discount'),
        'wholesale' => request()->has('wholesale'),
        'sort' => request('sort', 'default'),
        'subcats' => array_values(array_map('intval', (array) request()->input('subcats', []))),
        'attrs' => $attrsPayload,
    ];

    $boot = [
        'endpoint' => $endpoint,
        'categories' => $categoriesPayload,
        'subTree' => $subTreePayload,
        'subcategories' => $subTreePayload,
        'facets' => $facetsPayload,
        'category' => $categoryPayload,
        'initialProducts' => $productsPayload,
        'initialPagination' => $paginationPayload,
        'initialFilters' => $filtersPayload,
    ];
@endphp

@section('seo')
    <title>{{ $pageTitle ?? 'Каталог товарів' }} - ZMART</title>
    <meta name="description" content="{{ $pageDescription ?? 'Широкий вибір якісної техніки та господарських товарів. Швидка доставка по Україні.' }}">
@endsection

@section('styles')
    <script>
        window.__CATALOG_BOOT__ = @json($boot);
    </script>
@endsection

@section('content')
    <main class="cshop-page">
        <div class="zmart-shell">
            <nav class="cshop-page__crumbs" aria-label="Хлібні крихти">
                <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                @if (!empty($category))
                    <a href="{{ route('catalog') }}">Каталог</a>
                    <span>/</span>
                    <span>{{ $category->name }}</span>
                @else
                    <span>Каталог</span>
                @endif
            </nav>

            <header class="cshop-page__head">
                <div>
                    <h1>{{ $pageTitle ?? 'Каталог товарів' }}</h1>
                    @if (!empty($pageSubtitle))
                        <p>{{ $pageSubtitle }}</p>
                    @endif
                </div>
            </header>

            <catalog-shop></catalog-shop>
        </div>
    </main>
@endsection
