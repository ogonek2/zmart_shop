@extends('admin.layouts.app')


@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3>Создать категорию</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.category.store') }}" method="POST" class="form-block">
                                @csrf
                                <div class="form-body">
                                    <div class="form-group">
                                        <label for="nameCategory">Название</label>
                                        <input type="text" class="form-control" id="nameCategory"
                                            placeholder="Стиральные машины..." name="categoryName">
                                    </div>
                                </div>
                                <div class="form-footer">
                                    <button type="submit" class="btn btn-outline-success">
                                        Создать
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Количество товаров</th>
                                        <th>Страница</th>
                                        <th>Управление</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <button class="btn w-100 h-100 text-left text-dark" data-bs-toggle="modal"
                                                    data-bs-target="#categoryModal{{ $item->id }}">
                                                    {{ $item->name }} <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn w-100 h-100 text-center btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#categoryProductsListModal{{ $item->id }}">
                                                    {{ $item->products()->count() }} <i class="fa fa-list"
                                                        aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('catalog_category_page', $item->url) }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    Перейти <i class="fas fa-link"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.category.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Точно видалити?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn bg-gradient-danger w-100" type="submit">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="categoryProductsListModal{{ $item->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="categoryProductsListModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document"
                                                style="max-width: 600px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-normal"
                                                            id="categoryProductsListModalLabel{{ $item->id }}">
                                                            #{{ $item->id }} / {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn text-dark" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group">
                                                            @foreach ($item->products as $product)
                                                                <li class="list-group-element d-flex mb-2">
                                                                    <b class="mr-1">
                                                                        #{{ $product->id }} |
                                                                    </b>
                                                                    <span class="w-50">
                                                                        {{ $product->name }}<br>
                                                                        <small class="text-secondary"><b>ART: </b>
                                                                            {{ $product->articule }}</small>
                                                                    </span>
                                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info ml-auto">
                                                                        <span>
                                                                            К управлению <i class="fa fa-share"
                                                                                aria-hidden="true"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="categoryModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="categoryModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-normal"
                                                            id="categoryModalLabel{{ $item->id }}">
                                                            #{{ $item->id }} / {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn text-dark" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.category.update', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-8">
                                                                        <label>Название <b
                                                                                style="color: #e91e63">*</b></label>
                                                                        <div class="input-group input-group-outline">
                                                                            <input type="text"
                                                                                placeholder="Стиральные машины...."
                                                                                name="categoryName" class="form-control"
                                                                                value="{{ $item->name }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn bg-gradient-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn bg-gradient-primary">Зберегти
                                                                зміни</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                {{-- <li class="nav-item"><a class="nav-link" href="#upload_file"
                                        data-toggle="tab">Сгенерировать подборку</a></li> --}}
                                <li class="nav-item"><a class="nav-link active" href="#create" data-toggle="tab">Создать
                                        каталог в ручную</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                {{-- <div class="tab-pane" id="upload_file">
                                    <form class="form-horizontal" method="POST">
                                        @csrf

                                    </form>
                                </div> --}}

                                <div class="active tab-pane" id="create">
                                    <form class="form-vertical" method="POST" action="{{ route('admin.catalog.store') }}">
                                        @csrf
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label for="catalogNameSetting">Название каталога</label>
                                                <input type="text" class="form-control" name="catalogNameSetting"
                                                    id="catalogNameSetting" placeholder="Товары для расслабления...">
                                            </div>
                                            <div class="form-group">
                                                <label for="catalogSelectProductsSetting">Оберіть продукти</label>
                                                <select class="select2" multiple="multiple"
                                                    data-placeholder="Выберите несколько товаров" style="width: 100%;"
                                                    name="catalogSelectProductsSetting[]"
                                                    id="catalogSelectProductsSetting">
                                                    @foreach ($products as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name }} ~~~ ART: {{ $item->articule }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-footer">
                                            <button class="btn btn-outline-success">
                                                Создать
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Количество товаров</th>
                                        <th>Страница</th>
                                        <th>Управление</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($catalog as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                <button class="btn w-100 h-100 text-left text-dark" data-bs-toggle="modal"
                                                    data-bs-target="#catalogModal{{ $item->id }}">
                                                    {{ $item->name }} <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <button class="btn w-100 h-100 text-center btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#catalogProductsListModal{{ $item->id }}">
                                                    {{ $item->products()->count() }} <i class="fa fa-list"
                                                        aria-hidden="true"></i>
                                                </button>
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('catalog_category_page', $item->url) }}"
                                                    target="_blank" rel="noopener noreferrer">
                                                    Перейти <i class="fas fa-link"></i>
                                                </a> --}}
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.catalog.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Точно видалити?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn bg-gradient-danger w-100" type="submit">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="catalogProductsListModal{{ $item->id }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="catalogProductsListModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document"
                                                style="max-width: 600px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-normal"
                                                            id="catalogProductsListModalLabel{{ $item->id }}">
                                                            #{{ $item->id }} / {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn text-dark"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <ul class="list-group">
                                                            @foreach ($item->products as $product)
                                                                <li class="list-group-element d-flex mb-2">
                                                                    <b class="mr-1">
                                                                        #{{ $product->id }} |
                                                                    </b>
                                                                    <span class="w-50">
                                                                        {{ $product->name }}<br>
                                                                        <small class="text-secondary"><b>ART: </b>
                                                                            {{ $product->articule }}</small>
                                                                    </span>
                                                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info ml-auto">
                                                                        <span>
                                                                            К управлению <i class="fa fa-share"
                                                                                aria-hidden="true"></i>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="catalogModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="catalogModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-normal"
                                                            id="catalogModalLabel{{ $item->id }}">
                                                            #{{ $item->id }} / {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn text-dark"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('admin.catalog.update', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-body">
                                                                <div class="form-group">
                                                                    <label for="catalogNameSetting">Название
                                                                        каталога</label>
                                                                    <input type="text" class="form-control"
                                                                        name="catalogNameSetting" id="catalogNameSetting"
                                                                        placeholder="Товары для расслабления..."
                                                                        value="{{ $item->name }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        for="catalogSelectProductsSetting_edit_{{ $item->id }}">Оберіть
                                                                        продукти</label>
                                                                    <select class="select2_edit" multiple="multiple"
                                                                        data-placeholder="Выберите несколько товаров"
                                                                        style="width: 100%;"
                                                                        name="catalogSelectProductsSetting[]">
                                                                        @foreach ($products as $elem)
                                                                            <option value="{{ $elem->id }}"
                                                                                @if ($item->products->contains($elem->id)) selected @endif>
                                                                                ~ ART:
                                                                                {{ $elem->articule }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-footer">
                                                                <button class="btn btn-outline-success">
                                                                    Создать
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn bg-gradient-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit"
                                                                class="btn bg-gradient-primary">Зберегти
                                                                зміни</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        //Initialize Select2 Elements
        $(`.select2`).select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    </script>
    <script>
        // При показе каждой модалки
        // Для селектов внутри модалок — при каждом открытии
        $(document).on('shown.bs.modal', function(e) {
            $(e.target).find('.select2_edit').select2({
                dropdownParent: $(e.target),
                width: '100%'
            });
        });
    </script>
@endsection
