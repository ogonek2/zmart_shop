<div class="recommended-products">
    <div class="section-header text-center mb-4">
        <h3 class="section-title">
            <i class="fas fa-star text-warning me-2"></i>
            Рекомендуемые товары
        </h3>
        <p class="section-subtitle text-muted">Лучшие предложения для вас</p>
    </div>

    <!-- Индикатор загрузки -->
    <div id="loading-indicator" class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
        <p class="mt-2 text-muted">Загружаем рекомендуемые товары...</p>
    </div>

    <!-- Контейнер для слайдера -->
    <div id="swiper-container" class="d-none">
        <div class="swiper recommendedSwiper">
            <div class="swiper-wrapper" id="swiper-wrapper">
                <!-- Товары будут загружены через Ajax -->
            </div>

            <!-- Навигация Swiper -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            <!-- Пагинация -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Сообщение об ошибке -->
    <div id="error-message" class="text-center py-4 d-none">
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Не удалось загрузить рекомендуемые товары. Попробуйте обновить страницу.
        </div>
    </div>
</div>

<style>
    .recommended-products {
        margin: 3rem 0;
        padding: 2rem 0;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 20px;
    }

    .section-header {
        margin-bottom: 2rem;
    }

    .section-title {
        color: var(--dark-color);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .recommendedSwiper {
        padding: 1rem 0;
        position: relative;
    }

    .swiper-slide {
        height: auto;
        padding: 0.25rem;
    }

    /* Стили для навигации Swiper */
    .swiper-button-next,
    .swiper-button-prev {
        background: var(--primary-color);
        color: white;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        z-index: 10;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: var(--primary-hover);
        transform: scale(1.1);
        box-shadow: var(--shadow-lg);
    }

    .swiper-button-next::after,
    .swiper-button-prev::after {
        font-size: 1.2rem;
        font-weight: 600;
    }

    /* Стили для пагинации */
    .swiper-pagination {
        position: relative;
        margin-top: 1rem;
    }

    .swiper-pagination-bullet {
        background: var(--primary-color);
        opacity: 0.5;
        transition: all 0.3s ease;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
        transform: scale(1.2);
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .recommended-products {
            margin: 2rem 0;
            padding: 1.5rem 0;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .section-subtitle {
            font-size: 1rem;
        }

        .swiper-button-next,
        .swiper-button-prev {
            width: 36px;
            height: 36px;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .section-title {
            font-size: 1.25rem;
        }

        .swiper-slide {
            padding: 0.15rem;
        }
    }

    /* Анимация появления */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let recommendedSwiper = null;
    
    // Функция загрузки рекомендуемых товаров
    async function loadRecommendedProducts() {
        try {
            console.log('Начинаем загрузку рекомендуемых товаров...');
            
            const response = await fetch('/api/recommended-products');
            console.log('Ответ получен:', response);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Данные получены:', data);
            
            if (data.success && data.products.length > 0) {
                console.log('Товары успешно загружены, количество:', data.products.length);
                
                // Скрываем индикатор загрузки
                document.getElementById('loading-indicator').classList.add('d-none');
                
                // Показываем контейнер слайдера
                document.getElementById('swiper-container').classList.remove('d-none');
                
                // Заполняем слайдер товарами
                populateSwiper(data.products);
                
                // Даем Vue время на инициализацию компонентов
                setTimeout(() => {
                    // Инициализируем Swiper
                    initializeSwiper();
                }, 100);
                
            } else {
                console.log('Нет товаров или ошибка в данных:', data);
                throw new Error(data.message || 'Нет данных');
            }
            
        } catch (error) {
            console.error('Ошибка загрузки рекомендуемых товаров:', error);
            
            // Скрываем индикатор загрузки
            document.getElementById('loading-indicator').classList.add('d-none');
            
            // Показываем сообщение об ошибке
            document.getElementById('error-message').classList.remove('d-none');
            
            // Добавляем детали ошибки в сообщение
            const errorMessage = document.querySelector('#error-message .alert');
            if (errorMessage) {
                errorMessage.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Не удалось загрузить рекомендуемые товары: ${error.message}
                    <br><small class="mt-2 d-block">Попробуйте обновить страницу или проверьте консоль браузера</small>
                `;
            }
        }
    }
    
    // Функция заполнения слайдера товарами
    function populateSwiper(products) {
        console.log('Начинаем заполнение слайдера товарами:', products);
        
        const swiperWrapper = document.getElementById('swiper-wrapper');
        if (!swiperWrapper) {
            console.error('Элемент swiper-wrapper не найден!');
            return;
        }
        
        swiperWrapper.innerHTML = '';
        console.log('Очистили swiper-wrapper');
        
        products.forEach((product, index) => {
            console.log(`Создаем слайд для товара ${index + 1}:`, product);
            
            const slide = document.createElement('div');
            slide.className = 'swiper-slide fade-in';
            
            // Создаем Vue компонент product-card правильно
            const productCard = document.createElement('product-card');
            
            // Устанавливаем все атрибуты через setAttribute
            productCard.setAttribute('id', product.id);
            productCard.setAttribute('name', escapeHtml(product.name));
            productCard.setAttribute('price', product.price);
            productCard.setAttribute('discount', product.discount || 0);
            productCard.setAttribute('image_path', escapeUrl(product.image_path) || 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Нет+фото');
            productCard.setAttribute('url', escapeUrl(product.url));
            productCard.setAttribute('articule', escapeHtml(product.articule || 'Не указан'));
            productCard.setAttribute('rating', '4.5');
            productCard.setAttribute('availability', product.availability || 1);
            productCard.setAttribute('description', escapeHtml(product.description || ''));
            productCard.setAttribute('variant', 'featured');
            productCard.setAttribute('show-rating', 'true');
            productCard.setAttribute('show-compare', 'false');
            productCard.setAttribute('show-additional-info', 'true');
            
            // Добавляем компонент в слайд
            slide.appendChild(productCard);
            
            swiperWrapper.appendChild(slide);
            console.log(`Слайд ${index + 1} добавлен в swiper-wrapper`);
        });
        
        console.log(`Всего создано слайдов: ${products.length}`);
    }
    
    // Функция радикального экранирования HTML
    function escapeHtml(text) {
        if (!text) return '';
        
        // Радикальное экранирование - оставляем только буквы, цифры и пробелы
        let cleaned = text.toString()
            // Убираем все HTML теги
            .replace(/<[^>]*>/g, '')
            // Убираем все специальные символы, кроме букв, цифр и пробелов
            .replace(/[^\w\sа-яёіїєА-ЯЁІЇЄ]/g, '')
            // Убираем лишние пробелы
            .replace(/\s+/g, ' ')
            .trim();
        
        // Если после очистки ничего не осталось, возвращаем "Без описания"
        if (!cleaned || cleaned.length < 2) {
            return 'Без описания';
        }
        
        // Ограничиваем длину описания
        if (cleaned.length > 100) {
            cleaned = cleaned.substring(0, 97) + '...';
        }
        
        return cleaned;
    }
    
    // Функция экранирования для URL и изображений
    function escapeUrl(text) {
        if (!text) return '';
        
        // Для URL и изображений оставляем только безопасные символы
        let cleaned = text.toString()
            // Убираем все HTML теги
            .replace(/<[^>]*>/g, '')
            // Убираем все опасные символы, оставляем только буквы, цифры, дефисы и точки
            .replace(/[^\w\-\.\/\:]/g, '')
            .trim();
        
        return cleaned || 'bez-url';
    }
    
    // Функция инициализации Swiper
    function initializeSwiper() {
        console.log('Начинаем инициализацию Swiper...');
        
        const swiperElement = document.querySelector('.recommendedSwiper');
        if (!swiperElement) {
            console.error('Элемент .recommendedSwiper не найден!');
            return;
        }
        
        console.log('Элемент .recommendedSwiper найден:', swiperElement);
        
        if (recommendedSwiper) {
            console.log('Swiper уже инициализирован, уничтожаем старый экземпляр...');
            recommendedSwiper.destroy(true, true);
            recommendedSwiper = null;
        }
        
        try {
            console.log('Создаем новый экземпляр Swiper...');
            
            recommendedSwiper = new Swiper('.recommendedSwiper', {
                slidesPerView: 1,
                spaceBetween: 15,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    1200: {
                        slidesPerView: 5,
                        spaceBetween: 20,
                    },
                    1400: {
                        slidesPerView: 6,
                        spaceBetween: 20,
                    }
                },
                // Улучшенные настройки
                grabCursor: true,
                keyboard: {
                    enabled: true,
                    onlyInViewport: true,
                },
                mousewheel: {
                    forceToAxis: true,
                },
                // Эффекты
                effect: 'slide',
                speed: 600,
            });
            
            console.log('Swiper успешно инициализирован:', recommendedSwiper);
            
            // Проверяем количество слайдов
            const slides = swiperElement.querySelectorAll('.swiper-slide');
            console.log('Количество слайдов в Swiper:', slides.length);
            
        } catch (error) {
            console.error('Ошибка инициализации Swiper:', error);
        }
    }
    
    // Загружаем товары при загрузке страницы
    loadRecommendedProducts();
    
    // Обновляем товары каждые 30 минут
    setInterval(loadRecommendedProducts, 30 * 60 * 1000);
});
</script>
