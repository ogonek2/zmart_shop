@extends('layouts.app')

@section('seo')
    <title>Про нас - ZMART</title>
    <meta name="description" content="Дізнайтеся більше про ZMART - ваш надійний інтернет-магазин побутової техніки та господарських товарів. Якість, гарантія, швидка доставка.">
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
                <span class="text-gray-900 font-medium">Про нас</span>
            </nav>
        </div>
    </section>

    <!-- Header -->
    <section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Про нас</h1>
                <p class="text-xl text-emerald-100">Ваш надійний інтернет-магазин побутової техніки та господарських товарів</p>
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

                            <!-- Mission Section -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-8">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-bullseye text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Наша місія</h3>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">
                                        Ми створили <strong class="text-emerald-600">ZMART</strong>, щоб зробити якісну техніку доступною кожному українцю. 
                                        Наша мета — забезпечити вас сучасними товарами за розумною ціною з максимально зручним сервісом.
                        </p>
                    </div>
                                
                                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-gift text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900">Що ми пропонуємо</h3>
                                    </div>
                                    <ul class="space-y-3">
                                        <li class="flex items-center text-gray-700">
                                            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                            Побутова техніка та господарські товари для дому та кухні
                                        </li>
                                        <li class="flex items-center text-gray-700">
                                            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                            Аксесуари та електроніка
                                        </li>
                                        <li class="flex items-center text-gray-700">
                                            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                            Професійна консультація та підтримка
                                        </li>
                                        <li class="flex items-center text-gray-700">
                                            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                                            Швидка доставка по Україні
                                        </li>
                        </ul>
                    </div>
                </div>

                            <!-- Why Choose Us Section -->
                            <div class="mb-12">
                                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Чому обирають ZMART</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <div class="text-center group">
                                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-star text-white text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Якість товарів</h4>
                                        <p class="text-sm text-gray-600">Великий вибір перевірених товарів</p>
                                    </div>
                                    
                                    <div class="text-center group">
                                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-tags text-white text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Кращі ціни</h4>
                                        <p class="text-sm text-gray-600">Конкурентні ціни та акції</p>
                                    </div>
                                    
                                    <div class="text-center group">
                                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-thumbs-up text-white text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Відгуки клієнтів</h4>
                                        <p class="text-sm text-gray-600">Позитивні відгуки покупців</p>
                                    </div>
                                    
                                    <div class="text-center group">
                                        <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-bolt text-white text-xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Швидке замовлення</h4>
                                        <p class="text-sm text-gray-600">Просте та швидке оформлення</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Section -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Контактна інформація</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-envelope text-white"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Email</h4>
                                        <a href="mailto:zmartcomua@gmail.com" class="text-emerald-600 hover:text-emerald-700 transition-colors">
                                            zmartcomua@gmail.com
                                        </a>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-phone text-white"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Телефон</h4>
                                        <a href="tel:+380730777572" class="text-emerald-600 hover:text-emerald-700 transition-colors">
                                            +38 073-077-75-72
                                        </a>
                                    </div>
                                    
                                    <div class="text-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-map-marker-alt text-white"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">Адреса</h4>
                                        <p class="text-gray-600 text-sm">
                                            Одеса, пром ринок "7 км",<br>
                                            вул.Фабрична, маг. №2178
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Thank You Message -->
                            <div class="text-center mt-12">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-8 text-white">
                                    <h3 class="text-2xl font-bold mb-4">Дякуємо за довіру!</h3>
                                    <p class="text-emerald-100 text-lg">
                                        Дякуємо, що обираєте <strong>ZMART</strong>. Ми працюємо для вас 💙
                                    </p>
                                </div>
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
