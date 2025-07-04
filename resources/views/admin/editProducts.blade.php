@extends('admin.layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Артикуль</th>
                                        <th>Название</th>
                                        <th>Страница</th>
                                        <th>Полное редактирование</th>
                                        <th>Управление</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->articule }}</td>
                                            <td>
                                                <button class="btn w-100 h-100 text-left text-dark" data-bs-toggle="modal"
                                                    data-bs-target="#productsModal{{ $item->id }}">
                                                    {{ $item->name }} <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('catalog_product_page', $item->url) }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    Перейти <i class="fas fa-link"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-primary w-100">
                                                    Перейти к управлению
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('products.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Точно видалити?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn bg-gradient-danger w-100" type="submit">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="productsModal{{ $item->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="productsModalLabel{{ $item->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-weight-normal"
                                                            id="productsModalLabel{{ $item->id }}">
                                                            #{{ $item->id }} / {{ $item->name }}
                                                        </h5>
                                                        <button type="button" class="btn text-dark" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('products.update', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="type_form" value="demo_modal">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="title">Название товара</label>
                                                                <input type="text" class="form-control" name="title"
                                                                    id="title"
                                                                    value="{{ old('title', $item->name ?? '') }}"
                                                                    required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="articule">Артикул</label>
                                                                <input type="text" class="form-control" name="articule"
                                                                    id="articule"
                                                                    value="{{ old('articule', $item->articule ?? '') }}"
                                                                    required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="price">Цена</label>
                                                                <input type="number" class="form-control" name="price"
                                                                    id="price"
                                                                    value="{{ old('price', $item->price ?? '') }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn bg-gradient-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn bg-gradient-primary">Зберегти
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
@endsection
