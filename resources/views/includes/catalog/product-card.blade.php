@php
    $unitPrice = $product->discount > 0
        ? (float) $product->price * (1 - (float) $product->discount / 100)
        : (float) $product->price;

    $inStock = in_array($product->availability, ['in_stock', 1, '1', true], true)
        || $product->availability === null
        || $product->availability === '';

    $boxQty = null;
    if (method_exists($product, 'packages')) {
        $package = $product->relationLoaded('packages')
            ? $product->packages->first()
            : $product->packages()->first();
        if ($package && is_numeric($package->value)) {
            $boxQty = (int) $package->value;
        }
    }

    $cardId = 'pcard-qty-' . $product->id . '-' . uniqid();
@endphp

<article class="pcard">
    <div class="pcard__media">
        <button type="button" class="pcard__wish" onclick="toggleWishlist({{ $product->id }})" aria-label="Обране">
            <i class="far fa-heart"></i>
        </button>

        <a href="/catalog/{{ $product->url }}" class="pcard__image">
            <img src="{{ $product->image_path ?: asset('dist/img/no-image.png') }}"
                 alt="{{ $product->name }}"
                 loading="lazy">
        </a>
    </div>

    <div class="pcard__body">
        <h3 class="pcard__title">
            <a href="/catalog/{{ $product->url }}">{{ $product->name }}</a>
        </h3>

        <div class="pcard__stock {{ $inStock ? 'is-in' : 'is-out' }}">
            <span class="pcard__stock-dot"></span>
            {{ $inStock ? 'В наявності' : 'Немає в наявності' }}
        </div>

        <div class="pcard__price">
            <strong>{{ number_format($unitPrice, 0, ',', ' ') }} грн</strong>
            <span>/ шт</span>
        </div>

        @if ($product->is_wholesale && $product->wholesale_price)
            <div class="pcard__opt">
                <i class="fas fa-box"></i>
                <div>
                    <b>{{ number_format((float) $product->wholesale_price, 0, ',', ' ') }} грн</b>
                    <small>опт від {{ (int) ($product->wholesale_min_quantity ?: 1) }} шт</small>
                </div>
            </div>
        @endif

        <div class="pcard__qty" data-pcard-qty>
            <button type="button" class="pcard__qty-btn" data-qty-minus aria-label="Менше">−</button>
            <label class="pcard__qty-field" for="{{ $cardId }}">
                <input id="{{ $cardId }}" type="number" min="1" step="1" value="1" data-qty-input>
                <span>шт</span>
            </label>
            <button type="button" class="pcard__qty-btn" data-qty-plus aria-label="Більше">+</button>
        </div>

        <button
            type="button"
            class="pcard__cart"
            @if(!$inStock) disabled @endif
            data-add-cart
            data-id="{{ $product->id }}"
            data-name="{{ e($product->name) }}"
            data-price="{{ $unitPrice }}"
            data-image="{{ $product->image_path ?: '' }}"
            data-articule="{{ $product->articule ?: 'Не вказано' }}"
            data-availability="{{ $product->availability ?: 'in_stock' }}"
            data-wholesale="{{ $product->is_wholesale ? '1' : '0' }}"
            data-wholesale-price="{{ $product->wholesale_price ?: 0 }}"
            data-wholesale-min="{{ $product->wholesale_min_quantity ?: 0 }}"
        >
            <i class="fas fa-shopping-cart"></i>
            Додати до кошика
        </button>

        @if ($boxQty)
            <div class="pcard__box">
                <i class="fas fa-cubes"></i>
                У ящику: {{ $boxQty }} шт
            </div>
        @endif
    </div>
</article>
