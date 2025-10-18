@extends('layouts.app')

@section('seo')
    <title>Контактна інформація - ZMART</title>
    <meta name="description" content="Контактна інформація інтернет-магазину ZMART. Телефон, адреса, email. Зв'яжіться з нами для консультації та замовлення.">
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
                <span class="text-gray-900 font-medium">Контактна інформація</span>
            </nav>
        </div>
    </section>

    <!-- Header -->
    <section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Контактна інформація</h1>
                <p class="text-xl text-emerald-100">Зв'яжіться з нами для консультації та замовлення</p>
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
                            <!-- Contact Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                                <!-- Phone Card -->
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 text-center group hover:shadow-lg transition-all duration-300">
                                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-phone text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Телефон</h3>
                                    <a href="tel:+380730777572" class="text-emerald-600 hover:text-emerald-700 transition-colors text-lg font-semibold block mb-4">
                                        +38 073-077-75-72
                                    </a>
                                    <button class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                                        Замовити дзвінок
                                    </button>
                                </div>

                                <!-- Address Card -->
                                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-6 text-center group hover:shadow-lg transition-all duration-300">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Адреса</h3>
                                    <p class="text-gray-700 leading-relaxed">
                                        Одеса, пром ринок "7 км",<br>
                                        вул.Фабрична, маг. №2178
                                    </p>
                                </div>

                                <!-- Email Card -->
                                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-6 text-center group hover:shadow-lg transition-all duration-300">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-envelope text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-4">Пошта</h3>
                                    <a href="mailto:zmartcomua@gmail.com" class="text-purple-600 hover:text-purple-700 transition-colors text-lg font-semibold">
                                        zmartcomua@gmail.com
                                    </a>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-8 mb-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center flex items-center justify-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    Графік роботи
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="text-center">
                                        <h4 class="font-bold text-gray-900 mb-2">Пн - Пт</h4>
                                        <p class="text-gray-700">09:00 - 18:00</p>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="font-bold text-gray-900 mb-2">Сб - Нд</h4>
                                        <p class="text-gray-700">10:00 - 16:00</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Form -->
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Написати нам</h3>
                                <form class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ім'я</label>
                                            <input type="text" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Ваше ім'я">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                            <input type="email" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="your@email.com">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Тема</label>
                                        <input type="text" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Тема повідомлення">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Повідомлення</label>
                                        <textarea rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-400 focus:ring-0 transition-colors" placeholder="Ваше повідомлення"></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-8 py-4 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Відправити повідомлення
                                        </button>
                                    </div>
                                </form>
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
