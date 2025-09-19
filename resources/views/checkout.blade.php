@extends('layouts.app')

@section('seo')
    <title>Оформлення замовлення - ZMART</title>
@endsection

@section('content')
    <div id="preloader" class="position-fixed top-0 start-0 w-100 h-100 bg-white d-flex align-items-center justify-content-center" style="z-index: 9999; display: none !important">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Завантаження...</span>
        </div>
    </div>
    
    <div class="bg-light min-vh-100 py-4">
        <div class="container">
            <!-- Заголовок -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-dark mb-3">Оформлення замовлення</h1>
                <p class="lead text-muted">Заповніть форму для завершення покупки</p>
            </div>
            
            <div class="row g-4">
                <!-- Основная форма -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form id="order-form">
                                <!-- Спосіб доставки -->
                                <div class="mb-5">
                                    <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                                        <i class="fas fa-truck text-primary me-2"></i>
                                        Спосіб доставки
                                    </h2>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="delivery-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-truck text-primary fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Нова Пошта</h5>
                                                            <p class="card-text text-muted small mb-0">Доставка до відділення або поштомату</p>
                                                        </div>
                                                        <span class="badge bg-success position-absolute top-0 end-0 m-2">Популярно</span>
                                                    </div>
                                                    <input type="radio" name="delivery_service" value="novaposhta" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="delivery-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-mail-bulk text-primary fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Укрпошта</h5>
                                                            <p class="card-text text-muted small mb-0">Доставка до відділення пошти</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="delivery_service" value="ukrposhta" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="delivery-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-motorcycle text-primary fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Кур'єрська доставка</h5>
                                                            <p class="card-text text-muted small mb-0">Доставка додому або в офіс</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="delivery_service" value="courier" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="delivery-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-store text-primary fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Самовивіз</h5>
                                                            <p class="card-text text-muted small mb-0">Забрати замовлення самостійно</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="delivery_service" value="pickup" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Деталі доставки -->
                                <div id="delivery-details" class="mb-5 d-none">
                                    <!-- Нова Пошта -->
                                    <div id="novaposhta-details" class="d-none">
                                        <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Місто <span class="text-danger">*</span>
                                            </label>
                                            <div class="position-relative">
                                                <select id="city-select" class="form-select">
                                                    <option value="">Оберіть місто</option>
                                                </select>
                                                <div id="city-loader" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                </div>
                                            </div>
                                        </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    Відділення <span class="text-danger">*</span>
                                                </label>
                                                <div class="position-relative">
                                                    <select id="warehouse-select" class="form-select">
                                                        <option value="">Оберіть відділення</option>
                                                    </select>
                                                    <div id="warehouse-loader" class="position-absolute top-50 end-0 translate-middle-y me-3 d-none">
                                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ручний ввод адреси -->
                                    <div id="manual-address-details" class="d-none">
                                        <div class="col-12">
                                            <label for="manual-address" class="form-label fw-semibold">
                                                Адреса доставки <span class="text-danger">*</span>
                                            </label>
                                            <textarea id="manual-address" class="form-control" rows="3" placeholder="Введіть повну адресу доставки"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Дані отримувача -->
                                <div class="mb-5">
                                    <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        Дані отримувача
                                    </h2>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-semibold">
                                                Ім'я <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Ваше ім'я">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="lastname" class="form-label fw-semibold">
                                                Прізвище <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Ваше прізвище">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="fathername" class="form-label fw-semibold">
                                                По батькові
                                            </label>
                                            <input type="text" id="fathername" name="fathername" class="form-control" placeholder="По батькові (необов'язково)">
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label fw-semibold">
                                                Телефон <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="+380XXXXXXXXX">
                                        </div>
                                        
                                        <div class="col-12">
                                            <label for="comment" class="form-label fw-semibold">
                                                Коментар до замовлення
                                            </label>
                                            <textarea id="comment" name="comment" class="form-control" rows="3" placeholder="Додаткові побажання або коментарі (необов'язково)"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Спосіб оплати -->
                                <div class="mb-5">
                                    <h2 class="h4 fw-bold text-dark mb-4 d-flex align-items-center">
                                        <i class="fas fa-credit-card text-primary me-2"></i>
                                        Спосіб оплати
                                    </h2>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="payment-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-money-bill-wave text-success fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Накладений платіж</h5>
                                                            <p class="card-text text-muted small mb-0">Оплата готівкою при отриманні</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="payment_method" value="cash" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="payment-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-university text-success fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Банківський переказ</h5>
                                                            <p class="card-text text-muted small mb-0">Оплата на карту банку</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="payment_method" value="bank_transfer" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="payment-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-credit-card text-success fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Оплата картою при отриманні</h5>
                                                            <p class="card-text text-muted small mb-0">Оплата картою при доставці</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="payment_method" value="card_payment" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="payment-option card h-100 border-2 border-light hover-shadow position-relative">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                                            <i class="fas fa-store text-success fs-5"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="card-title mb-1">Оплата при самовивозі</h5>
                                                            <p class="card-text text-muted small mb-0">Оплата при отриманні в магазині</p>
                                                        </div>
                                                    </div>
                                                    <input type="radio" name="payment_method" value="pickup_payment" class="form-check-input position-absolute top-0 end-0 m-2" style="opacity: 0;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Інформація про способи оплати -->
                                <div id="payment-info" class="mb-4 d-none">
                                    <div id="payment-info-cash" class="alert alert-info d-none">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Накладений платіж:</strong> Оплата готівкою при отриманні замовлення. Кур'єр матиме з собою чек та квитанцію.
                                    </div>
                                    
                                    <div id="payment-info-bank_transfer" class="alert alert-success d-none">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Банківський переказ:</strong> Після підтвердження замовлення ми надішлемо вам реквізити для оплати на email.
                                    </div>
                                    
                                    <div id="payment-info-card_payment" class="alert alert-warning d-none">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Оплата картою при отриманні:</strong> Кур'єр матиме з собою термінал для оплати картою. Підтримуються всі основні платіжні системи.
                                    </div>
                                    
                                    <div id="payment-info-pickup_payment" class="alert alert-info d-none">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Оплата при самовивозі:</strong> Оплата готівкою або картою при отриманні замовлення в нашому магазині.
                                    </div>
                                </div>

                                <!-- Кнопка отправки -->
                                <div class="pt-4 border-top">
                                    <button type="submit" id="submit-order" class="btn btn-success btn-lg w-100 py-3">
                                        <i class="fas fa-check me-2"></i>
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
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 2rem;">
                        <div class="card-body p-4">
                            <h3 class="h5 fw-bold text-dark mb-4 d-flex align-items-center">
                                <i class="fas fa-shopping-cart text-primary me-2"></i>
                                Ваше замовлення
                            </h3>
                            <cart-list></cart-list>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }
        
        .delivery-option,
        .payment-option {
            cursor: pointer;
        }
        
        .delivery-option:hover,
        .payment-option:hover {
            border-color: #0d6efd !important;
        }
        
        .delivery-option.active,
        .payment-option.active {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05) !important;
        }
        
        .search-result-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s ease;
        }
        
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
    </style>
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
                $('.delivery-option').removeClass('active border-primary').addClass('border-light');
                
                // Добавляем активный класс к выбранной опции
                $(this).closest('.delivery-option').removeClass('border-light').addClass('active border-primary');
                
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
                $('.payment-option').removeClass('active border-success').addClass('border-light');
                
                // Добавляем активный класс к выбранной опции
                $(this).closest('.payment-option').removeClass('border-light').addClass('active border-success');
                
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
                $('#delivery-details').removeClass('d-none');
                $('#novaposhta-details').addClass('d-none');
                $('#manual-address-details').addClass('d-none');
                
                if (service === 'novaposhta') {
                    $('#novaposhta-details').removeClass('d-none');
                    loadCities();
                } else if (service === 'pickup') {
                    // Для самовывоза не показываем детали
                    $('#delivery-details').addClass('d-none');
                } else {
                    $('#manual-address-details').removeClass('d-none');
                }
            }

            // Функция показа информации о способе оплаты
            function showPaymentInfo(method) {
                $('#payment-info').removeClass('d-none');
                $('.alert').addClass('d-none');
                $(`#payment-info-${method}`).removeClass('d-none');
            }

            // Загрузка городов
            function loadCities() {
                $('#city-loader').removeClass('d-none');
                
                $.get('/cities')
                    .done(function(data) {
                        cities = data;
                        populateCitySelect(data);
                    })
                    .fail(function() {
                        console.error('Ошибка загрузки городов');
                    })
                    .always(function() {
                        $('#city-loader').addClass('d-none');
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
                $('#warehouse-loader').removeClass('d-none');
                
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
                        $('#warehouse-loader').addClass('d-none');
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
                $('#preloader').show();
                
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
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                // Отправка заказа
                $.ajax({
                    type: 'POST',
                    url: '/order-submit',
                    data: formData,
                    success: function(response) {
                        $('#preloader').hide();
                        localStorage.removeItem('cart');
                        window.location.href = '/thank-you';
                    },
                    error: function(xhr) {
                        $('#preloader').hide();
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