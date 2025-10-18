<!-- Footer -->
<footer class="bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About Company -->
            <div class="lg:col-span-1">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold">ZMART</h3>
                </div>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    Ваш надійний партнер у світі побутової техніки та господарських товарів. Пропонуємо якісні товари від провідних брендів за доступними цінами.
                </p>
                <!-- Social Links -->
                <div class="flex space-x-3">
                    <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                        <i class="fab fa-telegram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                        <i class="fab fa-viber"></i>
                    </a>
                </div>
            </div>

            <!-- Information -->
            <div>
                <h4 class="text-lg font-bold mb-4 flex items-center">
                    <i class="fas fa-info-circle text-emerald-500 mr-2"></i>
                    Інформація
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('pro_kompaniiu') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Про компанію
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('oplata_i_dostavka') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Оплата і доставка
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('obmin_ta_povernennia') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Обмін та повернення
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dohovir_oferty') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Договір оферти
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('uhoda_korystuvacha') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Умови використання
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy_policy') }}" class="text-gray-400 hover:text-emerald-500 transition-colors flex items-center group">
                            <i class="fas fa-chevron-right text-xs mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            Політика конфіденційності
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contacts -->
            <div>
                <h4 class="text-lg font-bold mb-4 flex items-center">
                    <i class="fas fa-address-book text-emerald-500 mr-2"></i>
                    Контакти
                </h4>
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-phone text-emerald-500 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Телефон</div>
                            <a href="tel:+380730777572" class="text-gray-300 hover:text-emerald-500 transition-colors font-medium">
                                +38 073-077-75-72
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-envelope text-emerald-500 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Email</div>
                            <a href="mailto:zmartcomua@gmail.com" class="text-gray-300 hover:text-emerald-500 transition-colors font-medium">
                                zmartcomua@gmail.com
                            </a>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <i class="fas fa-map-marker-alt text-emerald-500 text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Адреса</div>
                            <span class="text-gray-300">
                                Одеса, пром ринок "7 км",<br>
                                вул. Фабрична, маг. №2178
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Working Hours & Payment -->
            <div>
                <h4 class="text-lg font-bold mb-4 flex items-center">
                    <i class="fas fa-clock text-emerald-500 mr-2"></i>
                    Графік роботи
                </h4>
                <ul class="space-y-3 mb-6">
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-400">Пн - Пт:</span>
                        <span class="text-gray-300 font-medium">9:00 - 18:00</span>
                    </li>
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-400">Субота:</span>
                        <span class="text-gray-300 font-medium">10:00 - 16:00</span>
                    </li>
                    <li class="flex justify-between text-sm">
                        <span class="text-gray-400">Неділя:</span>
                        <span class="text-red-400 font-medium">Вихідний</span>
                    </li>
                </ul>

                <h4 class="text-sm font-bold mb-3 text-gray-400">Ми приймаємо:</h4>
                <div class="flex flex-wrap gap-2">
                    <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                        <i class="fab fa-cc-visa text-blue-600 text-xl"></i>
                    </div>
                    <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                        <i class="fab fa-cc-mastercard text-red-600 text-xl"></i>
                    </div>
                    <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm text-center md:text-left">
                    © {{ date('Y') }} ZMART. Всі права захищені.
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <a href="{{ route('kontaktna_informatsiia') }}" class="hover:text-emerald-500 transition-colors">
                        Контакти
                    </a>
                    <span>•</span>
                    <a href="{{ route('privacy_policy') }}" class="hover:text-emerald-500 transition-colors">
                        Конфіденційність
                    </a>
                    <span>•</span>
                    <a href="{{ route('dohovir_oferty') }}" class="hover:text-emerald-500 transition-colors">
                        Договір
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>