@php
    $navCategories = \App\Models\Category::whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->with(['childCategories' => function ($q) {
            $q->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->with(['childCategories' => function ($q2) {
                    $q2->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                }]);
        }])
        ->get();

    $megaCategories = $navCategories->map(fn ($cat) => map_category_tree_node($cat))->values();
@endphp

<header class="zh">
    <div class="zh-bar">
        <div class="zh-wrap zh-bar__row">
            <button type="button" class="zh-icon-btn zh-burger" id="zh-burger" aria-label="Меню">
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ url('/') }}" class="zh-logo" aria-label="Zmart">
                <span>Z</span>mart
            </a>

            <button type="button" class="zh-catalog-btn" id="zh-catalog-open" aria-haspopup="dialog">
                <i class="fas fa-th-large"></i>
                <span>Каталог</span>
            </button>

            <div class="zh-search">
                <search></search>
            </div>

            <div class="zh-actions">
                <a href="tel:0730777572" class="zh-phone" aria-label="Зателефонувати">
                    <i class="fas fa-phone-alt"></i>
                    <span>+38 073 077 75 72</span>
                </a>
                <wishlist-button></wishlist-button>
                <open-cart-button></open-cart-button>
            </div>
        </div>
    </div>
</header>

{{-- Global mega menu --}}
<div id="zh-mega" class="cshop-mega" hidden>
    <div class="cshop-mega__backdrop" data-mega-close></div>
    <div class="cshop-mega__panel" role="dialog" aria-modal="true" aria-label="Каталог">
        <div class="cshop-mega__head">
            <h2>Каталог</h2>
            <a href="{{ route('catalog') }}" class="cshop-mega__all" data-mega-close>Усі товари</a>
            <button type="button" class="cshop__icon-btn" data-mega-close aria-label="Закрити">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cshop-mega__shell">
            <nav class="cshop-mega__nav" aria-label="Основні категорії">
                @foreach ($megaCategories as $index => $cat)
                    <button
                        type="button"
                        class="cshop-mega__nav-item {{ $index === 0 ? 'is-active' : '' }}"
                        data-mega-cat="{{ $cat['id'] }}"
                        data-mega-url="{{ $cat['url'] }}"
                    >
                        <span class="cshop-mega__nav-name">{{ $cat['name'] }}</span>
                        @if (isset($cat['count']))
                            <em class="cshop-mega__nav-count">{{ $cat['count'] }}</em>
                        @endif
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                @endforeach
            </nav>
            <div class="cshop-mega__flyout" id="zh-mega-flyout">
                {{-- filled by JS --}}
            </div>
        </div>
    </div>
</div>

{{-- Mobile menu bottom sheet --}}
<div id="zh-mobile" class="zsheet" hidden>
    <div class="zsheet__backdrop" data-mobile-close></div>
    <div class="zsheet__panel zsheet__panel--menu" role="dialog" aria-modal="true" aria-label="Меню">
        <div class="zsheet__grab"></div>
        <div class="zsheet__head">
            <strong>Меню</strong>
            <button type="button" class="zh-icon-btn" data-mobile-close aria-label="Закрити">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="zsheet__body">
            <button type="button" class="zsheet__link" id="zh-mobile-catalog">
                <i class="fas fa-th-large"></i>
                <span>Каталог товарів</span>
                <i class="fas fa-chevron-right"></i>
            </button>
            <a href="{{ route('catalog') }}" class="zsheet__link" data-mobile-close>
                <i class="fas fa-store"></i>
                <span>Усі товари</span>
            </a>
            <a href="{{ route('wishlist') }}" class="zsheet__link" data-mobile-close>
                <i class="far fa-heart"></i>
                <span>Обране</span>
            </a>
            <a href="{{ route('pro_kompaniiu') }}" class="zsheet__link" data-mobile-close>
                <i class="fas fa-info-circle"></i>
                <span>Про нас</span>
            </a>
            <a href="{{ route('oplata_i_dostavka') }}" class="zsheet__link" data-mobile-close>
                <i class="fas fa-truck"></i>
                <span>Доставка</span>
            </a>
            <a href="{{ route('obmin_ta_povernennia') }}" class="zsheet__link" data-mobile-close>
                <i class="fas fa-undo"></i>
                <span>Гарантія</span>
            </a>
            <a href="{{ route('kontaktna_informatsiia') }}" class="zsheet__link" data-mobile-close>
                <i class="fas fa-headset"></i>
                <span>Підтримка</span>
            </a>
            <a href="tel:0730777572" class="zsheet__phone">+38 073 077 75 72</a>
        </div>
    </div>
</div>

<script>
window.__MEGA_CATS__ = @json($megaCategories);
</script>

<mini-cart></mini-cart>
