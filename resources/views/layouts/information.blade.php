@extends('layouts.app')

@section('content')
<section class="zinfo">
    <div class="zh-wrap zinfo__top">
        <nav class="zinfo__crumbs" aria-label="Навігація">
            <a href="{{ url('/') }}">Головна</a>
            <span aria-hidden="true">/</span>
            <span>{{ trim($__env->yieldContent('info_title')) }}</span>
        </nav>
        <h1 class="zinfo__title">@yield('info_title')</h1>
        @hasSection('info_lead')
            <p class="zinfo__lead">@yield('info_lead')</p>
        @endif
    </div>

    <div class="zh-wrap zinfo__layout">
        @include('includes.main.information_bar')
        <article class="zinfo__main">
            @yield('info_content')
        </article>
    </div>
</section>
@endsection
