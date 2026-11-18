<footer class="zf">
    <div class="zh-wrap zf__main">
        <div class="zf__brand">
            <a href="{{ url('/') }}" class="zf__logo" aria-label="Zmart">
                <span>Z</span>mart
            </a>
            <p class="zf__about">
                Побутова техніка та господарські товари. Доставка по Україні, самовивіз з 7&nbsp;км.
            </p>
            <a href="tel:0730777572" class="zf__call">+38 073 077 75 72</a>
            <a href="tel:0770777582" class="zf__call">+38 077 077 75 82</a>
        </div>

        <nav class="zf__col" aria-label="Інформація">
            <h3 class="zf__title">Інформація</h3>
            <ul class="zf__list">
                <li><a href="{{ route('pro_kompaniiu') }}">Про компанію</a></li>
                <li><a href="{{ route('oplata_i_dostavka') }}">Оплата і доставка</a></li>
                <li><a href="{{ route('obmin_ta_povernennia') }}">Обмін та повернення</a></li>
                <li><a href="{{ route('kontaktna_informatsiia') }}">Контакти</a></li>
            </ul>
        </nav>

        <nav class="zf__col" aria-label="Документи">
            <h3 class="zf__title">Документи</h3>
            <ul class="zf__list">
                <li><a href="{{ route('dohovir_oferty') }}">Договір оферти</a></li>
                <li><a href="{{ route('uhoda_korystuvacha') }}">Умови використання</a></li>
                <li><a href="{{ route('privacy_policy') }}">Політика конфіденційності</a></li>
            </ul>
        </nav>

        <div class="zf__col">
            <h3 class="zf__title">Контакти</h3>
            <ul class="zf__meta">
                <li>
                    <span>Email</span>
                    <a href="mailto:zmartcomua@gmail.com">zmartcomua@gmail.com</a>
                </li>
                <li>
                    <span>Адреса</span>
                    <p>Одеса, промринок «7&nbsp;км», вул.&nbsp;Фабрична, маг.&nbsp;№2523</p>
                </li>
                <li>
                    <span>Графік</span>
                    <p>Пн–Чт, Сб–Нд: 06:00–14:00<br>П’ятниця — вихідний</p>
                </li>
            </ul>
        </div>
    </div>
</footer>
