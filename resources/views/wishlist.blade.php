@extends('layouts.app')

@section('seo')
    <title>Обране - ZMART</title>
    <meta name="description" content="Ваш список обраних товарів ZMART.">
@endsection

@section('styles')
    <script>
        window.__WISHLIST_BOOT__ = @json([
            'categories' => $categories,
        ]);
    </script>
@endsection

@section('content')
    <main class="wpage">
        <div class="zmart-shell">
            <nav class="wpage__crumbs" aria-label="Хлібні крихти">
                <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span>Обране</span>
            </nav>

            <wishlist-page></wishlist-page>
        </div>
    </main>
@endsection
