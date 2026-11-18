<aside class="zinfo-nav" aria-label="Інформаційні сторінки">
    <p class="zinfo-nav__label">Розділи</p>
    <nav class="zinfo-nav__list">
        <a href="{{ route('pro_kompaniiu') }}" class="{{ request()->routeIs('pro_kompaniiu') ? 'is-active' : '' }}">Про компанію</a>
        <a href="{{ route('oplata_i_dostavka') }}" class="{{ request()->routeIs('oplata_i_dostavka') ? 'is-active' : '' }}">Оплата і доставка</a>
        <a href="{{ route('obmin_ta_povernennia') }}" class="{{ request()->routeIs('obmin_ta_povernennia') ? 'is-active' : '' }}">Обмін та повернення</a>
        <a href="{{ route('kontaktna_informatsiia') }}" class="{{ request()->routeIs('kontaktna_informatsiia') ? 'is-active' : '' }}">Контакти</a>
        <a href="{{ route('dohovir_oferty') }}" class="{{ request()->routeIs('dohovir_oferty') ? 'is-active' : '' }}">Договір оферти</a>
        <a href="{{ route('uhoda_korystuvacha') }}" class="{{ request()->routeIs('uhoda_korystuvacha') ? 'is-active' : '' }}">Умови використання</a>
        <a href="{{ route('privacy_policy') }}" class="{{ request()->routeIs('privacy_policy') ? 'is-active' : '' }}">Політика конфіденційності</a>
    </nav>
</aside>
