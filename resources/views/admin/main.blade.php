@extends('admin.layouts.app')

@section('header')
    <div class="col-sm-6">
        <h1 class="m-0">Панель управления</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Статистика -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ \App\Models\Product::count() }}</h3>
                            <p>Всего товаров</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                            Управление товарами <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ \App\Models\Category::count() }}</h3>
                            <p>Категорий</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <a href="{{ route('admin.category.index') }}" class="small-box-footer">
                            Управление категориями <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \App\Models\Product::where('availability', 2)->count() }}</h3>
                            <p>Товаров нет в наличии</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                            Проверить <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ \App\Models\Product::where('discount', '>', 0)->count() }}</h3>
                            <p>Товаров со скидкой</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="small-box-footer">
                            Просмотреть <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>Быстрые действия
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus mr-2"></i>Добавить товар
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.category.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-folder-plus mr-2"></i>Создать категорию
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.edit_products') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-edit mr-2"></i>Редактировать товары
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('admin.orders') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-shopping-cart mr-2"></i>Заказы
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Импорт товаров из Excel -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-excel mr-2"></i>Импорт товаров из Excel
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <form id="excelImportForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="excel_file">Выберите файл Excel</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="excel_file" name="excel_file" 
                                                       accept=".xlsx,.xls,.csv" required>
                                                <label class="custom-file-label" for="excel_file">Выберите файл...</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Поддерживаемые форматы: .xlsx, .xls, .csv. Максимальный размер: 5MB
                                            </small>
                                        </div>
                                        <button type="submit" class="btn btn-success" id="importBtn">
                                            <i class="fas fa-upload mr-2"></i>Загрузить и импортировать
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-info-circle mr-2"></i>Формат файла
                                            </h6>
                                            <p class="card-text small">
                                                Файл должен содержать колонки:
                                            </p>
                                            <ul class="small mb-0">
                                                <li><strong>title</strong> - название товара</li>
                                                <li><strong>articule</strong> - артикул</li>
                                                <li><strong>price</strong> - цена</li>
                                                <li><strong>description</strong> - описание</li>
                                                <li><strong>category</strong> - категория</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Прогресс загрузки -->
                            <div id="importProgress" class="mt-3" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted">Импорт выполняется в фоновом режиме...</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Последние добавленные товары -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-2"></i>Последние добавленные товары
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Изображение</th>
                                            <th>Название</th>
                                            <th>Цена</th>
                                            <th>Категория</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(\App\Models\Product::latest()->take(5)->get() as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if($product->image_path)
                                                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; border-radius: 5px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($product->name, 30) }}</strong>
                                                @if($product->discount > 0)
                                                    <span class="badge badge-danger ml-2">-{{ $product->discount }}%</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', ' ') }} ₴</span>
                                            </td>
                                            <td>
                                                @foreach($product->categories as $category)
                                                    <span class="badge badge-info">{{ $category->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('catalog_product_page', $product->url) }}" 
                                                   class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Системная информация -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-server mr-2"></i>Системная информация
                            </h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Версия PHP
                                    <span class="badge badge-primary">{{ phpversion() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Версия Laravel
                                    <span class="badge badge-info">{{ app()->version() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Время работы
                                    <span class="badge badge-success">{{ gmdate('H:i:s', time() - LARAVEL_START) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Память
                                    <span class="badge badge-warning">{{ round(memory_get_usage() / 1024 / 1024, 2) }} MB</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>Статистика загрузки
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ \App\Models\Product::count() > 0 ? min(100, (\App\Models\Product::count() / 1000) * 100) : 0 }}%">
                                    {{ \App\Models\Product::count() }} товаров
                                </div>
                            </div>
                            <small class="text-muted">Цель: 1000 товаров</small>
                            
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Товары с изображениями</span>
                                    <span>{{ \App\Models\Product::whereNotNull('image_path')->count() }}/{{ \App\Models\Product::count() }}</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         style="width: {{ \App\Models\Product::count() > 0 ? (\App\Models\Product::whereNotNull('image_path')->count() / \App\Models\Product::count()) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
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
    // Обновление label для выбранного файла
    $('#excel_file').change(function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(fileName || 'Выберите файл...');
    });

    // Обработка формы импорта
    $('#excelImportForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const importBtn = $('#importBtn');
        const importProgress = $('#importProgress');
        const progressBar = $('.progress-bar');
        
        // Проверяем наличие файла
        if (!$('#excel_file')[0].files.length) {
            alert('Пожалуйста, выберите файл Excel');
            return;
        }
        
        // Блокируем кнопку и показываем прогресс
        importBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Загрузка...');
        importProgress.show();
        
        // Имитируем прогресс
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 10;
            if (progress > 90) progress = 90;
            progressBar.css('width', progress + '%');
        }, 200);
        
        // Отправляем запрос
        $.ajax({
            url: '{{ route("admin.excel.upload") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                clearInterval(interval);
                progressBar.css('width', '100%');
                
                setTimeout(() => {
                    importBtn.prop('disabled', false).html('<i class="fas fa-upload mr-2"></i>Загрузить и импортировать');
                    importProgress.hide();
                    progressBar.css('width', '0%');
                    
                    // Показываем уведомление об успехе
                    alert('Файл успешно загружен! Импорт запущен в фоновом режиме.');
                    
                    // Очищаем форму
                    $('#excelImportForm')[0].reset();
                    $('.custom-file-label').text('Выберите файл...');
                }, 1000);
            },
            error: function(xhr) {
                clearInterval(interval);
                importBtn.prop('disabled', false).html('<i class="fas fa-upload mr-2"></i>Загрузить и импортировать');
                importProgress.hide();
                progressBar.css('width', '0%');
                
                let errorMessage = 'Произошла ошибка при загрузке файла';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            }
        });
    });
});
</script>
@endsection
