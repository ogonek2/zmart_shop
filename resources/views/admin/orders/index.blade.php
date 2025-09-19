@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Управление заказами</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                        <li class="breadcrumb-item active">Заказы</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Статистика -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $orders->total() }}</h3>
                            <p>Всего заказов</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $orders->where('status', 'pending')->count() }}</h3>
                            <p>Ожидают подтверждения</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $orders->where('status', 'delivered')->count() }}</h3>
                            <p>Доставлено</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $orders->where('status', 'cancelled')->count() }}</h3>
                            <p>Отменено</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Таблица заказов -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Список заказов</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.statistics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Статистика
                        </a>
                        <a href="{{ route('admin.orders.createTest') }}" class="btn btn-success btn-sm ml-2">
                            <i class="fas fa-plus"></i> Тестовый заказ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>№ Заказа</th>
                                    <th>Клиент</th>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th>Способ доставки</th>
                                    <th>Способ оплаты</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->id }}</strong>
                                        </td>
                                        <td>
                                            <div>{{ $order->full_name }}</div>
                                            <small class="text-muted">{{ $order->phone }}</small>
                                        </td>
                                        <td>{{ $order->formatted_created_at }}</td>
                                        <td>
                                            <strong>{{ $order->formatted_total_price }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                Новый заказ
                                            </span>
                                        </td>
                                        <td>{{ $order->delivery_service }}</td>
                                        <td>
                                            @switch($order->payment)
                                                @case('cash')
                                                    <span class="badge badge-success">Накладений платіж</span>
                                                    @break
                                                @case('bank_transfer')
                                                    <span class="badge badge-primary">Банківський переказ</span>
                                                    @break
                                                @case('card_payment')
                                                    <span class="badge badge-info">Картою при отриманні</span>
                                                    @break
                                                @case('pickup_payment')
                                                    <span class="badge badge-warning">При самовивозі</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $order->payment }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-info" title="Просмотр">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.exportPdf', $order->id) }}"
                                                    class="btn btn-sm btn-success" title="Экспорт в PDF">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Заказы не найдены</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Пагинация -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Автоматическое скрытие уведомлений
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endsection
