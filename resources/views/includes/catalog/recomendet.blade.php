<div class="recommended-products">
    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <div class="pcard-grid mb-8">
            @foreach($recommendedProducts as $product)
                @include('includes.catalog.product-card', ['product' => $product])
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">Немає схожих товарів</h4>
            <p class="text-gray-600 mb-4">У цій категорії поки немає інших товарів</p>
            <a href="{{ route('catalog') }}" class="inline-flex items-center px-6 py-3 bg-[#E8B923] text-[#1A1A1A] rounded font-bold hover:brightness-95 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Перейти до каталогу
            </a>
        </div>
    @endif
</div>
