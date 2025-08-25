@extends('layouts.app')

@section('styles')
    <style>
        #phone-validation-icon {
            font-size: 1.2rem;
        }

        .valid-phone {
            color: green;
        }

        .invalid-phone {
            color: red;
        }
    </style>
@endsection

@section('seo')
    <title>Головна сторінка</title>
@endsection

@section('content')
    <div id="preloader"
        class="position-fixed top-0 start-0 w-100 h-100 bg-white d-flex align-items-center justify-content-center"
        style="z-index: 9999; display: none !important">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Завантаження...</span>
        </div>
    </div>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-7">
                <form class="form-horizontal" id="order-form">
                    <h1>
                        Оформлення замовлення
                    </h1>
                    <div class="container mt-4">
                        <label class="form-label d-block mb-3 fs-5">
                            <b>
                                Доставка
                            </b>
                        </label>
                        <div class="d-flex flex-column gap-3" id="deliveryServiceGroup">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="delivery_service" id="radio-np"
                                    value="novaposhta">
                                <label class="form-check-label d-flex align-items-center" for="radio-np">
                                    <svg version="1.1" id="Шар_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 755 755.1"
                                        style="enable-background:new 0 0 755 755.1;" xml:space="preserve" width="20px"
                                        height="20px">
                                        <path fill="#DA291C" class="st0"
                                            d="M443.9,612.8v-157H310.7v157H208.9l123.4,123.7c24.8,24.8,65,24.8,89.9,0l123.7-123.7L443.9,612.8L443.9,612.8z   M142.3,546.2V208.9L18.6,332.4c-24.8,24.8-24.8,65,0,89.9L142.3,546.2z M310.7,142.1v157h133.2v-157h102L422.2,18.6  c-24.8-24.8-65-24.8-89.9,0L208.9,142.1H310.7z M736,332.4L612.5,208.9v337l123.7-123.7c24.8-24.6,25.1-64.8,0.8-89.6  c0,0,0,0-0.3-0.3c0,0,0,0-0.5,0H736z" />
                                    </svg>
                                    <span class="ms-1">
                                        Нова Пошта (Відділення / Поштомат)
                                    </span>
                                </label>
                                <!-- Город -->
                                <div id="city-container" class="mt-2 d-none">
                                    <label for="city-select" class="form-label">Оберіть місто:</label>

                                    <div id="city-loader" class="text-primary mb-2 d-none">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Завантаження...</span>
                                        </div> Завантаження міст...
                                    </div>

                                    <select id="city-select" class="selectpicker form-control" data-live-search="true"
                                        title="Оберіть місто"></select>
                                </div>

                                <!-- Відділення -->
                                <div id="warehouse-container" class="mt-3 d-none">
                                    <label for="warehouse-select" class="form-label">Оберіть відділення:</label>

                                    <div id="warehouse-loader" class="text-primary mb-2 d-none">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Завантаження...</span>
                                        </div> Завантаження відділень...
                                    </div>

                                    <select id="warehouse-select" class="selectpicker form-control" data-live-search="true"
                                        title="Оберіть відділення"></select>
                                </div>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="delivery_service" id="radio-courier"
                                    value="courier_nova">
                                <label class="form-check-label d-flex align-items-center" for="radio-courier">
                                    <svg version="1.1" id="Шар_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 755 755.1"
                                        style="enable-background:new 0 0 755 755.1;" xml:space="preserve" width="20px"
                                        height="20px">
                                        <path fill="#DA291C" class="st0"
                                            d="M443.9,612.8v-157H310.7v157H208.9l123.4,123.7c24.8,24.8,65,24.8,89.9,0l123.7-123.7L443.9,612.8L443.9,612.8z   M142.3,546.2V208.9L18.6,332.4c-24.8,24.8-24.8,65,0,89.9L142.3,546.2z M310.7,142.1v157h133.2v-157h102L422.2,18.6  c-24.8-24.8-65-24.8-89.9,0L208.9,142.1H310.7z M736,332.4L612.5,208.9v337l123.7-123.7c24.8-24.6,25.1-64.8,0.8-89.6  c0,0,0,0-0.3-0.3c0,0,0,0-0.5,0H736z" />
                                    </svg>
                                    <span class="ms-1">
                                        Кур'єр Нової Пошти
                                    </span>
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="delivery_service" id="radio-ukrposhta"
                                    value="ukrposhta">
                                <label class="form-check-label d-flex align-items-center" for="radio-ukrposhta">
                                    <svg width="14" height="20" viewBox="0 0 14 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1694_20)">
                                            <path opacity="0.999" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.04081 -0.00561523C7.31303 -0.00561523 7.58526 -0.00561523 7.85747 -0.00561523C9.85277 0.157498 11.4647 1.00848 12.6933 2.54733C13.4716 3.58099 13.9052 4.74108 13.9941 6.02758C13.9941 6.27944 13.9941 6.53131 13.9941 6.78317C13.9118 7.974 13.5288 9.06157 12.845 10.0459C12.7774 10.1389 12.7056 10.2285 12.6291 10.3149C12.5775 10.3409 12.523 10.3486 12.4658 10.3378C11.4581 10.1145 10.4509 9.88932 9.44414 9.66239C9.38724 9.61302 9.38336 9.55959 9.43247 9.50211C10.2497 8.98982 10.7922 8.2743 11.06 7.35558C11.3694 6.09368 11.1011 4.96794 10.255 3.97836C9.46012 3.13427 8.47429 2.72405 7.29747 2.74768C6.08785 2.83061 5.12535 3.34769 4.40997 4.29891C3.6987 5.3528 3.55093 6.47855 3.96664 7.67612C4.4303 8.81665 5.26058 9.56651 6.45747 9.9257C8.75539 10.4388 11.0537 10.9502 13.3525 11.4598C13.488 11.4911 13.5289 11.5693 13.475 11.6944C11.5065 14.4388 9.54265 17.1864 7.58331 19.9371C7.56038 19.9606 7.5351 19.9797 7.50747 19.9944C7.47636 19.9944 7.44526 19.9944 7.41414 19.9944C7.38465 19.9828 7.35937 19.9638 7.33831 19.9371C5.35007 17.1456 3.35896 14.356 1.36497 11.5685C0.54533 10.4169 0.0883855 9.13854 -0.00585938 7.73337C-0.00585938 7.45861 -0.00585938 7.18385 -0.00585938 6.9091C0.150986 4.82493 1.02599 3.09815 2.61914 1.72879C3.89743 0.682223 5.37133 0.104089 7.04081 -0.00561523Z"
                                                fill="#FABC25" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1694_20">
                                                <rect width="14" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <span class="ms-1">
                                        Укрпошта
                                    </span>
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="delivery_service" id="radio-meest"
                                    value="meest">
                                <label class="form-check-label d-flex align-items-center" for="radio-meest">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_1695_3)">
                                            <path opacity="0.989" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.8855 -0.052002C7.7605 -0.052002 9.6355 -0.052002 11.5105 -0.052002C13.359 3.25569 15.1646 6.58902 16.9272 9.948C15.1221 13.2803 13.3165 16.6136 11.5105 19.948C9.6355 19.948 7.7605 19.948 5.8855 19.948C7.69153 16.6136 9.49708 13.2803 11.3022 9.948C9.53956 6.58902 7.73401 3.25569 5.8855 -0.052002Z"
                                                fill="#E50032" />
                                            <path opacity="0.972" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M2.96875 7.13554C4.46342 7.10089 5.95647 7.13561 7.44792 7.2397C7.93403 8.14248 8.42014 9.04526 8.90625 9.94804C8.42014 10.8508 7.93403 11.7536 7.44792 12.6564C5.95647 12.7604 4.46342 12.7952 2.96875 12.7605C2.96875 10.8855 2.96875 9.01054 2.96875 7.13554Z"
                                                fill="#0060AE" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_1695_3">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <span class="ms-1">
                                        Meest Express
                                    </span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="delivery_service"
                                    id="radio-nodelivery" value="pickup">
                                <label class="form-check-label d-flex align-items-center" for="radio-nodelivery">
                                    <span class="ms-1">
                                        Самовивіз
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Ручне поле -->
                        <div id="manual-address-container" class="mt-3 d-none">
                            <label for="manual-address" class="form-label">Адреса відділення \ Домашня:</label>
                            <input type="text" class="form-control" id="manual-address"
                                placeholder="Введіть адресу вручну">
                        </div>
                    </div>
                    <div class="container mt-4">
                        <label class="form-label d-block mb-3 fs-5">
                            <b>
                                Отримувач
                            </b>
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="input_name1" class="form-label">Ім'я <b class="text-danger">*</b></label>
                                <input type="text" class="form-control" id="input_name1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="input_lastname1" class="form-label">Прізвище <b
                                        class="text-danger">*</b></label>
                                <input type="text" class="form-control" id="input_lastname1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="input_fathername1" class="form-label">По батькові</label>
                                <input type="text" class="form-control" id="input_fathername1">
                            </div>
                            <div class="col-md-6">
                                <label for="input_phone1"
                                    class="form-label d-flex justify-content-between align-items-center">
                                    <span>Номер телефону <b class="text-danger">*</b></span>
                                    <span id="phone-validation-icon" class="ms-2"></span>
                                </label>
                                <input type="text" class="form-control" id="input_phone1"
                                    placeholder="+38 (0__) ___ __ __" required>

                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="input_comment1" class="form-label">Додати коментар до замовлення</label>
                                    <textarea class="form-control resize-none" name="" id="input_comment1"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CSRF -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                </form>
            </div>
            <div class="col-md-5">
                <cart-list></cart-list>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const $citySelect = $('#city-select');
            const $warehouseSelect = $('#warehouse-select');
            const $cityContainer = $('#city-container');
            const $warehouseContainer = $('#warehouse-container');
            const $manualContainer = $('#manual-address-container');

            const $cityLoader = $('#city-loader');
            const $warehouseLoader = $('#warehouse-loader');

            // Сброс
            function resetFields() {
                $citySelect.empty().selectpicker('refresh');
                $warehouseSelect.empty().selectpicker('refresh');

                $cityContainer.addClass('d-none');
                $warehouseContainer.addClass('d-none');
                $manualContainer.addClass('d-none');
                $cityLoader.addClass('d-none');
                $warehouseLoader.addClass('d-none');
            }

            // Слушаем радио-кнопки
            $('input[name="delivery_service"]').on('change', function() {
                const service = $(this).val();
                resetFields();

                if (service === 'novaposhta') {
                    $cityContainer.removeClass('d-none');
                    $cityLoader.removeClass('d-none');

                    $.get('/cities', function(data) {
                        $.each(data, function(index, city) {
                            $citySelect.append(
                                `<option value="${city.Ref}">${city.Description}</option>`
                            );
                        });
                        $citySelect.selectpicker('refresh');
                        $cityLoader.addClass('d-none');
                    });
                } else if (service === "pickup") {
                    $manualContainer.addClass('d-none');
                } else {
                    $manualContainer.removeClass('d-none');
                }
            });

            // Слушаем выбор города
            $citySelect.on('changed.bs.select', function() {
                const cityRef = $(this).val();
                $warehouseSelect.empty().selectpicker('refresh');
                $warehouseContainer.removeClass('d-none');
                $warehouseLoader.removeClass('d-none');

                $.ajax({
                    method: 'POST',
                    url: '/warehouses',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        cityRef
                    }),
                    success: function(data) {
                        $.each(data, function(index, wh) {
                            $warehouseSelect.append(
                                `<option value="${wh.Ref}">${wh.Description}</option>`
                            );
                        });
                        $warehouseSelect.selectpicker('refresh');
                        $warehouseLoader.addClass('d-none');
                    }
                });
            });
        });
    </script>
    <script type="module">
        import {
            isValidUAPhone
        } from '{{ asset('js/phoneUtils.js') }}';

        $(document).on('click', '#submit_cart_ch_form', function(e) {
            e.preventDefault();

            // Очистка ошибок
            $('#input_name1, #input_lastname1, #input_phone1, #manual-address')
                .removeClass('is-invalid')
                .next('.invalid-feedback').remove();
            $('#deliveryServiceGroup .invalid-feedback').remove();
            $('#deliveryServiceGroup').removeClass('is-invalid');

            const name = $('#input_name1').val().trim();
            const lastname = $('#input_lastname1').val().trim();
            const phone = $('#input_phone1').val().trim();

            // Все данные
            const deliveryService = $('input[name="delivery_service"]:checked').val();
            const city = $('#city-select option:selected').text();
            const warehouse = $('#warehouse-select option:selected').text();
            const manualAddress = $('#manual-address').val();
            const totalPrice = $('#total_price_stream').val();

            const cart = window.localStorage.getItem('cart');

            let hasError = false;

            // Валидация Ім’я
            if (name.length < 2) {
                $('#input_name1').addClass('is-invalid').after(
                    '<div class="invalid-feedback">Введіть коректне ім’я (мінімум 2 символи).</div>');
                hasError = true;
            }

            // Валидация Прізвище
            if (lastname.length < 2) {
                $('#input_lastname1').addClass('is-invalid').after(
                    '<div class="invalid-feedback">Введіть коректне прізвище (мінімум 2 символи).</div>');
                hasError = true;
            }

            // Валидация служби доставки
            if (!deliveryService) {
                $('#deliveryServiceGroup')
                    .addClass('is-invalid')
                    .append('<div class="invalid-feedback d-block">Оберіть службу доставки.</div>');
                hasError = true;
            } else if (deliveryService === 'novaposhta') {
                if (!city) {
                    $('#deliveryServiceGroup')
                        .addClass('is-invalid')
                        .append('<div class="invalid-feedback d-block">Оберіть місто.</div>');
                    hasError = true;
                } else if (!warehouse) {
                    $('#deliveryServiceGroup')
                        .addClass('is-invalid')
                        .append('<div class="invalid-feedback d-block">Оберіть відділення.</div>');
                    hasError = true;
                }
            } else if (deliveryService === 'pickup') {
                hasError = false;
            } else {
                if (manualAddress.length < 2) {
                    $('#manual-address').addClass('is-invalid').after(
                        '<div class="invalid-feedback">Введіть адресу чи відділення (мінімум 2 символи).</div>');
                    hasError = true;
                }
            }

            // Валидация Телефон
            if (!isValidUAPhone(phone)) {
                $('#input_phone1').addClass('is-invalid').after(
                    '<div class="invalid-feedback">Введіть коректний номер телефону у форматі +380...</div>');
                hasError = true;
            }

            if (hasError) {
                $('#preloader').fadeOut();
                return;
            } else {
                $('#preloader').fadeIn();
            }

            const data = {
                delivery_service: deliveryService,
                city: city,
                warehouse: warehouse,
                manual_address: manualAddress,
                name: name,
                lastname: lastname,
                fathername: $('#input_fathername1').val(),
                phone: phone,
                comment: $('#input_comment1').val(),
                cart: cart,
                total_price: totalPrice,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                type: 'POST',
                url: '/order-submit',
                data: data,
                success: function(response) {
                    $('#preloader').fadeOut();
                    window.localStorage.removeItem('cart');
                    window.location.href = '/thank-you';
                },
                error: function(xhr) {
                    $('#preloader').fadeOut();
                    alert('Сталася помилка. Перевірте введені дані або спробуйте пізніше.');
                }
            });
        });
    </script>
    <script type="module">
        import {
            isValidUAPhone
        } from '{{ asset('js/phoneUtils.js') }}';

        let savedPhone = null;
        let cartSaveTimeout = null;

        function updatePhoneValidationIcon(valid) {
            const $icon = $('#phone-validation-icon');
            if (valid) {
                $icon.html('✅').removeClass('invalid-phone').addClass('valid-phone');
            } else {
                $icon.html('❌').removeClass('valid-phone').addClass('invalid-phone');
            }
        }

        $(document).on('input', '#input_phone1', function() {
            const raw = $(this).val();
            const cleaned = raw.replace(/\D/g, '');
            const fullPhone = cleaned.startsWith('380') ? cleaned : '38' + cleaned.slice(-10);

            if (cartSaveTimeout) clearTimeout(cartSaveTimeout);

            cartSaveTimeout = setTimeout(() => {
                const isValid = isValidUAPhone(fullPhone);
                updatePhoneValidationIcon(isValid);

                if (isValid && fullPhone !== savedPhone) {
                    const cart = window.localStorage.getItem('cart');
                    if (!cart || cart === '{}' || cart === 'null') {
                        console.log('Корзина порожня, не зберігаємо.');
                        return;
                    }

                    savedPhone = fullPhone;

                    console.log('Зберігаємо корзину для номера: ' + fullPhone);

                    $.ajax({
                        url: '/save-abandoned-cart',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            phone: fullPhone,
                            cart: cart
                        },
                        success: function() {
                            console.log('Черновик збережено: ' + fullPhone);
                        },
                        error: function() {
                            console.warn('Помилка при збереженні корзини');
                        }
                    });
                }
            }, 800);
        });
    </script>
@endsection
