<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('seo')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;500;600;700&family=Onest:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        #submit_cart_ch_form {
            display: none !important;
        }

        .callback-modal {
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .callback-modal.is-visible {
            display: flex;
        }

        .navbar-brand-mark {
            font-family: 'Onest', 'Golos Text', sans-serif;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .navbar-brand-mark .accent {
            color: #F5C400;
        }
    </style>

    <!-- Bootstrap Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"
        rel="stylesheet">
        
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WT7RLCN3');</script>
<!-- End Google Tag Manager -->

    @yield('styles')
</head>

<body class="bg-white">
    <div id="app" class="wrapper">
        @include('includes.main.nav')
        <div class="content-wrapper">
            @yield('content')
        </div>
        @include('includes.main.footer')

        <!-- Callback Modal -->
        <div id="callbackModal" class="fixed inset-0 z-50 callback-modal">
            <div class="absolute inset-0 bg-black bg-opacity-60" data-callback-close></div>
            <div class="relative z-10 max-w-md mx-auto mt-20 bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Замовити консультацію</h3>
                        <p class="text-sm text-gray-500 mt-1">Заповніть форму, і ми передзвонимо протягом робочого часу</p>
                    </div>
                    <button type="button" data-callback-close class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="callbackSuccess" class="hidden mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm"></div>
                    <div id="callbackError" class="hidden mb-4 rounded-2xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm"></div>
                    <form id="callbackForm" class="space-y-4" method="POST" action="{{ route('contact_request') }}">
                        @csrf
                        <div>
                            <label for="callback-name" class="block text-sm font-semibold text-gray-700 mb-2">Ім'я *</label>
                            <input id="callback-name" name="name" type="text" required class="w-full px-4 py-3 border-2 border-brand-line rounded-brand focus:border-brand-yellow focus:ring-0 transition-colors" placeholder="Ваше ім'я">
                        </div>
                        <div>
                            <label for="callback-phone" class="block text-sm font-semibold text-gray-700 mb-2">Номер телефону *</label>
                            <input id="callback-phone" name="phone" type="text" required class="w-full px-4 py-3 border-2 border-brand-line rounded-brand focus:border-brand-yellow focus:ring-0 transition-colors" placeholder="+380...">
                        </div>
                        <div>
                            <label for="callback-message" class="block text-sm font-semibold text-gray-700 mb-2">Повідомлення <span class="text-gray-500 font-normal">(необов'язково)</span></label>
                            <textarea id="callback-message" name="message" rows="3" class="w-full px-4 py-3 border-2 border-brand-line rounded-brand focus:border-brand-yellow focus:ring-0 transition-colors" placeholder="Коротко опишіть питання"></textarea>
                        </div>
                        <button id="callbackSubmit" type="submit" class="w-full btn-primary">
                            <span id="callbackSubmitText">Відправити</span>
                            <svg id="callbackSpinner" class="hidden ml-2 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Компонент уведомлений -->
        <toast-notification></toast-notification>
        
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Подключение Bootstrap JS (включает Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Vue --}}
    <script src="{{ mix('js/app.js') }}" onload="console.log('Vue app.js загружен')" onerror="console.error('Ошибка загрузки Vue app.js')"></script>

    <script>
        window.toggleWishlist = window.toggleWishlist || function(productId) {
            try {
                const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
                const index = wishlist.findIndex((item) => item.id == productId);
                if (index > -1) {
                    wishlist.splice(index, 1);
                } else {
                    wishlist.push({ id: productId });
                }
                localStorage.setItem('wishlist', JSON.stringify(wishlist));
                window.dispatchEvent(new Event('wishlist-updated'));
            } catch (e) {
                console.error(e);
            }
        };

        window.addToCart = window.addToCart || function(productId, name, price, image, articule, availability, quantity = 1,
            isWholesale = false, wholesalePrice = 0, wholesaleMinQuantity = 0) {
            try {
                const qty = Math.max(1, parseInt(quantity, 10) || 1);
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                const existing = cart.find((item) => item.id == productId);
                if (existing) {
                    existing.quantity = (existing.quantity || 1) + qty;
                } else {
                    const item = {
                        id: productId,
                        name,
                        price,
                        image,
                        articule,
                        availability,
                        quantity: qty,
                    };
                    if (isWholesale) {
                        item.isWholesale = true;
                        item.wholesalePrice = wholesalePrice;
                        item.wholesaleMinQuantity = wholesaleMinQuantity;
                    }
                    cart.push(item);
                }
                localStorage.setItem('cart', JSON.stringify(cart));
                window.dispatchEvent(new Event('cart-updated'));
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Додано в кошик',
                        message: name || 'Товар додано',
                        type: 'success',
                        product: {
                            name: name || 'Товар',
                            price: price,
                            image: image || '',
                        },
                        duration: 3200,
                    },
                }));
            } catch (e) {
                console.error(e);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-pcard-qty]').forEach((wrap) => {
                if (wrap.dataset.bound === '1') return;
                wrap.dataset.bound = '1';
                const input = wrap.querySelector('[data-qty-input]');
                if (!input) return;
                wrap.querySelector('[data-qty-minus]')?.addEventListener('click', () => {
                    input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
                });
                wrap.querySelector('[data-qty-plus]')?.addEventListener('click', () => {
                    input.value = Math.max(1, (parseInt(input.value, 10) || 1) + 1);
                });
                input.addEventListener('change', () => {
                    const value = parseInt(input.value, 10);
                    input.value = Number.isFinite(value) && value > 0 ? value : 1;
                });
            });

            document.querySelectorAll('[data-add-cart]').forEach((btn) => {
                if (btn.dataset.bound === '1') return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', () => {
                    const card = btn.closest('.pcard');
                    const qtyInput = card?.querySelector('[data-qty-input]');
                    const qty = Math.max(1, parseInt(qtyInput?.value || '1', 10) || 1);
                    window.addToCart(
                        btn.dataset.id,
                        btn.dataset.name,
                        parseFloat(btn.dataset.price || '0'),
                        btn.dataset.image || '',
                        btn.dataset.articule || 'Не вказано',
                        btn.dataset.availability || 'in_stock',
                        qty,
                        btn.dataset.wholesale === '1',
                        parseFloat(btn.dataset.wholesalePrice || '0'),
                        parseInt(btn.dataset.wholesaleMin || '0', 10)
                    );
                });
            });
        });
    </script>
    
    {{-- Fallback для Vue.js компонентов --}}
    <script>
        // Проверяем, загрузился ли Vue
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (typeof Vue === 'undefined' && typeof app === 'undefined') {
                    console.warn('Vue.js не загрузился, загружаем fallback версию');
                    
                    // Загружаем CDN версию Vue.js
                    const script = document.createElement('script');
                    script.src = 'https://unpkg.com/vue@3/dist/vue.global.js';
                    script.onload = function() {
                        console.log('Vue.js загружен из CDN');
                        // Здесь можно добавить fallback инициализацию компонентов
                    };
                    document.head.appendChild(script);
                }
            }, 2000); // Ждем 2 секунды
        });
    </script>

    {{-- Mask --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
    {{-- phoneUtils.js --}}
    <script src="{{ asset('js/phoneUtils.js') }}"></script>

    <!-- Initialize Swiper -->
    <script>
        // Инициализируем Swiper только если элемент существует
        document.addEventListener('DOMContentLoaded', function() {
            // Убрано инициализация Swiper для .recomendedSwiper, так как секция заменена на простую HTML
            console.log('Swiper инициализация пропущена - секция заменена на простую HTML');
        });
    </script>

    <script>
        $(document).ready(function() {
            console.log('Layout загружен');
            
            $('#input_phone1').inputmask('+38 (099) 999 99 99'); // Украина
            
            // Анимация появления элементов
            $('.animate-fade-in-up').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });
        
        // Дополнительная отладка
        window.addEventListener('load', function() {
            console.log('Layout: все ресурсы загружены');
        });
        
        window.addEventListener('error', function(e) {
            console.error('Layout: JavaScript ошибка:', e.error);
        });
    </script>
    
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WT7RLCN3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    {{-- Scripts --}}
    @yield('scripts')
    
    <script>
    (function () {
        if (window.__ZH_NAV_BOUND__) return;
        window.__ZH_NAV_BOUND__ = true;

        const cats = () => window.__MEGA_CATS__ || [];
        let activeId = null;
        const previewCache = {};

        const escapeHtml = (value) => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');

        const setBodyLock = (locked) => {
            document.body.style.overflow = locked ? 'hidden' : '';
        };

        const renderFlyout = async (cat) => {
            const flyout = document.getElementById('zh-mega-flyout');
            if (!flyout || !cat) return;
            const children = cat.children || [];
            let html = `
                <div class="cshop-mega__flyout-head">
                    <a href="/catalog/categoriya/${encodeURIComponent(cat.url)}" class="cshop-mega__flyout-title" data-mega-close>${escapeHtml(cat.name)}</a>
                    <a href="/catalog/categoriya/${encodeURIComponent(cat.url)}" class="cshop-mega__flyout-more" data-mega-close>
                        Усі товари категорії <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            `;

            if (children.length) {
                html += '<div class="cshop-mega__flyout-grid">';
                children.forEach((child) => {
                    html += `<div class="cshop-mega__group">
                        <a href="/catalog/categoriya/${encodeURIComponent(child.url)}" class="cshop-mega__group-title" data-mega-close>${escapeHtml(child.name)}</a>`;
                    if ((child.children || []).length) {
                        html += '<div class="cshop-mega__group-links">';
                        child.children.forEach((grand) => {
                            html += `<a href="/catalog/categoriya/${encodeURIComponent(grand.url)}" class="cshop-mega__sub" data-mega-close>${escapeHtml(grand.name)}</a>`;
                        });
                        html += '</div>';
                    }
                    html += '</div>';
                });
                html += '</div>';
                flyout.innerHTML = html;
                return;
            }

            flyout.innerHTML = html + '<div class="cshop-mega__preview-loading"><div class="cshop__spinner"></div></div>';
            if (!Object.prototype.hasOwnProperty.call(previewCache, cat.id)) {
                try {
                    const response = await fetch(`/catalog/categoriya/${encodeURIComponent(cat.url)}?preview=1`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                    });
                    const data = response.ok ? await response.json() : { products: [] };
                    previewCache[cat.id] = data.products || [];
                } catch (e) {
                    previewCache[cat.id] = [];
                }
            }

            const products = previewCache[cat.id] || [];
            if (!products.length) {
                flyout.innerHTML = html + '<p class="cshop__hint">У цій категорії поки немає товарів</p>';
                return;
            }

            html += '<div class="cshop-mega__minis">';
            products.forEach((item) => {
                const price = Math.round(parseFloat(item.price) || 0).toLocaleString('uk-UA');
                const img = item.image_path
                    ? `<img src="${escapeHtml(item.image_path)}" alt="${escapeHtml(item.name)}" loading="lazy">`
                    : '<i class="fas fa-image"></i>';
                html += `<a href="/catalog/${encodeURIComponent(item.url)}" class="cshop-mini" data-mega-close>
                    <span class="cshop-mini__img">${img}</span>
                    <span class="cshop-mini__body">
                        <span class="cshop-mini__name">${escapeHtml(item.name)}</span>
                        <strong class="cshop-mini__price">${price} грн</strong>
                    </span>
                </a>`;
            });
            html += '</div>';
            flyout.innerHTML = html;
        };

        const activateCat = (id) => {
            activeId = id;
            document.querySelectorAll('#zh-mega .cshop-mega__nav-item').forEach((btn) => {
                btn.classList.toggle('is-active', Number(btn.dataset.megaCat) === Number(id));
            });
            const cat = cats().find((row) => Number(row.id) === Number(id));
            renderFlyout(cat);
        };

        const openMega = () => {
            const mega = document.getElementById('zh-mega');
            if (!mega) return;
            mega.hidden = false;
            setBodyLock(true);
            const list = cats();
            activateCat(activeId || list[0]?.id);
        };

        const closeMega = () => {
            const mega = document.getElementById('zh-mega');
            if (!mega) return;
            mega.hidden = true;
            if (document.getElementById('zh-mobile')?.hidden !== false) {
                setBodyLock(false);
            }
        };

        const openMobile = () => {
            const mobile = document.getElementById('zh-mobile');
            if (!mobile) return;
            mobile.hidden = false;
            setBodyLock(true);
        };

        const closeMobile = () => {
            const mobile = document.getElementById('zh-mobile');
            if (!mobile) return;
            mobile.hidden = true;
            if (document.getElementById('zh-mega')?.hidden !== false) {
                setBodyLock(false);
            }
        };

        document.addEventListener('click', (e) => {
            if (e.target.closest('#zh-catalog-open')) {
                e.preventDefault();
                openMega();
                return;
            }
            if (e.target.closest('#zh-burger')) {
                e.preventDefault();
                openMobile();
                return;
            }
            if (e.target.closest('#zh-mobile-catalog')) {
                e.preventDefault();
                closeMobile();
                openMega();
                return;
            }
            if (e.target.closest('[data-mega-close]')) {
                closeMega();
                return;
            }
            if (e.target.closest('[data-mobile-close]')) {
                closeMobile();
                return;
            }
            const navItem = e.target.closest('[data-mega-cat]');
            if (navItem) {
                e.preventDefault();
                activateCat(navItem.dataset.megaCat);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeMega();
                closeMobile();
            }
        });

        window.addEventListener('open-catalog-mega', openMega);
        window.openCatalogMega = openMega;
        window.closeCatalogMega = closeMega;
    })();

    (function () {
        const modal = document.getElementById('callbackModal');
        if (!modal) {
            return;
        }

        const successBox = modal.querySelector('#callbackSuccess');
        const errorBox = modal.querySelector('#callbackError');
        const form = modal.querySelector('#callbackForm');
        const submitButton = modal.querySelector('#callbackSubmit');
        const submitText = modal.querySelector('#callbackSubmitText');
        const spinner = modal.querySelector('#callbackSpinner');
        const closeElements = modal.querySelectorAll('[data-callback-close]');
        const openElements = document.querySelectorAll('[data-callback-open]');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

        const toggleBodyScroll = (disable) => {
            document.body.style.overflow = disable ? 'hidden' : '';
        };

        const resetFeedback = () => {
            if (successBox) {
                successBox.classList.add('hidden');
                successBox.textContent = '';
            }
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }
        };

        const setLoadingState = (isLoading) => {
            if (!submitButton || !submitText) {
                return;
            }
            submitButton.disabled = isLoading;
            submitText.textContent = isLoading ? 'Відправлення...' : 'Відправити';
            if (spinner) {
                spinner.classList.toggle('hidden', !isLoading);
            }
        };

        window.openCallbackModal = function () {
            resetFeedback();
            if (form) {
                form.reset();
            }
            modal.classList.add('is-visible');
            toggleBodyScroll(true);
        };

        window.closeCallbackModal = function () {
            modal.classList.remove('is-visible');
            toggleBodyScroll(false);
        };

        closeElements.forEach((el) => {
            el.addEventListener('click', () => {
                closeCallbackModal();
            });
        });

        openElements.forEach((el) => {
            el.addEventListener('click', (event) => {
                event.preventDefault();
                openCallbackModal();
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeCallbackModal();
            }
        });

        if (form) {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                resetFeedback();

                const formData = new FormData(form);
                setLoadingState(true);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                        },
                        body: formData,
                    });

                    let responseData = {};
                    try {
                        responseData = await response.json();
                    } catch (parseError) {
                        responseData = {};
                    }

                    if (response.ok && responseData.success) {
                        if (successBox) {
                            successBox.textContent = responseData.message || 'Запит успішно відправлено.';
                            successBox.classList.remove('hidden');
                        }
                        form.reset();
                    } else {
                        let errorMessage = responseData.message || null;
                        if (!errorMessage && responseData.errors) {
                            const aggregated = Object.values(responseData.errors)
                                .reduce((acc, item) => acc.concat(item), []);
                            if (aggregated.length > 0) {
                                errorMessage = aggregated.join('\n');
                            }
                        }
                        if (!errorMessage) {
                            errorMessage = 'Сталася помилка. Спробуйте, будь ласка, пізніше.';
                        }
                        if (errorBox) {
                            errorBox.textContent = errorMessage;
                            errorBox.classList.remove('hidden');
                        }
                    }
                } catch (error) {
                    if (errorBox) {
                        errorBox.textContent = 'Сталася помилка. Спробуйте, будь ласка, пізніше.';
                        errorBox.classList.remove('hidden');
                    }
                    console.error('Callback form error:', error);
                } finally {
                    setLoadingState(false);
                }
            });
        }
    })();
    </script>
</body>

</html>
