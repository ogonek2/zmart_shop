@extends('layouts.app')

@section('seo')
    <title>Дякуємо за замовлення - ZMART</title>
    <meta name="description" content="Ваше замовлення прийнято. Менеджер ZMART скоро зв’яжеться з вами.">
@endsection

@section('content')
<main class="zthanks">
    <div class="zmart-shell">
        <div class="zthanks__card">
            <div class="zthanks__icon" aria-hidden="true">
                <i class="fas fa-check"></i>
            </div>
            <h1>Дякуємо за замовлення</h1>
            <p>Ми вже отримали вашу заявку і скоро зв’яжемося для підтвердження.</p>
            <div class="zthanks__actions">
                <a href="{{ route('welcome') }}" class="zthanks__btn">На головну</a>
                <a href="{{ route('catalog') }}" class="zthanks__btn zthanks__btn--ghost">До каталогу</a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
    @if(session('purchase_js'))
        <script>
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({!! json_encode(session('purchase_js'), JSON_UNESCAPED_UNICODE) !!});
        </script>
    @endif
@endsection
