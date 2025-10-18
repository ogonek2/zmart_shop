@extends('layouts.app')

@section('seo')
    <title>Оформлення замовлення - ZMART</title>
    <meta name="description" content="Оформлення замовлення в інтернет-магазині ZMART. Швидка доставка по всій Україні.">
@endsection

@section('styles')
    <style>
        .delivery-option.active,
        .payment-option.active {
            border-color: #10b981 !important;
            background-color: rgba(16, 185, 129, 0.05) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }
        
        .delivery-option.active .group,
        .payment-option.active .group {
            transform: scale(1.02);
        }
        
        .search-result-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #e5e7eb;
            transition: background-color 0.2s ease;
        }
        
        .search-result-item:hover {
            background-color: #f9fafb;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        /* Анимации для появления элементов */
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
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
    </style>
@endsection

@section('content')
    <!-- Preloader -->
    <div id="preloader" class="fixed inset-0 bg-white z-50 flex items-center justify-center hidden">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
            <p class="mt-4 text-gray-600 font-medium">Завантаження...</p>
        </div>
    </div>
    
    <!-- Breadcrumbs -->
    <section class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-gray-900 font-medium">Оформлення замовлення</span>
            </nav>
        </div>
    </section>

    <!-- Header -->
    <section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Оформлення замовлення</h1>
                <p class="text-xl text-emerald-100">Заповніть форму для завершення покупки</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Основная форма -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-8">
                            <form id="order-form">
                                <!-- Спосіб доставки -->
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-truck text-white"></i>
                                        </div>
                                        Спосіб доставки
                                    </h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="delivery-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-emerald-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-truck text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Нова Пошта</h5>
                                                    <p class="text-sm text-gray-600">Доставка до відділення або поштомату</p>
                                                </div>
                                                <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded-full">Популярно</span>
                                            </div>
                                            <input type="radio" name="delivery_service" value="novaposhta" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                        
                                        <div class="delivery-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-emerald-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-motorcycle text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Кур'єрська доставка</h5>
                                                    <p class="text-sm text-gray-600">Доставка додому або в офіс</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="delivery_service" value="courier" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                        
                                        <div class="delivery-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-emerald-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-store text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Самовивіз</h5>
                                                    <p class="text-sm text-gray-600">Забрати замовлення самостійно</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="delivery_service" value="pickup" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Деталі доставки -->
                                <div id="delivery-details" class="mb-8 hidden">
                                    <!-- Нова Пошта -->
                                    <div id="novaposhta-details" class="hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                                    Місто <span class="text-red-500">*</span>
                                            </label>
                                                <div class="relative">
                                                    <select id="city-select" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors">
                                                    <option value="">Оберіть місто</option>
                                                </select>
                                                    <div id="city-loader" class="absolute top-1/2 right-3 -translate-y-1/2 hidden">
                                                        <div class="w-5 h-5 border-2 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
                                                    </div>
                                            </div>
                                        </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                                    Відділення <span class="text-red-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <select id="warehouse-select" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors">
                                                        <option value="">Оберіть відділення</option>
                                                    </select>
                                                    <div id="warehouse-loader" class="absolute top-1/2 right-3 -translate-y-1/2 hidden">
                                                        <div class="w-5 h-5 border-2 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ручний ввод адреси -->
                                    <div id="manual-address-details" class="hidden">
                                        <div>
                                            <label for="manual-address" class="block text-sm font-bold text-gray-700 mb-2">
                                                Адреса доставки <span class="text-red-500">*</span>
                                            </label>
                                            <textarea id="manual-address" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" rows="3" placeholder="Введіть повну адресу доставки"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Дані отримувача -->
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        Дані отримувача
                                    </h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                                Ім'я <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="name" name="name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Ваше ім'я">
                                        </div>
                                        
                                        <div>
                                            <label for="lastname" class="block text-sm font-bold text-gray-700 mb-2">
                                                Прізвище <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="lastname" name="lastname" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Ваше прізвище">
                                        </div>
                                        
                                        <div>
                                            <label for="fathername" class="block text-sm font-bold text-gray-700 mb-2">
                                                По батькові
                                            </label>
                                            <input type="text" id="fathername" name="fathername" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="По батькові (необов'язково)">
                                        </div>
                                        
                                        <div>
                                            <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">
                                                Телефон <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="+380XXXXXXXXX">
                                        </div>
                                        
                                        <div class="md:col-span-2">
                                            <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">
                                                Коментар до замовлення
                                            </label>
                                            <textarea id="comment" name="comment" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" rows="3" placeholder="Додаткові побажання або коментарі (необов'язково)"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Спосіб оплати -->
                                <div class="mb-8">
                                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-credit-card text-white"></i>
                                        </div>
                                        Спосіб оплати
                                    </h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="payment-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Накладений платіж</h5>
                                                    <p class="text-sm text-gray-600">Оплата готівкою при отриманні</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="payment_method" value="cash" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                        
                                        <div class="payment-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-university text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Банківський переказ</h5>
                                                    <p class="text-sm text-gray-600">Оплата на карту банку</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="payment_method" value="bank_transfer" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                        
                                        <div class="payment-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-credit-card text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Оплата картою при отриманні</h5>
                                                    <p class="text-sm text-gray-600">Оплата картою при доставці</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="payment_method" value="card_payment" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                        
                                        <div class="payment-option group cursor-pointer bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-lg transition-all duration-300 relative">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl flex items-center justify-center mr-4">
                                                    <i class="fas fa-store text-white text-lg"></i>
                                                        </div>
                                                <div class="flex-1">
                                                    <h5 class="font-bold text-gray-900 mb-1">Оплата при самовивозі</h5>
                                                    <p class="text-sm text-gray-600">Оплата при отриманні в магазині</p>
                                                </div>
                                            </div>
                                            <input type="radio" name="payment_method" value="pickup_payment" class="absolute top-3 right-3 w-5 h-5 opacity-0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Інформація про способи оплати -->
                                <div id="payment-info" class="mb-6 hidden">
                                    <div id="payment-info-cash" class="bg-blue-50 border border-blue-200 rounded-xl p-4 hidden">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                            <div>
                                                <strong class="text-blue-900">Накладений платіж:</strong> 
                                                <span class="text-blue-800">Оплата готівкою при отриманні замовлення. Кур'єр матиме з собою чек та квитанцію.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="payment-info-bank_transfer" class="bg-green-50 border border-green-200 rounded-xl p-4 hidden">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-green-500 mt-1 mr-3"></i>
                                            <div>
                                                <strong class="text-green-900">Банківський переказ:</strong> 
                                                <span class="text-green-800">Після підтвердження замовлення ми надішлемо вам реквізити для оплати на email.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="payment-info-card_payment" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 hidden">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                                            <div>
                                                <strong class="text-yellow-900">Оплата картою при отриманні:</strong> 
                                                <span class="text-yellow-800">Кур'єр матиме з собою термінал для оплати картою. Підтримуються всі основні платіжні системи.</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="payment-info-pickup_payment" class="bg-blue-50 border border-blue-200 rounded-xl p-4 hidden">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                            <div>
                                                <strong class="text-blue-900">Оплата при самовивозі:</strong> 
                                                <span class="text-blue-800">Оплата готівкою або картою при отриманні замовлення в нашому магазині.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопка отправки -->
                                <div class="pt-6 border-t border-gray-200">
                                    <button type="submit" id="submit-order" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-4 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        <i class="fas fa-check mr-2"></i>
                                        Оформити замовлення
                                    </button>
                                </div>

                                <!-- CSRF -->
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Боковая панель с корзиной -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-8">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shopping-cart text-white text-sm"></i>
                                </div>
                                Ваше замовлення
                            </h3>
                            <cart-list></cart-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            let cities = [];
            let warehouses = [];
            let selectedCityRef = '';

            // Обработчики для опций доставки
            $('input[name="delivery_service"]').on('change', function() {
                const service = $(this).val();
                
                // Убираем активный класс со всех опций
                $('.delivery-option').removeClass('active');
                
                // Добавляем активный класс к выбранной опции
                $(this).closest('.delivery-option').addClass('active');
                
                // Показываем соответствующие детали доставки
                showDeliveryDetails(service);
            });

            // Клик по карточкам доставки
            $('.delivery-option').on('click', function() {
                const radio = $(this).find('input[type="radio"]');
                radio.prop('checked', true).trigger('change');
            });

            // Обработчики для опций оплаты
            $('input[name="payment_method"]').on('change', function() {
                const method = $(this).val();
                
                // Убираем активный класс со всех опций
                $('.payment-option').removeClass('active');
                
                // Добавляем активный класс к выбранной опции
                $(this).closest('.payment-option').addClass('active');
                
                // Показываем информацию о способе оплаты
                showPaymentInfo(method);
            });

            // Клик по карточкам оплаты
            $('.payment-option').on('click', function() {
                const radio = $(this).find('input[type="radio"]');
                radio.prop('checked', true).trigger('change');
            });

            // Инициализация Select2 для городов
            $('#city-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Оберіть місто',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Міста не знайдено";
                    },
                    searching: function() {
                        return "Пошук...";
                    },
                    inputTooShort: function() {
                        return "";
                    }
                },
                minimumInputLength: 0
            });

            // Инициализация Select2 для отделений
            $('#warehouse-select').select2({
                theme: 'bootstrap-5',
                placeholder: 'Оберіть відділення',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Відділення не знайдено";
                    },
                    searching: function() {
                        return "Пошук...";
                    },
                    inputTooShort: function() {
                        return "";
                    }
                },
                minimumInputLength: 0
            });

            // Функция показа деталей доставки
            function showDeliveryDetails(service) {
                $('#delivery-details').removeClass('hidden');
                $('#novaposhta-details').addClass('hidden');
                $('#manual-address-details').addClass('hidden');
                
                if (service === 'novaposhta') {
                    $('#novaposhta-details').removeClass('hidden');
                    loadCities();
                } else if (service === 'pickup') {
                    // Для самовывоза не показываем детали
                    $('#delivery-details').addClass('hidden');
                } else {
                    $('#manual-address-details').removeClass('hidden');
                }
            }

            // Функция показа информации о способе оплаты
            function showPaymentInfo(method) {
                $('#payment-info').removeClass('hidden');
                $('#payment-info > div').addClass('hidden');
                $(`#payment-info-${method}`).removeClass('hidden');
            }

            // Загрузка городов
            function loadCities() {
                $('#city-loader').removeClass('hidden');
                
                $.get('/cities')
                    .done(function(data) {
                        cities = data;
                        populateCitySelect(data);
                    })
                    .fail(function() {
                        console.error('Ошибка загрузки городов');
                    })
                    .always(function() {
                        $('#city-loader').addClass('hidden');
                    });
            }

            // Заполнение селекта городов
            function populateCitySelect(citiesData) {
                const $select = $('#city-select');
                $select.empty().append('<option value="">Оберіть місто</option>');
                
                citiesData.forEach(city => {
                    $select.append(`<option value="${city.Ref}">${city.Description}</option>`);
                });
                
                // Переинициализируем Select2
                $select.trigger('change.select2');
            }

            // Обработчик изменения селекта городов
            $('#city-select').on('change', function() {
                const cityRef = $(this).val();
                
                if (cityRef) {
                    selectedCityRef = cityRef;
                    loadWarehouses(cityRef);
                }
            });


            // Загрузка отделений
            function loadWarehouses(cityRef) {
                $('#warehouse-loader').removeClass('hidden');
                
                $.ajax({
                    method: 'POST',
                    url: '/warehouses',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({ cityRef }),
                    success: function(data) {
                        warehouses = data;
                        populateWarehouseSelect(data);
                    },
                    error: function() {
                        console.error('Ошибка загрузки отделений');
                    },
                    complete: function() {
                        $('#warehouse-loader').addClass('hidden');
                    }
                });
            }

            // Заполнение селекта отделений
            function populateWarehouseSelect(warehousesData) {
                const $select = $('#warehouse-select');
                $select.empty().append('<option value="">Оберіть відділення</option>');
                
                warehousesData.forEach(warehouse => {
                    $select.append(`<option value="${warehouse.Ref}">${warehouse.Description}</option>`);
                });
                
                // Переинициализируем Select2
                $select.trigger('change.select2');
            }


            // Валидация формы
            function validateForm() {
                let isValid = true;
                
                // Очистка предыдущих ошибок
                $('input, textarea, select').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                
                // Проверка способа доставки
                const deliveryService = $('input[name="delivery_service"]:checked').val();
                if (!deliveryService) {
                    showError('Оберіть спосіб доставки');
                    isValid = false;
                }
                
                // Проверка деталей доставки
                if (deliveryService === 'novaposhta') {
                    const city = $('#city-select').val();
                    const warehouse = $('#warehouse-select').val();
                    
                    if (!city) {
                        showFieldError('#city-select', 'Оберіть місто');
                        isValid = false;
                    }
                    
                    if (!warehouse) {
                        showFieldError('#warehouse-select', 'Оберіть відділення');
                        isValid = false;
                    }
                } else if (deliveryService && deliveryService !== 'pickup') {
                    const manualAddress = $('#manual-address').val().trim();
                    if (!manualAddress || manualAddress.length < 2) {
                        showFieldError('#manual-address', 'Введіть адресу доставки');
                        isValid = false;
                    }
                }
                
                // Проверка данных получателя
                const name = $('#name').val().trim();
                const lastname = $('#lastname').val().trim();
                const phone = $('#phone').val().trim();
                
                if (!name || name.length < 2) {
                    showFieldError('#name', 'Введіть коректне ім\'я');
                    isValid = false;
                }
                
                if (!lastname || lastname.length < 2) {
                    showFieldError('#lastname', 'Введіть коректне прізвище');
                    isValid = false;
                }
                
                if (!phone || phone.length < 10) {
                    showFieldError('#phone', 'Введіть коректний номер телефону');
                    isValid = false;
                }
                
                // Проверка способа оплаты
                const paymentMethod = $('input[name="payment_method"]:checked').val();
                if (!paymentMethod) {
                    showError('Оберіть спосіб оплати');
                    isValid = false;
                }
                
                return isValid;
            }

            // Показать ошибку поля
            function showFieldError(selector, message) {
                $(selector).addClass('is-invalid');
                $(selector).after(`<div class="invalid-feedback">${message}</div>`);
            }

            // Показать общую ошибку
            function showError(message) {
                alert(message);
            }

            // Отправка формы
            $('#order-form').on('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }
                
                // Проверка корзины
                const cart = localStorage.getItem('cart');
                if (!cart || cart === '[]' || cart === '{}' || cart === 'null') {
                    alert('Корзина пуста. Пожалуйста, добавьте товары в корзину.');
                    return;
                }
                
                // Показать прелоадер
                $('#preloader').removeClass('hidden');
                
                // Получаем общую сумму из скрытого поля
                const totalPrice = $('#total_price_stream').val();
                
                // Подготовка данных
                const formData = {
                    delivery_service: $('input[name="delivery_service"]:checked').val(),
                    city: $('#city-select option:selected').text(),
                    warehouse: $('#warehouse-select option:selected').text(),
                    manual_address: $('#manual-address').val().trim(),
                    name: $('#name').val().trim(),
                    lastname: $('#lastname').val().trim(),
                    fathername: $('#fathername').val().trim(),
                    phone: $('#phone').val().trim(),
                    comment: $('#comment').val().trim(),
                    payment: $('input[name="payment_method"]:checked').val(),
                    cart: cart,
                    total_price: totalPrice,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                // Отправка заказа
                $.ajax({
                    type: 'POST',
                    url: '/order-submit',
                    data: formData,
                    success: function(response) {
                        $('#preloader').addClass('hidden');
                        localStorage.removeItem('cart');
                        window.location.href = '/thank-you';
                    },
                    error: function(xhr) {
                        $('#preloader').addClass('hidden');
                        let errorMessage = 'Ошибка при отправке заказа. Попробуйте еще раз.';
                        
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                errorMessage = response.error;
                            }
                        } catch (e) {
                            console.error('Could not parse error response:', e);
                        }
                        
                        alert(errorMessage);
                    }
                });
            });
        });
    </script>
@endsection