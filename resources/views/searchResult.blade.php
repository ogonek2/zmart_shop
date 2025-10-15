@extends('layouts.app')

@section('seo')
    <title>Результати пошуку: {{ $originalQuery }} - ZMART</title>
    <meta name="description" content="Результати пошуку товарів за запитом {{ $originalQuery }}">
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
            <span class="text-gray-900 font-medium">Пошук</span>
        </nav>
    </div>
</section>

<!-- Search Header -->
<section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-3xl"></i>
                </div>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-3">
                Результати пошуку
            </h1>
            <p class="text-xl text-emerald-100 mb-4">
                За запитом: <span class="font-bold text-yellow-300">«{{ $originalQuery }}»</span>
            </p>
            <div class="text-lg">
                @if (!empty($getProducts) && $getProducts->count() > 0)
                    Знайдено <span class="font-bold">{{ $getProducts->total() }}</span> {{ $getProducts->total() == 1 ? 'товар' : 'товарів' }}
                @else
                    За вашим запитом нічого не знайдено
                @endif
            </div>
            
            @if ($usedAlternative && $query !== $originalQuery)
                <div class="mt-4 inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl">
                    <i class="fas fa-info-circle mr-2"></i>
                    Показано результати для <strong class="ml-1">«{{ $query }}»</strong>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Suggestions -->
@if (!empty($suggestions))
<section class="bg-white py-6 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
            Можливо, ви мали на увазі:
        </h3>
        <div class="flex flex-wrap gap-3">
            @foreach ($suggestions as $suggestion)
                <a href="{{ url('/search?q=' . urlencode($suggestion)) }}" 
                   class="px-4 py-2 bg-gray-100 hover:bg-emerald-500 hover:text-white rounded-xl text-gray-700 font-medium transition-all duration-200">
                    «{{ $suggestion }}»
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($getProducts->isEmpty())
            <!-- No Results -->
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Нічого не знайдено</h2>
                <p class="text-lg text-gray-600 mb-8">
                    За вашим запитом <span class="font-bold text-emerald-600">«{{ $originalQuery }}»</span> товари не знайдені.
                </p>
                
                <!-- Search Tips -->
                <div class="bg-gray-50 rounded-xl p-6 text-left max-w-md mx-auto mb-8">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Поради щодо пошуку:
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            Перевірте правильність написання
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            Спробуйте використовувати більш загальні слова
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            Використовуйте синоніми
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-emerald-500 mr-2 mt-1"></i>
                            Переконайтеся, що всі слова написані правильно
                        </li>
                    </ul>
                </div>
                
                <a href="{{ route('catalog') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Повернутися в каталог
                </a>
            </div>
        @else
            <!-- Results -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Знайдені товари</h2>
                    <p class="text-gray-600">
                        Сторінка {{ $getProducts->currentPage() }} з {{ $getProducts->lastPage() }}
                    </p>
                </div>
                <a href="{{ route('catalog') }}" 
                   class="inline-flex items-center px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>
                    До каталогу
                </a>
            </div>

            <!-- Products List -->
            <product-list 
                :products="{{ json_encode($getProducts->items()) }}"
                :pagination="{{ json_encode([
                    'current_page' => $getProducts->currentPage(),
                    'last_page' => $getProducts->lastPage(),
                    'per_page' => $getProducts->perPage(),
                    'total' => $getProducts->total(),
                    'from' => $getProducts->firstItem(),
                    'to' => $getProducts->lastItem(),
                ]) }}"
            ></product-list>

            <!-- Pagination -->
            @if($getProducts->hasPages())
            <div class="mt-12">
                <div class="flex items-center justify-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($getProducts->onFirstPage())
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $getProducts->previousPageUrl() }}" 
                           class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($getProducts->getUrlRange(1, $getProducts->lastPage()) as $page => $url)
                        @if ($page == $getProducts->currentPage())
                            <span class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-bold shadow-lg">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($getProducts->hasMorePages())
                        <a href="{{ $getProducts->nextPageUrl() }}" 
                           class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>

                <!-- Pagination Info -->
                <div class="text-center mt-4 text-sm text-gray-600">
                    Показано {{ $getProducts->firstItem() }}-{{ $getProducts->lastItem() }} з {{ $getProducts->total() }} товарів
                </div>
            </div>
            @endif
        @endif
    </div>
</section>
@endsection