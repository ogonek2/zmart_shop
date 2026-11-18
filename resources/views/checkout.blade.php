@extends('layouts.app')

@section('seo')
    <title>Оформлення замовлення - ZMART</title>
    <meta name="description" content="Оформлення замовлення в інтернет-магазині ZMART. Швидка доставка по всій Україні.">
@endsection

@section('content')
<main class="zcheck">
    <div class="zmart-shell">
        <header class="zcheck__page-head">
            <h1>Оформлення замовлення</h1>
        </header>

        <form id="order-form" class="zcheck__layout" novalidate>
            <div class="zcheck__main">
                {{-- Contact instead of registration --}}
                <section class="zcheck__card">
                    <h2>Контактні дані</h2>
                    <p class="zcheck__lead">Вкажіть телефон і ПІБ — ми зв’яжемося для підтвердження замовлення.</p>
                    <div class="zcheck__alert">
                        Мінімальна сума замовлення — <strong>1000 ₴</strong>
                    </div>
                    <div class="zcheck__grid">
                        <div class="zcheck__field">
                            <label for="phone">Телефон <em>*</em></label>
                            <div class="zcheck__phone">
                                <span>+380</span>
                                <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="XX XXX XX XX">
                            </div>
                        </div>
                        <div class="zcheck__field">
                            <label for="name">Ім’я <em>*</em></label>
                            <input type="text" id="name" name="name" autocomplete="given-name" placeholder="Ім’я">
                        </div>
                        <div class="zcheck__field">
                            <label for="lastname">Прізвище <em>*</em></label>
                            <input type="text" id="lastname" name="lastname" autocomplete="family-name" placeholder="Прізвище">
                        </div>
                        <div class="zcheck__field">
                            <label for="fathername">По батькові</label>
                            <input type="text" id="fathername" name="fathername" placeholder="Необов’язково">
                        </div>
                    </div>
                </section>

                {{-- Order items --}}
                <section class="zcheck__card">
                    <div class="zcheck__card-head">
                        <h2>Ваше замовлення</h2>
                        <a href="/catalog" class="zcheck__link">Редагувати товари</a>
                    </div>
                    <cart-list variant="items"></cart-list>
                </section>

                {{-- Delivery --}}
                <section class="zcheck__card">
                    <h2>Доставка</h2>
                    <div class="zcheck__methods" role="radiogroup" aria-label="Спосіб доставки">
                        <label class="zcheck-method">
                            <div class="zcheck-method__row">
                                <input type="radio" name="delivery_service" value="novaposhta">
                                <span class="zcheck-method__title">
                                    <strong>Нова Пошта</strong>
                                    <small>Відділення або поштомат</small>
                                </span>
                                <em class="zcheck-method__price">За тарифами перевізника</em>
                            </div>
                            <div class="zcheck-method__body">
                                <div class="zcheck__grid">
                                    <div class="zcheck__field zcheck__field--full">
                                        <label for="np-city-input">Місто <em>*</em></label>
                                        <div class="zcheck__suggest" data-suggest="city">
                                            <input type="text" id="np-city-input" autocomplete="off" placeholder="Почніть вводити назву міста">
                                            <input type="hidden" id="city-ref" value="">
                                            <input type="hidden" id="city-name" value="">
                                            <div id="np-city-list" class="zcheck__suggest-list" hidden></div>
                                        </div>
                                    </div>
                                    <div class="zcheck__field zcheck__field--full">
                                        <label for="warehouse-select">Відділення / поштомат <em>*</em></label>
                                        <select id="warehouse-select" disabled>
                                            <option value="">Спочатку оберіть місто</option>
                                        </select>
                                        <input type="hidden" id="warehouse-name" value="">
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="zcheck-method">
                            <div class="zcheck-method__row">
                                <input type="radio" name="delivery_service" value="courier">
                                <span class="zcheck-method__title">
                                    <strong>Кур’єрська доставка</strong>
                                    <small>Доставка за адресою</small>
                                </span>
                                <em class="zcheck-method__price">За тарифами перевізника</em>
                            </div>
                            <div class="zcheck-method__body">
                                <div class="zcheck__grid">
                                    <div class="zcheck__field">
                                        <label for="courier-city">Місто <em>*</em></label>
                                        <input type="text" id="courier-city" placeholder="Наприклад, Одеса">
                                    </div>
                                    <div class="zcheck__field">
                                        <label for="courier-street">Вулиця <em>*</em></label>
                                        <input type="text" id="courier-street" placeholder="Назва вулиці">
                                    </div>
                                    <div class="zcheck__field">
                                        <label for="courier-building">Будинок <em>*</em></label>
                                        <input type="text" id="courier-building" placeholder="№">
                                    </div>
                                    <div class="zcheck__field">
                                        <label for="courier-apt">Квартира / офіс</label>
                                        <input type="text" id="courier-apt" placeholder="Необов’язково">
                                    </div>
                                    <div class="zcheck__field zcheck__field--full">
                                        <label for="manual-address">Уточнення адреси</label>
                                        <textarea id="manual-address" rows="2" placeholder="Орієнтири, під’їзд, поверх"></textarea>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="zcheck-method">
                            <div class="zcheck-method__row">
                                <input type="radio" name="delivery_service" value="pickup">
                                <span class="zcheck-method__title">
                                    <strong>Самовивіз</strong>
                                    <small>Одеса, 7 км, маг. №2523</small>
                                </span>
                                <em class="zcheck-method__price">Безкоштовно</em>
                            </div>
                            <div class="zcheck-method__body">
                                <div class="zcheck__note">
                                    Одеса, промринок «7 км», вул. Фабрична, маг. №2523.
                                    Після підтвердження повідомимо, коли можна забрати замовлення.
                                </div>
                            </div>
                        </label>
                    </div>
                </section>

                {{-- Payment --}}
                <section class="zcheck__card">
                    <h2>Оплата</h2>
                    <div class="zcheck__methods" role="radiogroup" aria-label="Спосіб оплати">
                        <label class="zcheck-method zcheck-method--simple">
                            <div class="zcheck-method__row">
                                <input type="radio" name="payment_method" value="cash">
                                <span class="zcheck-method__title">
                                    <strong>Накладений платіж</strong>
                                    <small>Оплата готівкою при отриманні</small>
                                </span>
                            </div>
                        </label>
                        <label class="zcheck-method zcheck-method--simple">
                            <div class="zcheck-method__row">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <span class="zcheck-method__title">
                                    <strong>Оплата на картку</strong>
                                    <small>Реквізити надішлемо після підтвердження</small>
                                </span>
                            </div>
                        </label>
                        <label class="zcheck-method zcheck-method--simple">
                            <div class="zcheck-method__row">
                                <input type="radio" name="payment_method" value="card_payment">
                                <span class="zcheck-method__title">
                                    <strong>Картка при отриманні</strong>
                                    <small>Оплата терміналом у кур’єра / на відділенні</small>
                                </span>
                            </div>
                        </label>
                        <label class="zcheck-method zcheck-method--simple">
                            <div class="zcheck-method__row">
                                <input type="radio" name="payment_method" value="pickup_payment">
                                <span class="zcheck-method__title">
                                    <strong>Оплата при самовивозі</strong>
                                    <small>Готівка або картка в магазині</small>
                                </span>
                            </div>
                        </label>
                    </div>
                </section>

                {{-- Comment --}}
                <section class="zcheck__card">
                    <h2>Коментар до замовлення</h2>
                    <div class="zcheck__field">
                        <textarea id="comment" name="comment" rows="3" placeholder="Побажання до замовлення (необов’язково)"></textarea>
                    </div>
                </section>

                <p id="checkout-form-error" class="zcheck__error" hidden></p>
            </div>

            <aside class="zcheck__aside">
                <div class="zcheck__summary">
                    <h2>Разом</h2>
                    <cart-list variant="aside"></cart-list>
                    <button type="submit" id="submit-order" class="zcheck__btn">Замовлення підтверджую</button>
                    <p id="checkout-minimum-message" class="zcheck__hint" hidden></p>
                    <p class="zcheck__legal">
                        Натискаючи кнопку, ви погоджуєтесь з
                        <a href="{{ route('uhoda_korystuvacha') }}">умовами використання</a>
                        та
                        <a href="{{ route('privacy_policy') }}">політикою конфіденційності</a>.
                    </p>
                </div>
            </aside>
        </form>
    </div>

    <div id="preloader" class="zcheck__loader" hidden>
        <div class="zcheck__loader-box">
            <span class="zcheck__spinner"></span>
            <p>Надсилаємо замовлення...</p>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
(function () {
    if (window.__ZCHECK_BOUND__) return;
    window.__ZCHECK_BOUND__ = true;

    const MIN_ORDER_TOTAL = 1000;
    let cityTimer = null;
    let cityRequestId = 0;

    function csrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }
    function el(id) { return document.getElementById(id); }
    function form() { return document.getElementById('order-form'); }

    function showError(message) {
        const node = el('checkout-form-error');
        if (!node) return;
        node.textContent = message;
        node.hidden = false;
        node.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError() {
        const node = el('checkout-form-error');
        if (node) node.hidden = true;
        form()?.querySelectorAll('.is-invalid').forEach((n) => n.classList.remove('is-invalid'));
    }

    function markInvalid(node) {
        if (node) node.classList.add('is-invalid');
    }

    function setHidden(node, isHidden) {
        if (!node) return;
        node.hidden = !!isHidden;
        if (isHidden) node.setAttribute('hidden', '');
        else node.removeAttribute('hidden');
    }

    function normalizePhone(value) {
        const digits = String(value || '').replace(/\D/g, '');
        if (digits.startsWith('380') && digits.length >= 12) return '+' + digits;
        if (digits.length === 9) return '+380' + digits;
        if (digits.length === 10 && digits.startsWith('0')) return '+38' + digits;
        return value.trim();
    }

    function updateSubmitState() {
        const submitButton = el('submit-order');
        const minOrderMessage = el('checkout-minimum-message');
        if (!submitButton) return;
        const total = parseFloat(el('total_price_stream')?.value || '0') || 0;

        if (total < MIN_ORDER_TOTAL) {
            submitButton.disabled = true;
            if (minOrderMessage) {
                const difference = Math.ceil(MIN_ORDER_TOTAL - total);
                minOrderMessage.textContent = total > 0
                    ? `Мінімальна сума — 1000 ₴. Додайте ще на ${difference.toLocaleString('uk-UA')} ₴.`
                    : 'Додайте товари до кошика, щоб оформити замовлення.';
                minOrderMessage.hidden = false;
                minOrderMessage.classList.remove('is-ok');
            }
        } else {
            submitButton.disabled = false;
            if (minOrderMessage) {
                minOrderMessage.textContent = '';
                minOrderMessage.hidden = true;
                minOrderMessage.classList.add('is-ok');
            }
        }
    }

    function resetNovaFields() {
        const cityInput = el('np-city-input');
        const cityRefInput = el('city-ref');
        const cityNameInput = el('city-name');
        const cityList = el('np-city-list');
        const warehouseSelect = el('warehouse-select');
        const warehouseNameInput = el('warehouse-name');
        if (cityInput) cityInput.value = '';
        if (cityRefInput) cityRefInput.value = '';
        if (cityNameInput) cityNameInput.value = '';
        if (cityList) {
            cityList.innerHTML = '';
            setHidden(cityList, true);
        }
        if (warehouseSelect) {
            warehouseSelect.innerHTML = '<option value="">Спочатку оберіть місто</option>';
            warehouseSelect.disabled = true;
        }
        if (warehouseNameInput) warehouseNameInput.value = '';
    }

    function renderCityResults(items) {
        const cityList = el('np-city-list');
        if (!cityList) return;
        cityList.innerHTML = '';
        if (!items.length) {
            cityList.innerHTML = '<div class="zcheck__suggest-empty">Міст не знайдено</div>';
            setHidden(cityList, false);
            return;
        }
        items.forEach((city) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'zcheck__suggest-item';
            btn.textContent = city.Description;
            btn.dataset.ref = city.Ref || '';
            btn.dataset.name = city.CityName || city.Description || '';
            btn.dataset.label = city.Description || '';
            cityList.appendChild(btn);
        });
        setHidden(cityList, false);
    }

    function selectCity(city) {
        const cityInput = el('np-city-input');
        const cityRefInput = el('city-ref');
        const cityNameInput = el('city-name');
        const cityList = el('np-city-list');
        if (cityInput) cityInput.value = city.Description || city.label || '';
        if (cityRefInput) cityRefInput.value = city.Ref || city.ref || '';
        if (cityNameInput) cityNameInput.value = city.CityName || city.name || city.Description || '';
        if (cityList) {
            cityList.innerHTML = '';
            setHidden(cityList, true);
        }
        if (cityRefInput?.value) loadWarehouses(cityRefInput.value);
    }

    async function searchCities(query) {
        const cityList = el('np-city-list');
        if (!cityList) return;
        const requestId = ++cityRequestId;
        cityList.innerHTML = '<div class="zcheck__suggest-empty">Пошук...</div>';
        setHidden(cityList, false);
        try {
            const response = await fetch(`/cities?q=${encodeURIComponent(query)}`, {
                headers: { Accept: 'application/json' },
            });
            const data = await response.json();
            if (requestId !== cityRequestId) return;
            if (!response.ok) throw new Error(data.error || 'Помилка пошуку міст');
            renderCityResults(Array.isArray(data) ? data : []);
        } catch (e) {
            if (requestId !== cityRequestId) return;
            cityList.innerHTML = `<div class="zcheck__suggest-empty">${e.message || 'Помилка завантаження міст'}</div>`;
            setHidden(cityList, false);
        }
    }

    async function loadWarehouses(cityRef) {
        const warehouseSelect = el('warehouse-select');
        const warehouseNameInput = el('warehouse-name');
        if (!warehouseSelect) return;
        warehouseSelect.disabled = true;
        warehouseSelect.innerHTML = '<option value="">Завантаження відділень...</option>';
        if (warehouseNameInput) warehouseNameInput.value = '';
        try {
            const response = await fetch('/warehouses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    Accept: 'application/json',
                },
                body: JSON.stringify({ cityRef }),
            });
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Помилка завантаження відділень');
            const list = Array.isArray(data) ? data : [];
            warehouseSelect.innerHTML = '<option value="">Оберіть відділення</option>';
            list.forEach((warehouse) => {
                const option = document.createElement('option');
                option.value = warehouse.Ref;
                option.textContent = warehouse.Description;
                warehouseSelect.appendChild(option);
            });
            warehouseSelect.disabled = list.length === 0;
            if (!list.length) warehouseSelect.innerHTML = '<option value="">Відділень не знайдено</option>';
        } catch (e) {
            warehouseSelect.innerHTML = '<option value="">Помилка завантаження</option>';
            showError(e.message || 'Не вдалося завантажити відділення.');
        }
    }

    function buildCourierAddress() {
        const parts = [];
        const city = el('courier-city')?.value.trim();
        const street = el('courier-street')?.value.trim();
        const building = el('courier-building')?.value.trim();
        const apt = el('courier-apt')?.value.trim();
        const extra = el('manual-address')?.value.trim();
        if (city) parts.push(city);
        if (street) parts.push(`вул. ${street}`);
        if (building) parts.push(`буд. ${building}`);
        if (apt) parts.push(`кв./оф. ${apt}`);
        if (extra) parts.push(extra);
        return parts.join(', ');
    }

    function validateForm() {
        clearError();
        const f = form();
        if (!f) return false;
        let isValid = true;

        const phone = el('phone');
        const name = el('name');
        const lastname = el('lastname');
        const phoneValue = normalizePhone(phone?.value || '');

        if (!phoneValue || phoneValue.replace(/\D/g, '').length < 12) {
            markInvalid(phone);
            isValid = false;
        }
        if (!name?.value.trim() || name.value.trim().length < 2) {
            markInvalid(name);
            isValid = false;
        }
        if (!lastname?.value.trim() || lastname.value.trim().length < 2) {
            markInvalid(lastname);
            isValid = false;
        }

        const deliveryService = f.querySelector('input[name="delivery_service"]:checked')?.value;
        if (!deliveryService) {
            showError('Оберіть спосіб доставки');
            return false;
        }

        if (deliveryService === 'novaposhta') {
            if (!el('city-ref')?.value) {
                markInvalid(el('np-city-input'));
                isValid = false;
            }
            if (!el('warehouse-select')?.value) {
                markInvalid(el('warehouse-select'));
                isValid = false;
            }
        } else if (deliveryService === 'courier') {
            ['courier-city', 'courier-street', 'courier-building'].forEach((id) => {
                const node = el(id);
                if (!node?.value.trim()) {
                    markInvalid(node);
                    isValid = false;
                }
            });
        }

        const paymentMethod = f.querySelector('input[name="payment_method"]:checked')?.value;
        if (!paymentMethod) {
            showError('Оберіть спосіб оплати');
            return false;
        }

        if (!isValid) showError('Перевірте обов’язкові поля');
        return isValid;
    }

    document.addEventListener('change', (e) => {
        const target = e.target;
        if (!(target instanceof HTMLElement)) return;

        if (target.matches('input[name="delivery_service"]') && target.value !== 'novaposhta') {
            resetNovaFields();
        }

        if (target.id === 'warehouse-select') {
            const select = target;
            const opt = select.options[select.selectedIndex];
            const warehouseNameInput = el('warehouse-name');
            if (warehouseNameInput) warehouseNameInput.value = opt && select.value ? opt.textContent : '';
        }
    });

    document.addEventListener('input', (e) => {
        const target = e.target;
        if (!(target instanceof HTMLElement) || target.id !== 'np-city-input') return;

        el('city-ref') && (el('city-ref').value = '');
        el('city-name') && (el('city-name').value = '');
        const warehouseSelect = el('warehouse-select');
        const warehouseNameInput = el('warehouse-name');
        if (warehouseSelect) {
            warehouseSelect.innerHTML = '<option value="">Спочатку оберіть місто</option>';
            warehouseSelect.disabled = true;
        }
        if (warehouseNameInput) warehouseNameInput.value = '';

        clearTimeout(cityTimer);
        const q = target.value.trim();
        const cityList = el('np-city-list');
        if (q.length < 2) {
            if (cityList) {
                cityList.innerHTML = '<div class="zcheck__suggest-empty">Введіть щонайменше 2 літери</div>';
                setHidden(cityList, false);
            }
            return;
        }
        cityTimer = setTimeout(() => searchCities(q), 280);
    });

    document.addEventListener('focusin', (e) => {
        const target = e.target;
        if (!(target instanceof HTMLElement) || target.id !== 'np-city-input') return;
        if (el('city-ref')?.value) return;
        const q = target.value.trim();
        const cityList = el('np-city-list');
        if (q.length >= 2) searchCities(q);
        else if (cityList) {
            cityList.innerHTML = '<div class="zcheck__suggest-empty">Введіть щонайменше 2 літери</div>';
            setHidden(cityList, false);
        }
    });

    document.addEventListener('click', (e) => {
        const target = e.target;
        if (!(target instanceof Element)) return;
        const suggestItem = target.closest('.zcheck__suggest-item');
        if (suggestItem) {
            e.preventDefault();
            selectCity({
                Ref: suggestItem.dataset.ref,
                CityName: suggestItem.dataset.name,
                Description: suggestItem.dataset.label || suggestItem.textContent,
            });
            return;
        }
        if (!target.closest('[data-suggest="city"]')) {
            const cityList = el('np-city-list');
            if (cityList) setHidden(cityList, true);
        }
    });

    document.addEventListener('submit', async (e) => {
        const f = e.target;
        if (!(f instanceof HTMLFormElement) || f.id !== 'order-form') return;
        e.preventDefault();
        if (!validateForm()) return;

        const cart = localStorage.getItem('cart');
        if (!cart || cart === '[]' || cart === '{}' || cart === 'null') {
            showError('Кошик порожній. Додайте товари перед оформленням.');
            return;
        }

        const totalPrice = el('total_price_stream')?.value || '0';
        if ((parseFloat(totalPrice) || 0) < MIN_ORDER_TOTAL) {
            showError('Мінімальна сума замовлення — 1000 ₴.');
            return;
        }

        const deliveryService = f.querySelector('input[name="delivery_service"]:checked')?.value;
        let city = '';
        let warehouse = '';
        let manualAddress = '';

        if (deliveryService === 'novaposhta') {
            city = el('city-name')?.value || el('np-city-input')?.value || '';
            const warehouseSelect = el('warehouse-select');
            warehouse = el('warehouse-name')?.value
                || (warehouseSelect?.options[warehouseSelect.selectedIndex]?.textContent || '');
        } else if (deliveryService === 'courier') {
            city = el('courier-city')?.value.trim() || '';
            manualAddress = buildCourierAddress();
        } else if (deliveryService === 'pickup') {
            city = 'Одеса';
            manualAddress = 'Самовивіз: промринок «7 км», вул. Фабрична, маг. №2523';
        }

        const preloader = el('preloader');
        if (preloader) setHidden(preloader, false);

        const payload = {
            delivery_service: deliveryService,
            city,
            warehouse,
            manual_address: manualAddress,
            name: el('name')?.value.trim() || '',
            lastname: el('lastname')?.value.trim() || '',
            fathername: el('fathername')?.value.trim() || '',
            phone: normalizePhone(el('phone')?.value || ''),
            comment: el('comment')?.value.trim() || '',
            payment: f.querySelector('input[name="payment_method"]:checked')?.value,
            cart,
            total_price: totalPrice,
            _token: csrf(),
        };

        try {
            const response = await fetch('/order-submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf(),
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.error || data.message || 'Помилка під час надсилання замовлення.');
            localStorage.removeItem('cart');
            window.dispatchEvent(new Event('cart-updated'));
            window.location.href = '/thank-you';
        } catch (err) {
            showError(err.message || 'Помилка під час надсилання замовлення.');
        } finally {
            if (preloader) setHidden(preloader, true);
        }
    });

    function boot() {
        updateSubmitState();
        let ticks = 0;
        const timer = setInterval(() => {
            updateSubmitState();
            if (++ticks >= 12) clearInterval(timer);
        }, 200);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => setTimeout(boot, 50));
    } else {
        setTimeout(boot, 50);
    }
    window.addEventListener('load', () => setTimeout(boot, 100));
    window.addEventListener('cart-updated', updateSubmitState);
})();
</script>
@endsection
