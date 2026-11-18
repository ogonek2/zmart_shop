@extends('layouts.app')

@section('seo')
    <title>ZMART - Інтернет-магазин техніки</title>
    <meta name="description"
        content="Широкий вибір якісної побутової техніки та господарських товарів від провідних брендів. Швидка доставка по всій Україні. Офіційна гарантія.">
@endsection

@section('content')
    @php
        $rootCategories = get_all_category()->whereNull('parent_id')->values();
        $chipCategories = $rootCategories->take(11);

        $findCategory = function (array $needles) use ($rootCategories) {
            foreach ($rootCategories as $category) {
                $name = mb_strtolower($category->name);
                foreach ($needles as $needle) {
                    if (str_contains($name, mb_strtolower($needle))) {
                        return $category;
                    }
                }
            }
            return $rootCategories->first();
        };

        $promoBlocks = [
            [
                'label' => 'Кухня',
                'title' => 'Посуд',
                'category' => $findCategory(['посуд', 'кухн']),
            ],
            [
                'label' => 'Господарство',
                'title' => 'Дім і ремонт',
                'category' => $findCategory(['інструмент', 'инструмент', 'щітк', 'щетк']),
            ],
            [
                'label' => 'Догляд',
                'title' => 'Гігієна',
                'category' => $findCategory(['догляд', 'гігієн', 'гигиен', 'волос']),
            ],
        ];
    @endphp

    <main class="zmart-home">
        {{-- HERO --}}
        <section class="zh-hero" aria-label="Головний банер">
            @if (isset($heroSlides) && $heroSlides->count() > 0)
                <div class="zh-hero__slider" data-hero-slider>
                    @foreach ($heroSlides as $slide)
                        <article class="zh-hero__slide {{ $loop->first ? 'is-active' : '' }}" data-hero-slide>
                            <div class="zh-hero__media">
                                @if ($slide->image_url)
                                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                                        loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                @else
                                    <div class="zh-hero__fallback"></div>
                                @endif
                            </div>

                            <div class="zh-wrap zh-hero__overlay">
                                <div class="zh-hero__panel">
                                    <h1>{{ $slide->title }}</h1>
                                    @if ($slide->subtitle)
                                        <p>{{ $slide->subtitle }}</p>
                                    @endif
                                    @if ($slide->button_text && $slide->button_url)
                                        <a href="{{ $slide->button_url }}" class="zh-btn">{{ $slide->button_text }}</a>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach

                    @if ($heroSlides->count() > 1)
                        <button type="button" class="zh-hero__arrow zh-hero__arrow--prev" data-hero-prev
                            aria-label="Попередній">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="zh-hero__arrow zh-hero__arrow--next" data-hero-next
                            aria-label="Наступний">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="zh-hero__dots" data-hero-dots>
                            @foreach ($heroSlides as $slide)
                                <button type="button" class="{{ $loop->first ? 'is-active' : '' }}"
                                    data-hero-dot="{{ $loop->index }}"
                                    aria-label="Слайд {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="zh-hero__slide is-active">
                    <div class="zh-hero__media">
                        <div class="zh-hero__fallback"></div>
                    </div>
                    <div class="zh-wrap zh-hero__overlay">
                        <div class="zh-hero__panel">
                            <h1>Побутова техніка для вашого дому</h1>
                            <p>Якісні товари з доставкою по всій Україні</p>
                            <a href="{{ route('catalog') }}" class="zh-btn">Перейти до каталогу</a>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- USP --}}
        <section class="zmart-home__usp">
            <div class="zmart-shell">
                <ul class="zmart-usp-list">
                    <li>
                        <i class="fas fa-truck"></i>
                        <div>
                            <strong>Доставка по Україні</strong>
                            <span>Нова Пошта та самовивіз з 7 км</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-boxes"></i>
                        <div>
                            <strong>Опт і роздріб</strong>
                            <span>Вигідні ціни від партії</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Гарантія якості</strong>
                            <span>Перевірені товари на складі</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-headset"></i>
                        <div>
                            <strong>Підтримка покупця</strong>
                            <span>Допоможемо з підбором</span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        {{-- CATEGORY CHIPS --}}
        <section class="zmart-home__cats-section">
            <div class="zmart-shell">
                <div class="zmart-section-head">
                    <h2 class="zmart-section-title">Популярні категорії</h2>
                    <a href="{{ route('catalog') }}" class="zmart-section-link">Весь каталог <i
                            class="fas fa-arrow-right"></i></a>
                </div>
                <div class="zmart-cats-grid">
                    @foreach ($chipCategories as $category)
                        @php
                            $categoryImage = null;
                            $latestProduct = $category->products()->latest()->first();
                            if ($latestProduct && $latestProduct->image_path) {
                                $categoryImage = $latestProduct->image_path;
                            }
                            if (!$categoryImage) {
                                foreach (
                                    $category->childCategories()->where('is_active', true)->get()
                                    as $childCategory
                                ) {
                                    $childProduct = $childCategory->products()->latest()->first();
                                    if ($childProduct && $childProduct->image_path) {
                                        $categoryImage = $childProduct->image_path;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <a href="{{ route('catalog_category_page', $category->url) }}" class="zmart-cat-chip">
                            <span class="zmart-cat-chip__icon">
                                @if ($categoryImage)
                                    <img src="{{ $categoryImage }}" alt="{{ $category->name }}">
                                @else
                                    <i class="fas fa-box"></i>
                                @endif
                            </span>
                            <span class="zmart-cat-chip__name">{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- BESTSELLERS --}}
        @if (isset($popularProducts) && $popularProducts->count() > 0)
            <section class="zmart-home__products-section">
                <div class="zmart-shell">
                    <div class="zmart-section-head">
                        <div>
                            <h2 class="zmart-section-title">Хіти продажу</h2>
                            <p class="zmart-section-sub">Товари, які найчастіше замовляють наші клієнти</p>
                        </div>
                        <a href="{{ route('catalog') }}" class="zmart-section-link">Дивитись усі <i
                                class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="pcard-grid">
                        @foreach ($popularProducts->take(10) as $product)
                            @include('includes.catalog.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- SALE --}}
        @if (isset($saleProducts) && $saleProducts->count() > 0)
            <section class="zmart-home__products-section zmart-home__products-section--sale">
                <div class="zmart-shell">
                    <div class="zmart-section-head">
                        <div>
                            <h2 class="zmart-section-title">Знижки тижня</h2>
                            <p class="zmart-section-sub">Товари зі знижкою — встигніть купити вигідніше</p>
                        </div>
                        <a href="{{ route('catalog', ['discount' => 1]) }}" class="zmart-section-link">Усі акції <i
                                class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="pcard-grid">
                        @foreach ($saleProducts as $product)
                            @include('includes.catalog.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- WHOLESALE BAND --}}
        <section class="zmart-home__wholesale-band">
            <div class="zmart-shell">
                <div class="zmart-wholesale-band">
                    <div class="zmart-wholesale-band__copy">
                        <p class="zmart-wholesale-band__eyebrow">Для магазинів та перекупників</p>
                        <h2>Оптові ціни від невеликих партій</h2>
                        <p>Купуйте з вигодою: на картках товарів видно оптову ціну та мінімальну кількість. Самовивіз з
                            ринку «7 км» або доставка Новою Поштою.</p>
                    </div>
                    <div class="zmart-wholesale-band__actions">
                        <a href="{{ route('catalog', ['wholesale' => 1]) }}" class="zmart-btn-yellow">Товари з оптом</a>
                        <a href="{{ route('kontaktna_informatsiia') }}" class="zmart-wholesale-band__ghost">Зв’язатися з менеджером</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- WHOLESALE PRODUCTS --}}
        @if (isset($wholesaleProducts) && $wholesaleProducts->count() > 0)
            <section class="zmart-home__products-section">
                <div class="zmart-shell">
                    <div class="zmart-section-head">
                        <div>
                            <h2 class="zmart-section-title">Вигідний опт</h2>
                            <p class="zmart-section-sub">Ціна за штуку знижується при замовленні партії</p>
                        </div>
                        <a href="{{ route('catalog', ['wholesale' => 1]) }}" class="zmart-section-link">Усі оптові <i
                                class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="pcard-grid">
                        @foreach ($wholesaleProducts as $product)
                            @include('includes.catalog.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- CATEGORY SHOWCASES --}}
        @if (isset($categoryShowcases) && $categoryShowcases->count() > 0)
            @foreach ($categoryShowcases as $showcase)
                <section class="zmart-home__products-section {{ $loop->even ? 'zmart-home__products-section--alt' : '' }}">
                    <div class="zmart-shell">
                        <div class="zmart-section-head">
                            <div>
                                <h2 class="zmart-section-title">{{ $showcase['category']->name }}</h2>
                                <p class="zmart-section-sub">Актуальний асортимент категорії в наявності</p>
                            </div>
                            <a href="{{ route('catalog_category_page', $showcase['category']->url) }}"
                                class="zmart-section-link">До категорії <i class="fas fa-arrow-right"></i></a>
                        </div>

                        <div class="pcard-grid">
                            @foreach ($showcase['products'] as $product)
                                @include('includes.catalog.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
        @endif

        {{-- NEW ARRIVALS --}}
        @if (isset($newProducts) && $newProducts->count() > 0)
            <section class="zmart-home__products-section zmart-home__products-section--alt">
                <div class="zmart-shell">
                    <div class="zmart-section-head">
                        <div>
                            <h2 class="zmart-section-title">Нові надходження</h2>
                            <p class="zmart-section-sub">Свіжі позиції зі складу — варто глянути першими</p>
                        </div>
                        <a href="{{ route('catalog', ['sort' => 'newest']) }}" class="zmart-section-link">Усі новинки <i
                                class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="pcard-grid">
                        @foreach ($newProducts as $product)
                            @include('includes.catalog.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- TRUST + CTA --}}
        <section class="zmart-home__trust">
            <div class="zmart-shell">
                <div class="zmart-trust">
                    <div class="zmart-trust__copy">
                        <h2>Чому купують у ZMART</h2>
                        <p>Працюємо з ринку «7 км» в Одесі: широкий асортимент господарських товарів і техніки, живий
                            склад і зрозумілі умови для роздробу та опту.</p>
                        <ul>
                            <li><i class="fas fa-check"></i>Магазин №2523, вул. Фабрична</li>
                            <li><i class="fas fa-check"></i>Самовивіз або доставка НП</li>
                            <li><i class="fas fa-check"></i>Роздрібні та оптові ціни в картці товару</li>
                        </ul>
                    </div>
                    <div class="zmart-trust__cta">
                        <h3>Готові обрати товари?</h3>
                        <p>Відкрийте каталог і додавайте потрібну кількість одразу в кошик.</p>
                        <a href="{{ route('catalog') }}" class="zmart-btn-yellow">Перейти до каталогу</a>
                        <a href="tel:+380730777572" class="zmart-trust__phone">
                            <i class="fas fa-phone-alt"></i> +38 073 077 75 72
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('[data-hero-slider]');
            if (slider) {
                const slides = Array.from(slider.querySelectorAll('[data-hero-slide]'));
                const dots = Array.from(slider.querySelectorAll('[data-hero-dot]'));
                const prevBtn = slider.querySelector('[data-hero-prev]');
                const nextBtn = slider.querySelector('[data-hero-next]');
                let index = 0;
                let timer = null;

                const show = (nextIndex) => {
                    if (!slides.length) return;
                    index = (nextIndex + slides.length) % slides.length;
                    slides.forEach((slide, i) => slide.classList.toggle('is-active', i === index));
                    dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
                };

                const stop = () => {
                    if (timer) clearInterval(timer);
                };

                const start = () => {
                    if (slides.length < 2) return;
                    stop();
                    timer = setInterval(() => show(index + 1), 6000);
                };

                prevBtn?.addEventListener('click', () => {
                    show(index - 1);
                    start();
                });
                nextBtn?.addEventListener('click', () => {
                    show(index + 1);
                    start();
                });
                dots.forEach((dot) => {
                    dot.addEventListener('click', () => {
                        show(parseInt(dot.dataset.heroDot || '0', 10));
                        start();
                    });
                });

                slider.addEventListener('mouseenter', stop);
                slider.addEventListener('mouseleave', start);
                start();
            }

            document.querySelectorAll('[data-promo-rotator]').forEach((card) => {
                const images = Array.from(card.querySelectorAll('.zmart-promo__stage img'));
                const dots = Array.from(card.querySelectorAll('.zmart-promo__dots span'));
                if (images.length < 2) return;

                let index = images.findIndex((img) => img.classList.contains('is-active'));
                if (index < 0) index = 0;
                const delay = parseInt(card.dataset.promoDelay || '2500', 10);
                let timer = null;

                const show = (nextIndex) => {
                    index = (nextIndex + images.length) % images.length;
                    images.forEach((img, i) => img.classList.toggle('is-active', i === index));
                    dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
                };

                const stop = () => {
                    if (timer) clearInterval(timer);
                };

                const start = () => {
                    stop();
                    timer = setInterval(() => show(index + 1), delay);
                };

                card.addEventListener('mouseenter', stop);
                card.addEventListener('mouseleave', start);
                start();
            });
        });
    </script>
@endsection
