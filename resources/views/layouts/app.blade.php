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
            menu.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }

    function closeCatalogMenu() {
        const menu = document.getElementById('catalogMenu');
        const overlay = document.getElementById('catalogMenuOverlay');
        
        if (menu && overlay) {
            menu.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    // Navigation initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Catalog Search
        const catalogSearch = document.getElementById('catalogSearch');
        const catalogList = document.getElementById('catalogList');
        
        if (catalogSearch && catalogList) {
            const catalogItems = catalogList.querySelectorAll('.catalog-item');
            
            if (catalogItems.length > 0) {
                catalogSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    
                    catalogItems.forEach(item => {
                        const itemText = item.textContent.toLowerCase();
                        
                        if (searchTerm === '' || itemText.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        }
        
        // Close mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
                closeCatalogMenu();
            }
        });
        
        // Close mobile menu on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });
    });
    </script>
</body>

</html>
