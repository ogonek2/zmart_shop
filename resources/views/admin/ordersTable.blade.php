@extends('admin.layouts.app')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6>Таблица заказов</h6>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дата</th>
                                        <th>Имя</th>
                                        <th>Состояние</th>
                                        <th>Общая стоимость</th>
                                        <th>Телефон</th>
                                        <th>Доставка</th>
                                        <th>Развернуть</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders_data as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <button class="btn w-100 h-100 text-left text-dark">
                                                    {{ $item->name }} {{ $item->lastname }} {{ $item->fathername }}
                                                </button>
                                            </td>
                                            <td>
                                                {{ $item->created_at }}
                                            </td>
                                            <td>
                                                {{ $item->total_price }} UAH
                                            </td>
                                            <td>
                                                <a href="tel:{{ $item->phone }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $item->phone }} <i class="fas fa-link"></i>
                                                </a>
                                            </td>
                                            <td>
                                                {{ $item->delivery_service }}
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-outline-primary w-100" data-bs-toggle="modal"
                                                    data-bs-target="#productsModal{{ $item->id }}">
                                                    Просмотр
                                                </button>
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
                                                    @php
                                                        $cartItems = json_decode($item->cart, true); // true — для получения массива
                                                    @endphp
                                                    
                                                    <div class="modal-body">
                                                        <div class="card mb-2 p-2 border">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <span>Доставка: <b class="text-info">{{ $item->delivery_service ?? 'Отсутствует' }}</b></span><br>
                                                                    <span>Город: <b class="text-info">{{ $item->city ?? 'Отсутствует'  }}</b></span><br>
                                                                    <span>Отделение: <b class="text-info">{{ $item->warehouse ?? 'Отсутствует' }}</b></span><br>
                                                                    <span>Адресс доставки: <b class="text-info">{{ $item->manual_address ?? 'Отсутствует' }}</b></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($cartItems)
                                                            @foreach($cartItems as $cartItem)
                                                                <div class="card mb-2 p-2 border">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-12">
                                                                            <b>ART: {{ $cartItem['articule'] ?? 'Пусто' }}</b>
                                                                            <h6>{{ $cartItem['name'] }}</h6>
                                                                            <span>Цена: {{ $cartItem['price'] }} грн</span> / <spam>Кол-во: {{ $cartItem['quantity'] }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p>Кошик порожній або не вдалося розпарсити JSON.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn bg-gradient-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
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
