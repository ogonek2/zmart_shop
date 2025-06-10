<!-- Сайдбар -->
<aside class="col-md-3 d-none d-md-block position-sticky border-end py-4">
    <ul class="list-group">
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Электрочайники</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Вентиляторы</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Микроволновые
                печи</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Посуда</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Термосы</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Пылесосы</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Утюги и
                гладильные системы</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Весы
                торговые</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Набор ножей</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Тостеры</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Духовки
                (Электрические печи)</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Хлебопечки</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Мультиварки</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Бутербродницы и
                вафельницы</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Мясорубки</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Грили и
                электрошашлычницы</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Фритюрницы</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Блендеры
                стационарные</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Кофемолки</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Миксеры</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Портативные
                газовые плиты</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Настольные
                плиты</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link"
                href="#">Соковыжималки</a></li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Измельчители</a>
        </li>
        <li class="list-group-item border-0 bg-white hover-effect-1"><a class="nav-link" href="#">Планетарные
                миксеры</a></li>
        <!-- и т.д. -->
    </ul>
    @guest
        @if (Route::has('login'))
            <div class="card bg-light w-100 py-1 my-3 border-0">
                <div class="card-body text-center">
                    <p style="font-size: 14px">
                        Увійдіть, щоб отримувати рекомендації, персональні бонуси і знижки.
                    </p>
                    <a class="btn btn-primary" href="{{ route('login') }}">
                        Увійти або створити кабінет
                    </a>
                </div>
            </div>
        @endif
    @endguest
    <ul class="list-group">
        <li class="list-group-item bg-white border-0">
            <h4 class="fw-medium">
                Інформація
            </h4>
        </li>
        <li class="list-group-item bg-white border-0">
            <a href="#" class="btn w-100" style="text-align: left">
                О нас
            </a>
        </li>
        <li class="list-group-item bg-white border-0">
            <a href="#" class="btn w-100" style="text-align: left">
                Оплата та доставка
            </a>
        </li>
        <li class="list-group-item bg-white border-0">
            <a href="#" class="btn w-100" style="text-align: left">
                Контакти
            </a>
        </li>
    </ul>
</aside>
