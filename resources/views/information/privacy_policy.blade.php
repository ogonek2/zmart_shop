@extends('layouts.app')

@section('seo')
    <title>Політика конфіденційності - ZMART</title>
    <meta name="description" content="Політика конфіденційності інтернет-магазину ZMART. Захист персональних даних користувачів.">
@endsection

@section('content')
    <!-- Breadcrumbs -->
    <section class="bg-gray-50 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                    <i class="fas fa-home"></i>
                </a>
                <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                <span class="text-gray-900 font-medium">Політика конфіденційності</span>
            </nav>
        </div>
    </section>

    <!-- Header -->
    <section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Політика конфіденційності</h1>
                <p class="text-xl text-emerald-100">Захист персональних даних користувачів</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="bg-gray-50 min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    @include('includes.main.information_bar')
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-8">
                            <div class="text-center mb-8">
                                <p class="text-gray-600 text-sm">Останнє оновлення: 28 липня 2025 року</p>
                            </div>

                <p>Ця Політика конфіденційності пояснює, як інтернет-магазин <strong>Zmart</strong> (далі — «ми», «наш
                    сайт»)
                    збирає, використовує та захищає персональні дані користувачів (далі — «ви», «користувач») при
                    відвідуванні
                    та використанні сайту <a href="https://zmart.com.ua">zmart.com.ua</a>.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-white text-sm"></i>
                                </div>
                                1. Збір інформації
                            </h2>
                            <p class="text-gray-700 mb-4">Ми можемо збирати такі дані під час використання сайту:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-8">
                                <li>Ім'я та прізвище</li>
                                <li>Номер телефону</li>
                                <li>Електронна пошта</li>
                                <li>Адреса доставки</li>
                                <li>IP-адреса, дані браузера та тип пристрою</li>
                            </ul>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cogs text-white text-sm"></i>
                                </div>
                                2. Використання інформації
                            </h2>
                            <p class="text-gray-700 mb-4">Ми використовуємо ваші дані з наступною метою:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-8">
                                <li>Обробка та доставка замовлень</li>
                                <li>Зв'язок із клієнтом</li>
                                <li>Надання підтримки</li>
                                <li>Покращення роботи сайту</li>
                                <li>Рекламні та маркетингові повідомлення (з вашої згоди)</li>
                            </ul>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shield-alt text-white text-sm"></i>
                                </div>
                                3. Захист персональних даних
                            </h2>
                            <p class="text-gray-700 mb-8">Ми вживаємо всі необхідні технічні та організаційні заходи для захисту ваших персональних даних від
                                несанкціонованого доступу, зміни чи знищення.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-white text-sm"></i>
                                </div>
                                4. Розкриття інформації третім особам
                            </h2>
                            <p class="text-gray-700 mb-4">Ми не продаємо і не передаємо ваші персональні дані третім особам, за винятком випадків, коли це
                                необхідно для:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-8">
                                <li>Виконання замовлення (служби доставки, платіжні сервіси тощо)</li>
                                <li>Дотримання законодавства України</li>
                            </ul>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cookie-bite text-white text-sm"></i>
                                </div>
                                5. Cookies
                            </h2>
                            <p class="text-gray-700 mb-8">Ми використовуємо файли cookie для зручності користувачів, аналітики трафіку та покращення
                                функціональності сайту. Ви можете змінити налаштування cookies у своєму браузері.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-red-400 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-external-link-alt text-white text-sm"></i>
                                </div>
                                6. Посилання на інші сайти
                            </h2>
                            <p class="text-gray-700 mb-8">Наш сайт може містити посилання на сторонні ресурси. Ми не несемо відповідальності за зміст або політику
                                конфіденційності таких сайтів.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-handshake text-white text-sm"></i>
                                </div>
                                7. Згода
                            </h2>
                            <p class="text-gray-700 mb-8">Використовуючи наш сайт, ви надаєте згоду на збір та обробку ваших персональних даних відповідно до цієї
                                Політики конфіденційності.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-check text-white text-sm"></i>
                                </div>
                                8. Права користувача
                            </h2>
                            <p class="text-gray-700 mb-4">Ви маєте право:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-8">
                                <li>Отримати інформацію про свої персональні дані</li>
                                <li>Змінити або видалити свої дані</li>
                                <li>Відкликати згоду на обробку персональних даних</li>
                            </ul>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-pink-400 to-rose-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-white text-sm"></i>
                                </div>
                                9. Зміни до політики
                            </h2>
                            <p class="text-gray-700 mb-8">Ми можемо періодично оновлювати цю Політику. Всі зміни будуть публікуватися на цій сторінці з відповідною
                                датою оновлення.</p>

                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-white text-sm"></i>
                                </div>
                                10. Контактна інформація
                            </h2>
                            <p class="text-gray-700 mb-4">Якщо у вас виникли питання щодо Політики конфіденційності, звертайтесь:</p>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 mb-8">
                                <li><strong>Email:</strong> zmartcomua@gmail.com</li>
                                <li><strong>Телефон:</strong> +38 073-077-75-72</li>
                                <li><strong>Адреса:</strong> Одесса, пром рынок "7 км", ул.Фабричная, маг. №2178</li>
                            </ul>

                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 text-center">
                                <p class="text-gray-700"><small>Використовуючи сайт <a href="https://zmart.com.ua" class="text-emerald-600 hover:text-emerald-700">zmart.com.ua</a>, ви
                                        погоджуєтесь з цією політикою конфіденційності.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
