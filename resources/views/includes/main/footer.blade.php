<div class="container" id="footer">
    <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-5 border-top">
        <div class="col mb-3"> 
            <a class="navbar-brand" href="{{ url('/') }}">
                <h1
                    style="border-left: 5px solid  orange; padding-left: 10px; color: orrange; font-size: 20px; font-weight: bold;">
                    ZMART.COM.UA
                </h1>
            </a>
            <p class="text-body-secondary">© 2025</p>
        </div>
        <div class="col mb-3"></div>
        <div class="col mb-3">
            <h5>Товари</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="{{ route('pro_kompaniiu') }}" class="nav-link p-0 text-body-secondary">Про нас</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Всі товари</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Каталог товари</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Категорії</a></li>
            </ul>
        </div>
        <div class="col mb-3">
            <h5>Інформація</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="{{ route('oplata_i_dostavka') }}" class="nav-link p-0 text-body-secondary">Оплата і доставка</a></li>
                <li class="nav-item mb-2"><a href="{{ route('obmin_ta_povernennia') }}" class="nav-link p-0 text-body-secondary">Обмін та повернення</a></li>
                <li class="nav-item mb-2"><a href="{{ route('kontaktna_informatsiia') }}" class="nav-link p-0 text-body-secondary">Контакти</a></li>
                <li class="nav-item mb-2"><a href="{{ route('dohovir_oferty') }}" class="nav-link p-0 text-body-secondary">Договір оферти</a></li>
                <li class="nav-item mb-2"><a href="{{ route('uhoda_korystuvacha') }}" class="nav-link p-0 text-body-secondary">Угода користувача</a></li>
                <li class="nav-item mb-2"><a href="{{ route('privacy_policy') }}" class="nav-link p-0 text-body-secondary">Політика конфіденційності</a></li>
            </ul>
        </div>
        <div class="col mb-3">
            <h5>Контактна інформація</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="mailto:zmartcomua@gmail.com"
                        class="nav-link p-0 text-body-secondary"><i class="fas fa-envelope"></i>
                        zmartcomua@gmail.com</a></li>
                <li class="nav-item mb-2"><a href="tel:0730777572 " class="nav-link p-0 text-body-secondary"><i
                            class="fa fa-phone" aria-hidden="true"></i> +38 073-077-75-72</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary"><i
                            class="fa fa-location-arrow" aria-hidden="true"></i> Одесса, пром рынок "7 км",
                        ул.Фабричная, маг. №2178</a></li>
            </ul>
        </div>
    </footer>
</div>
