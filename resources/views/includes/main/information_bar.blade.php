<!-- Сайдбар -->
<aside class="w-full hidden md:block">
    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-info-circle text-white text-sm"></i>
            </div>
            Інформація
        </h3>
        
        <nav class="space-y-2">
            <a href="{{ route('pro_kompaniiu') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('pro_kompaniiu') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-building text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Про нас
            </a>
            
            <a href="{{ route('oplata_i_dostavka') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('oplata_i_dostavka') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-credit-card text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Оплата і доставка
            </a>
            
            <a href="{{ route('obmin_ta_povernennia') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('obmin_ta_povernennia') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-exchange-alt text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Обмін та повернення
            </a>
            
            <a href="{{ route('kontaktna_informatsiia') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('kontaktna_informatsiia') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-phone text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Контакти
            </a>
            
            <a href="{{ route('dohovir_oferty') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('dohovir_oferty') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-file-contract text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Договір оферти
            </a>
            
            <a href="{{ route('uhoda_korystuvacha') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('uhoda_korystuvacha') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-user-shield text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Угода користувача
            </a>
            
            <a href="{{ route('privacy_policy') }}" 
               class="flex items-center px-4 py-3 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all duration-200 group {{ request()->routeIs('privacy_policy') ? 'bg-emerald-50 text-emerald-600 font-semibold' : '' }}">
                <i class="fas fa-shield-alt text-gray-400 group-hover:text-emerald-500 mr-3 w-5"></i>
                Політика конфіденційності
            </a>
        </nav>
    </div>
</aside>
