<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <h1
                    style="border-left: 5px solid  orange; padding-left: 10px; color: orrange; font-size: 30px; font-weight: bold;">
                    ZMART
                </h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <div class="search-wrapper d-flex align-items-center rounded-pill bg-light"
                            style="height: 40px; max-width: 300px;">
                            <input type="text" class="form-control border-0 bg-transparent shadow-none"
                                placeholder="Поиск" style="font-size: 16px;">
                            <button
                                class="btn btn-dark rounded-pill d-flex align-items-center justify-content-center ms-2"
                                style="width: 80px; height: 40px;">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="fa-solid fa-location-dot"></i> Одесса
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            +38 073-077-75-72
                        </a>
                        <div class="dropdown-menu bg-white border-0" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Виклик</a>
                            <a class="dropdown-item" href="#" style="color: orange">Змовити дзвінок</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
        <div class="container">
            <!-- Содержимое навбара -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <!-- Кнопка Dropdown 1 -->
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Каталог
                        </a>
                        <div class="dropdown-menu w-100 mt-0 border-0 rounded-0 bg-dark text-white">
                            <div class="container py-3">
                                <div class="row row-cols-2 row-cols-md-5 g-3 text-start">
                                    <div class="col">
                                        <h6 class="text-warning">Блендери</h6>
                                        <a class="dropdown-item text-white" href="#">Охотничі</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Кофемашина</h6>
                                        <a class="dropdown-item text-white" href="#">АиР</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Пилесоси</h6>
                                        <a class="dropdown-item text-white" href="#">95Х18</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Мясорубка</h6>
                                        <a class="dropdown-item text-white" href="#">ГОІ</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Тостери</h6>
                                        <a class="dropdown-item text-white" href="#">Клинки</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Чайники</h6>
                                        <a class="dropdown-item text-white" href="#">Клинки</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Мультиварки</h6>
                                        <a class="dropdown-item text-white" href="#">Клинки</a>
                                    </div>
                                    <div class="col">
                                        <h6 class="text-warning">Посуд</h6>
                                        <a class="dropdown-item text-white" href="#">Клинки</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Кнопка Dropdown 2 (без содержимого) -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Знижки <i class="fa-solid fa-tag"></i>
                        </a>
                        <div class="dropdown-menu w-100 mt-0 border-0 rounded-0 bg-dark text-white">
                            <div class="container py-3">
                                <div class="row row-cols-2 row-cols-md-5 g-3 text-start">
                                    <div class="col">
                                        <h6 class="text-warning">Категорії</h6>
                                        <a class="dropdown-item text-white" href="#">Охотничі</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Новинки <i class="fa-solid fa-bullhorn"></i>
                        </a>
                        <div class="dropdown-menu w-100 mt-0 border-0 rounded-0 bg-dark text-white">
                            <div class="container py-3">
                                <div class="row row-cols-2 row-cols-md-5 g-3 text-start">
                                    <div class="col">
                                        <h6 class="text-warning">Категорії</h6>
                                        <a class="dropdown-item text-white" href="#">Охотничі</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fa fa-user-circle" aria-hidden="true"></i> Увійти
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </li>
                    @endguest
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"></i>
                            Кошик</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
