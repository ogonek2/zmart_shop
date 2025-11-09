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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}" onload="console.log('App CSS загружен')" onerror="console.error('Ошибка загрузки App CSS')">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" onload="console.log('Swiper CSS загружен')" onerror="console.error('Ошибка загрузки Swiper CSS')" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="console.log('Font Awesome CSS загружен')" onerror="console.error('Ошибка загрузки Font Awesome CSS')">

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #f59e0b;
            --secondary-hover: #d97706;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gray-color: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #ffffff;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            line-height: 1.3;
        }

        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease-in-out;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--secondary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .navbar {
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand h1 {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 14px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            width: 40px;
            height: 40px;
            padding: 15px;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid var(--border-color);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .badge {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .shadow-custom {
            box-shadow: var(--shadow-lg);
        }

        .rounded-custom {
            border-radius: 16px;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        #submit_cart_ch_form{
            display: none !important;
        }

        /* Анимации */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Кастомные скроллбары */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-hover);
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

<body class="bg-light">
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
                            <input id="callback-name" name="name" type="text" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Ваше ім'я">
                        </div>
                        <div>
                            <label for="callback-phone" class="block text-sm font-semibold text-gray-700 mb-2">Номер телефону *</label>
                            <input id="callback-phone" name="phone" type="text" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="+380...">
                        </div>
                        <div>
                            <label for="callback-message" class="block text-sm font-semibold text-gray-700 mb-2">Повідомлення <span class="text-gray-500 font-normal">(необов'язково)</span></label>
                            <textarea id="callback-message" name="message" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Коротко опишіть питання"></textarea>
                        </div>
                        <button id="callbackSubmit" type="submit" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
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
    // Mobile Menu Functions
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        
        if (menu && overlay) {
            menu.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
    }

    function closeMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        
        if (menu && overlay) {
            menu.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Mobile Catalog Functions
    function toggleMobileCatalog() {
        const items = document.getElementById('mobileCatalogItems');
        const icon = document.getElementById('mobileCatalogIcon');
        
        if (items && icon) {
            items.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    }

    // Desktop Catalog Menu Functions
    function toggleCatalogMenu() {
        const menu = document.getElementById('catalogMenu');
        const overlay = document.getElementById('catalogMenuOverlay');
        
        if (menu && overlay) {
            menu.classList.remove('-translate-x-full');
            menu.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    function closeCatalogMenu() {
        const menu = document.getElementById('catalogMenu');
        const overlay = document.getElementById('catalogMenuOverlay');
        
        if (menu && overlay) {
            menu.classList.remove('translate-x-0');
            menu.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

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
