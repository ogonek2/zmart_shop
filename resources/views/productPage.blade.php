@extends('layouts.app')

@php
    $gallery = collect();
    if ($product->image_path) {
        $gallery->push($product->image_path);
    }
    foreach ($images ?? [] as $image) {
        $src = method_exists($image, 'getImagePath') ? $image->getImagePath() : ($image->src ?? null);
        if ($src && !$gallery->contains($src)) {
            $gallery->push($src);
        }
    }
    $gallery = $gallery->values();

    $basePrice = (float) $product->price;
    $discount = (float) ($product->discount ?? 0);
    $finalPrice = $discount > 0 ? $basePrice - ($basePrice * $discount / 100) : $basePrice;
    $inStock = in_array($product->availability, ['in_stock', 1, '1', true, null, ''], true)
        || $product->availability === 'in_stock';

    $specs = [];
    foreach (['characteristics', 'modifications', 'additional_fields'] as $field) {
        $data = $product->{$field} ?? null;
        if (!is_array($data)) {
            continue;
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = implode(', ', array_filter($value));
            }
            $value = trim((string) $value);
            $key = trim((string) $key);
            if ($key === '' || $value === '' || in_array($value, ['Не вказано', '-', 'null'], true)) {
                continue;
            }
            $specs[$key] = $value;
        }
    }
    if (!empty($product->brand)) {
        $specs = ['Бренд' => $product->brand] + $specs;
    }
    if (!empty($product->articule)) {
        $specs = ['Артикул' => $product->articule] + $specs;
    }
    if (!empty($product->condition_item)) {
        $specs['Стан'] = $product->condition_item;
    }

    $bullets = [];
    if (!empty($product->complectation)) {
        $parts = preg_split('/[\r\n|;]+/', strip_tags($product->complectation)) ?: [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part !== '') {
                $bullets[] = $part;
            }
        }
    }
    if (!$bullets && !empty($product->description)) {
        $text = trim(strip_tags($product->description));
        $parts = preg_split('/(?<=[.!?])\s+/u', $text) ?: [];
        foreach ($parts as $part) {
            $part = trim($part);
            if (mb_strlen($part) > 12) {
                $bullets[] = $part;
            }
            if (count($bullets) >= 5) {
                break;
            }
        }
    }
    foreach (array_slice($specs, 0, 5, true) as $key => $value) {
        if (count($bullets) >= 5) {
            break;
        }
        if (!in_array($key, ['Артикул', 'Бренд'], true)) {
            $bullets[] = "{$key}: {$value}";
        }
    }

    $category = $product->categories->first();
    $boxQty = optional($product->packages->first())->value;
@endphp

@section('seo')
    <title>{{ $product->seo_title ?: ($product->name . ' - ZMART') }}</title>
    <meta name="description" content="{{ $product->seo_description ?: ($product->description ? strip_tags(mb_substr($product->description, 0, 160)) : $product->name) }}">
@endsection

@section('content')
<main class="ppage">
    <div class="zmart-shell">
        <nav class="ppage__crumbs" aria-label="Хлібні крихти">
            <a href="{{ url('/') }}"><i class="fas fa-home"></i></a>
            <span>/</span>
            <a href="{{ route('catalog') }}">Каталог</a>
            @if ($category)
                <span>/</span>
                <a href="{{ route('catalog_category_page', $category->url) }}">{{ trim($category->name) }}</a>
            @endif
            <span>/</span>
            <span>{{ $product->name }}</span>
        </nav>

        <div class="ppage__layout">
            {{-- Gallery --}}
            <section class="ppage__gallery {{ $gallery->count() > 1 ? 'has-thumbs' : '' }}" aria-label="Фото товару">
                @if ($gallery->count() > 1)
                    <div class="ppage__thumbs" role="list">
                        @foreach ($gallery as $index => $src)
                            <button
                                type="button"
                                class="ppage__thumb {{ $index === 0 ? 'is-active' : '' }}"
                                data-index="{{ $index }}"
                                data-src="{{ $src }}"
                                aria-label="Фото {{ $index + 1 }}"
                            >
                                <img src="{{ $src }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="ppage__stage">
                    @if ($discount > 0)
                        <span class="ppage__badge ppage__badge--sale">-{{ rtrim(rtrim(number_format($discount, 1, '.', ''), '0'), '.') }}%</span>
                    @endif
                    @if ($product->is_wholesale && $product->wholesale_price)
                        <span class="ppage__badge ppage__badge--opt">Опт</span>
                    @endif

                    @if ($gallery->isNotEmpty())
                        <img
                            id="ppage-main-image"
                            src="{{ $gallery->first() }}"
                            alt="{{ $product->name }}"
                            class="ppage__main-img"
                        >
                    @else
                        <div class="ppage__main-empty"><i class="fas fa-image"></i></div>
                    @endif

                    @if ($gallery->count() > 1)
                        <button type="button" class="ppage__nav ppage__nav--prev" id="ppage-prev" aria-label="Попереднє фото">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="ppage__nav ppage__nav--next" id="ppage-next" aria-label="Наступне фото">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </section>

            {{-- Product info --}}
            <section class="ppage__info">
                @if ($product->brand)
                    <p class="ppage__brand">{{ $product->brand }}</p>
                @elseif ($category)
                    <a class="ppage__brand" href="{{ route('catalog_category_page', $category->url) }}">{{ trim($category->name) }}</a>
                @endif

                <h1 class="ppage__title">{{ $product->name }}</h1>

                <div class="ppage__meta">
                    @if ($product->articule)
                        <span>Артикул: <b>{{ $product->articule }}</b></span>
                    @endif
                    <span class="ppage__stock {{ $inStock ? 'is-in' : 'is-out' }}">
                        <i class="fas fa-circle"></i>
                        {{ $inStock ? 'В наявності' : 'Немає в наявності' }}
                    </span>
                </div>

                <div class="ppage__price-row ppage__price-row--inline">
                    <div class="ppage__price">
                        <strong>{{ number_format($finalPrice, 0, '.', ' ') }} грн</strong>
                        @if ($discount > 0)
                            <s>{{ number_format($basePrice, 0, '.', ' ') }} грн</s>
                        @endif
                    </div>
                    @if ($boxQty)
                        <span class="ppage__box">У ящику: {{ $boxQty }} шт</span>
                    @endif
                </div>

                @if ($product->is_wholesale && $product->wholesale_price)
                    <div class="ppage__wholesale">
                        <i class="fas fa-boxes"></i>
                        <div>
                            <b>Оптова ціна: {{ number_format((float) $product->wholesale_price, 0, '.', ' ') }} грн</b>
                            <span>від {{ $product->wholesale_min_quantity ?: 1 }} шт</span>
                        </div>
                    </div>
                @endif

                @if (count($bullets))
                    <div class="ppage__about">
                        <h2>Про цей товар</h2>
                        <ul>
                            @foreach ($bullets as $bullet)
                                <li>{{ $bullet }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($specs))
                    <div class="ppage__specs-preview">
                        <h2>Короткі характеристики</h2>
                        <table>
                            <tbody>
                                @foreach (array_slice($specs, 0, 8, true) as $key => $value)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (count($specs) > 8)
                            <a href="#ppage-details" class="ppage__more-link">Усі характеристики</a>
                        @endif
                    </div>
                @endif
            </section>

            {{-- Buy box --}}
            <aside class="ppage__buybox">
                <div class="ppage__buybox-card">
                    <div class="ppage__price">
                        <strong id="ppage-buy-price">{{ number_format($finalPrice, 0, '.', ' ') }} грн</strong>
                        @if ($discount > 0)
                            <s>{{ number_format($basePrice, 0, '.', ' ') }} грн</s>
                        @endif
                    </div>

                    <p class="ppage__delivery">
                        <i class="fas fa-truck"></i>
                        Доставка Новою Поштою по Україні
                    </p>

                    <p class="ppage__stock {{ $inStock ? 'is-in' : 'is-out' }}">
                        <i class="fas fa-circle"></i>
                        {{ $inStock ? 'В наявності' : 'Немає в наявності' }}
                    </p>

                    <label class="ppage__qty">
                        <span>Кількість</span>
                        <div class="ppage__qty-ctrl">
                            <button type="button" id="ppage-qty-minus" aria-label="Менше">−</button>
                            <input id="ppage-qty" type="number" min="1" value="1" inputmode="numeric">
                            <button type="button" id="ppage-qty-plus" aria-label="Більше">+</button>
                        </div>
                    </label>

                    <button
                        type="button"
                        class="ppage__cart-btn"
                        id="ppage-add-cart"
                        @if(!$inStock) disabled @endif
                    >
                        <i class="fas fa-shopping-cart"></i>
                        Додати в кошик
                    </button>

                    <button type="button" class="ppage__wish-btn" id="ppage-wish">
                        <i class="far fa-heart"></i>
                        В обране
                    </button>

                    <ul class="ppage__buybox-perks">
                        <li><i class="fas fa-shield-alt"></i> Гарантія 12 місяців</li>
                        <li><i class="fas fa-undo"></i> Повернення 14 днів</li>
                        <li><i class="fas fa-lock"></i> Безпечна оплата</li>
                    </ul>

                    <p class="ppage__min-order">Мінімальна сума замовлення — 1000 ₴</p>
                </div>
            </aside>
        </div>

        {{-- Details --}}
        <section class="ppage__details" id="ppage-details">
            <div class="ppage__tabs" role="tablist">
                <button type="button" class="ppage__tab is-active" data-tab="about" role="tab">Опис</button>
                <button type="button" class="ppage__tab" data-tab="specs" role="tab">Характеристики</button>
                <button type="button" class="ppage__tab" data-tab="delivery" role="tab">Доставка</button>
            </div>

            <div class="ppage__panels">
                <div class="ppage__panel is-active" data-panel="about">
                    <h2>Опис товару</h2>
                    <div class="ppage__prose">
                        @if (!empty($product->description))
                            {!! $product->description !!}
                        @else
                            <p>Опис товару відсутній.</p>
                        @endif
                    </div>
                    @if (!empty($product->complectation))
                        <h3>Комплектація</h3>
                        <div class="ppage__prose">{!! nl2br(e($product->complectation)) !!}</div>
                    @endif
                </div>

                <div class="ppage__panel" data-panel="specs">
                    <h2>Технічні характеристики</h2>
                    @if (count($specs))
                        <table class="ppage__table">
                            <tbody>
                                @foreach ($specs as $key => $value)
                                    <tr>
                                        <th>{{ $key }}</th>
                                        <td>{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="ppage__muted">Характеристики не вказані.</p>
                    @endif
                </div>

                <div class="ppage__panel" data-panel="delivery">
                    <h2>Доставка та оплата</h2>
                    <div class="ppage__delivery-grid">
                        <article>
                            <h3><i class="fas fa-truck"></i> Нова Пошта</h3>
                            <p>Відділення або кур’єр по всій Україні. Зазвичай 1–3 робочих дні.</p>
                        </article>
                        <article>
                            <h3><i class="fas fa-credit-card"></i> Оплата</h3>
                            <p>Онлайн-оплата, безготівковий розрахунок або оплата при отриманні (за домовленістю).</p>
                        </article>
                        <article>
                            <h3><i class="fas fa-undo"></i> Повернення</h3>
                            <p>Повернення або обмін протягом 14 днів згідно із законодавством України.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        @if (isset($recommendedProducts) && $recommendedProducts->count() > 0)
            <section class="ppage__related">
                <div class="ppage__related-head">
                    <h2>Схожі товари</h2>
                    <a href="{{ route('catalog') }}">Весь каталог</a>
                </div>
                <div class="pcard-grid">
                    @foreach ($recommendedProducts as $item)
                        @include('includes.catalog.product-card', ['product' => $item])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection

@section('scripts')
<script>
window.__PPAGE__ = {
    images: @json($gallery->values()),
    index: 0,
    product: {
        id: {{ (int) $product->id }},
        name: @json(trim(preg_replace('/\s+/u', ' ', (string) $product->name))),
        price: {{ json_encode((float) $finalPrice) }},
        image: @json((string) $product->image_path),
        articule: @json((string) ($product->articule ?: 'Не вказано')),
        availability: @json((string) ($product->availability ?? 'in_stock')),
        wholesale: {{ $product->is_wholesale ? 'true' : 'false' }},
        wholesalePrice: {{ json_encode((float) ($product->wholesale_price ?: 0)) }},
        wholesaleMin: {{ (int) ($product->wholesale_min_quantity ?: 0) }},
    },
};

window.__PPAGE_SET_IMAGE__ = function (next) {
    const state = window.__PPAGE__;
    if (!state || !state.images.length) return;
    const main = document.getElementById('ppage-main-image');
    if (!main) return;
    state.index = (next + state.images.length) % state.images.length;
    main.style.opacity = '0';
    setTimeout(() => {
        main.src = state.images[state.index];
        main.style.opacity = '1';
    }, 120);
    document.querySelectorAll('.ppage__thumb').forEach((btn, i) => {
        btn.classList.toggle('is-active', i === state.index);
    });
};

window.__PPAGE_CLAMP_QTY__ = function () {
    const qtyInput = document.getElementById('ppage-qty');
    if (!qtyInput) return 1;
    const value = parseInt(qtyInput.value, 10);
    qtyInput.value = Number.isFinite(value) && value > 0 ? value : 1;
    return Number(qtyInput.value);
};

window.__PPAGE_SYNC_WISH__ = function () {
    const wishBtn = document.getElementById('ppage-wish');
    if (!wishBtn) return;
    const list = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const id = Number(window.__PPAGE__?.product?.id);
    const active = list.some((item) => Number(item.id ?? item) === id);
    const icon = wishBtn.querySelector('i');
    if (icon) {
        icon.classList.toggle('fas', active);
        icon.classList.toggle('far', !active);
    }
    wishBtn.classList.toggle('is-active', active);
};

if (!window.__PPAGE_BOUND__) {
    window.__PPAGE_BOUND__ = true;

    document.addEventListener('click', function (e) {
        const thumb = e.target.closest('.ppage__thumb');
        if (thumb) {
            e.preventDefault();
            window.__PPAGE_SET_IMAGE__(Number(thumb.dataset.index) || 0);
            return;
        }

        if (e.target.closest('#ppage-prev')) {
            e.preventDefault();
            window.__PPAGE_SET_IMAGE__((window.__PPAGE__?.index || 0) - 1);
            return;
        }

        if (e.target.closest('#ppage-next')) {
            e.preventDefault();
            window.__PPAGE_SET_IMAGE__((window.__PPAGE__?.index || 0) + 1);
            return;
        }

        if (e.target.closest('#ppage-qty-minus')) {
            e.preventDefault();
            const input = document.getElementById('ppage-qty');
            if (input) input.value = Math.max(1, window.__PPAGE_CLAMP_QTY__() - 1);
            return;
        }

        if (e.target.closest('#ppage-qty-plus')) {
            e.preventDefault();
            const input = document.getElementById('ppage-qty');
            if (input) input.value = window.__PPAGE_CLAMP_QTY__() + 1;
            return;
        }

        const addBtn = e.target.closest('#ppage-add-cart');
        if (addBtn) {
            e.preventDefault();
            if (addBtn.disabled || typeof window.addToCart !== 'function') return;
            const p = window.__PPAGE__?.product || {};
            window.addToCart(
                p.id,
                p.name,
                parseFloat(p.price) || 0,
                p.image || '',
                p.articule || 'Не вказано',
                p.availability || 'in_stock',
                window.__PPAGE_CLAMP_QTY__(),
                !!p.wholesale,
                parseFloat(p.wholesalePrice) || 0,
                parseInt(p.wholesaleMin, 10) || 0
            );
            return;
        }

        const wishBtn = e.target.closest('#ppage-wish');
        if (wishBtn) {
            e.preventDefault();
            const id = Number(window.__PPAGE__?.product?.id);
            if (typeof window.toggleWishlist === 'function' && id) {
                window.toggleWishlist(id);
            }
            window.__PPAGE_SYNC_WISH__();
            return;
        }

        const tab = e.target.closest('.ppage__tab');
        if (tab) {
            e.preventDefault();
            const name = tab.dataset.tab;
            document.querySelectorAll('.ppage__tab').forEach((el) => el.classList.toggle('is-active', el === tab));
            document.querySelectorAll('.ppage__panel').forEach((panel) => {
                panel.classList.toggle('is-active', panel.dataset.panel === name);
            });
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target && e.target.id === 'ppage-qty') {
            window.__PPAGE_CLAMP_QTY__();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (!document.querySelector('.ppage')) return;
        if (e.key === 'ArrowLeft') window.__PPAGE_SET_IMAGE__((window.__PPAGE__?.index || 0) - 1);
        if (e.key === 'ArrowRight') window.__PPAGE_SET_IMAGE__((window.__PPAGE__?.index || 0) + 1);
    });

    window.addEventListener('wishlist-updated', function () {
        window.__PPAGE_SYNC_WISH__();
    });
}

document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        window.__PPAGE_SYNC_WISH__();
    }, 0);
});
</script>
@endsection
