@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Заказ #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Заказы</a></li>
                        <li class="breadcrumb-item active">Просмотр</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Успешно!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <!-- Информация о заказе -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Информация о заказе</h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.orders.exportPdf', $order->id) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-pdf"></i> Экспорт в PDF
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Номер заказа:</strong></td>
                                            <td>#{{ $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Дата заказа:</strong></td>
                                            <td>{{ $order->formatted_created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Статус:</strong></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    Новый заказ
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Способ доставки:</strong></td>
                                            <td>{{ $order->delivery_service }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Способ оплаты:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>Город:</strong></td>
                                            <td>{{ $order->city }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Отделение:</strong></td>
                                            <td>{{ $order->warehouse }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Подытог:</strong></td>
                                            <td>{{ $order->formatted_total_price }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Доставка:</strong></td>
                                            <td>0,00 ₴</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Итого:</strong></td>
                                            <td><strong>{{ $order->formatted_total_price }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if ($order->notes)
                                <div class="mt-3">
                                    <h6><strong>Примечания:</strong></h6>
                                    <p class="text-muted">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Товары в заказе -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Товары в заказе</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Товар</th>
                                            <th>Артикул</th>
                                            <th>Цена</th>
                                            <th>Количество</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->cart_items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if (isset($item['image']) && $item['image'])
                                                            <img src="{{ $item['image'] }}"
                                                                alt="{{ $item['name'] ?? 'Товар' }}"
                                                                class="img-thumbnail mr-2"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        @endif
                                                        <div>
                                                            <strong>{{ $item['name'] ?? 'Название не указано' }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $item['articule'] ?? 'Не указан' }}</td>
                                                <td>{{ number_format($item['price'] ?? 0, 2, ',', ' ') }} ₴</td>
                                                <td>{{ $item['quantity'] ?? 1 }}</td>
                                                <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2, ',', ' ') }}
                                                    ₴</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Товары не найдены</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Информация о клиенте и управление -->
                <div class="col-md-4">
                    <!-- Информация о клиенте -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Информация о клиенте</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Имя:</strong> {{ $order->name }}</p>
                            <p><strong>Фамилия:</strong> {{ $order->lastname }}</p>
                            <p><strong>Отчество:</strong> {{ $order->fathername ?? 'Не указано' }}</p>
                            <p><strong>Телефон:</strong> {{ $order->phone }}</p>
                            <p><strong>Город:</strong> {{ $order->city }}</p>
                            <p><strong>Отделение:</strong> {{ $order->warehouse }}</p>
                            @if($order->manual_address)
                            <p><strong>Адрес:</strong> {{ $order->manual_address }}</p>
                            @endif
                            @if($order->comment)
                            <p><strong>Комментарий:</strong> {{ $order->comment }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Управление статусом -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Изменить статус</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="status">Новый статус:</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" selected>Ожидает подтверждения</option>
                                        <option value="confirmed">Подтвержден</option>
                                        <option value="processing">В обработке</option>
                                        <option value="shipped">Отправлен</option>
                                        <option value="delivered">Доставлен</option>
                                        <option value="cancelled">Отменен</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save"></i> Обновить статус
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Действия</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.orders.exportPdf', $order->id) }}"
                                class="btn btn-success btn-block mb-2">
                                <i class="fas fa-file-pdf"></i> Экспорт в PDF
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Назад к списку
                            </a>
                        </div>
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
