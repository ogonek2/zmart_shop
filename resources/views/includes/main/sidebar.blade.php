<!-- Сайдбар -->
<aside class="col-md-3 d-none d-md-block border-end px-0 position-relative py-4">
    <div id="sticky-sidebar" class="sidebar-wrapper px-4">
        <ul class="list-group">
            @foreach (get_all_category() as $item)
                <li class="list-group-item border-0 bg-white hover-effect-2">
                    <a class="nav-link d-flex justify-content-between w-100"
                        href="{{ route('catalog_category_page', $item->url) }}">
                        <span>
                            {{ $item->name }}
                        </span>
                        <span>
                            <b>{{ $item->products()->count() }}</b>
                        </span>
                    </a>
                </li>
            @endforeach
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
    </div>
</aside>
