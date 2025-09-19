@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Статистика заказов</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Заказы</a></li>
                    <li class="breadcrumb-item active">Статистика</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Основные показатели -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_orders'] }}</h3>
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
                        <h3>{{ $stats['pending_orders'] }}</h3>
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
                        <h3>{{ $stats['delivered_orders'] }}</h3>
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
                        <h3>{{ $stats['cancelled_orders'] }}</h3>
                        <p>Отменено</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Финансовые показатели -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Финансовые показатели</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Общая выручка</span>
                                <span class="info-box-number">{{ number_format($stats['total_revenue'], 2, ',', ' ') }} ₴</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Выручка за текущий месяц</span>
                                <span class="info-box-number">{{ number_format($stats['monthly_revenue'], 2, ',', ' ') }} ₴</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Статистика по способам оплаты</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Накладений платіж</span>
                                <span class="info-box-number">{{ $stats['payment_stats']['cash'] }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-primary">
                            <span class="info-box-icon"><i class="fas fa-university"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Банківський переказ</span>
                                <span class="info-box-number">{{ $stats['payment_stats']['bank_transfer'] }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Картою при отриманні</span>
                                <span class="info-box-number">{{ $stats['payment_stats']['card_payment'] }}</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-store"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">При самовивозі</span>
                                <span class="info-box-number">{{ $stats['payment_stats']['pickup_payment'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Аналитика</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Конверсия заказов</h6>
                                @if($stats['total_orders'] > 0)
                                    @php
                                        $conversionRate = (($stats['delivered_orders'] / $stats['total_orders']) * 100);
                                    @endphp
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: {{ $conversionRate }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($conversionRate, 1) }}% заказов успешно доставлено</small>
                                @else
                                    <p class="text-muted">Нет данных для расчета</p>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Средний чек</h6>
                                @if($stats['total_orders'] > 0)
                                    @php
                                        $averageOrder = $stats['total_revenue'] / $stats['total_orders'];
                                    @endphp
                                    <h4 class="text-success">{{ number_format($averageOrder, 2, ',', ' ') }} ₴</h4>
                                    <small class="text-muted">Средняя сумма заказа</small>
                                @else
                                    <p class="text-muted">Нет данных для расчета</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Кнопка возврата -->
        <div class="row">
            <div class="col-12">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад к списку заказов
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Здесь можно добавить JavaScript для интерактивных графиков
    // Например, используя Chart.js или другие библиотеки
});
</script>
@endsection
