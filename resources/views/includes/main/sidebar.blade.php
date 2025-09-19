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
    </div>
</aside>
