@extends('admin.layouts.app')

@section('header')
    <div class="col-sm-6">
        <h1 class="m-0">Управление товарами</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
            <li class="breadcrumb-item active">Товары</li>
        </ol>
    </div>
@endsection

@section('content')
    <!-- CSRF токен для AJAX запросов -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <section class="content">
        <div class="container-fluid">
            <!-- Панель управления -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-2"></i>Список товаров
                            </h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary mr-2">
                                <i class="fas fa-plus mr-2"></i>Добавить товар
                            </a>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                                <i class="fas fa-file-excel mr-2"></i>Импорт Excel
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Фильтры и поиск -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Поиск по названию...">
                        </div>
                        <div class="col-md-2">
                            <select id="categoryFilter" class="form-control">
                                <option value="">Все категории</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="availabilityFilter" class="form-control">
                                <option value="">Все товары</option>
                                <option value="1">В наличии</option>
                                <option value="2">Нет в наличии</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="sortFilter" class="form-control">
                                <option value="id_desc">Сначала новые</option>
                                <option value="id_asc">Сначала старые</option>
                                <option value="name_asc">По названию А-Я</option>
                                <option value="name_desc">По названию Я-А</option>
                                <option value="price_asc">По цене ↑</option>
                                <option value="price_desc">По цене ↓</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="resetFilters" class="btn btn-secondary">
                                <i class="fas fa-undo mr-2"></i>Сбросить фильтры
                            </button>
                        </div>
                    </div>

                    <!-- Таблица товаров -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Изображение</th>
                                    <th>Название</th>
                                    <th>Артикул</th>
                                    <th>Категория</th>
                                    <th>Цена</th>
                                    <th>Наличие</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\Product::with('categories')->get() as $product)
                                <tr data-product-id="{{ $product->id }}" 
                                    data-category="{{ $product->categories->pluck('id')->join(',') }}"
                                    data-availability="{{ $product->availability }}">
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
                                        <code>{{ $product->articule }}</code>
                                    </td>
                                    <td>
                                        @foreach($product->categories as $category)
                                            <span class="badge badge-info">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="text-success font-weight-bold">{{ number_format($product->price, 0, ',', ' ') }} ₴</span>
                                        @if($product->discount > 0)
                                            <br><small class="text-muted text-decoration-line-through">
                                                {{ number_format($product->price * (1 + $product->discount / 100), 0, ',', ' ') }} ₴
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->availability === 1)
                                            <span class="badge badge-success">В наличии</span>
                                        @else
                                            <span class="badge badge-danger">Нет в наличии</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->condition_item === 1)
                                            <span class="badge badge-primary">Новое</span>
                                        @elseif($product->condition_item === 2)
                                            <span class="badge badge-warning">Б/У</span>
                                        @else
                                            <span class="badge badge-info">Отремонтировано</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-warning" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('catalog_product_page', $product->url) }}" 
                                               class="btn btn-sm btn-info" title="Просмотреть" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Вы действительно хотите удалить товар \"{{ $product->name }}\"?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Статистика -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Всего товаров:</strong> {{ \App\Models\Product::count() }} | 
                                <strong>С изображениями:</strong> {{ \App\Models\Product::whereNotNull('image_path')->count() }} | 
                                <strong>Со скидкой:</strong> {{ \App\Models\Product::where('discount', '>', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button id="exportProducts" class="btn btn-success">
                                <i class="fas fa-download mr-2"></i>Экспорт
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Модальное окно подтверждения удаления -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить товар "<strong id="productNameToDelete"></strong>"?</p>
                    <p class="text-danger"><small>Это действие нельзя отменить!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно импорта Excel -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-excel mr-2"></i>Импорт товаров из Excel
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle mr-2"></i>Формат файла</h6>
                            <p class="mb-2">Файл должен содержать колонки:</p>
                            <ul class="mb-0 small">
                                <li><strong>title</strong> - название товара (обязательно)</li>
                                <li><strong>articule</strong> - артикул</li>
                                <li><strong>price</strong> - цена</li>
                                <li><strong>description</strong> - описание</li>
                                <li><strong>category</strong> - категория</li>
                            </ul>
                        </div>
                        
                        <!-- Прогресс загрузки -->
                        <div id="importProgress" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">Импорт выполняется в фоновом режиме...</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="submit" form="excelImportForm" class="btn btn-success" id="importBtn">
                        <i class="fas fa-upload mr-2"></i>Загрузить и импортировать
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Поиск и фильтрация
    function filterProducts() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const categoryFilter = $('#categoryFilter').val();
        const availabilityFilter = $('#availabilityFilter').val();
        
        $('#productsTable tbody tr').each(function() {
            const $row = $(this);
            const productName = $row.find('td:eq(2)').text().toLowerCase();
            const productCategory = $row.data('category');
            const productAvailability = $row.data('availability');
            
            let show = true;
            
            // Поиск по названию
            if (searchTerm && !productName.includes(searchTerm)) {
                show = false;
            }
            
            // Фильтр по категории
            if (categoryFilter && !productCategory.includes(categoryFilter)) {
                show = false;
            }
            
            // Фильтр по наличию
            if (availabilityFilter && productAvailability != availabilityFilter) {
                show = false;
            }
            
            $row.toggle(show);
        });
        
        updateStatistics();
    }
    
    // Обновление статистики
    function updateStatistics() {
        const visibleRows = $('#productsTable tbody tr:visible').length;
        const totalRows = $('#productsTable tbody tr').length;
        
        if (visibleRows !== totalRows) {
            $('.alert-info').html(`
                <i class="fas fa-filter mr-2"></i>
                <strong>Показано:</strong> ${visibleRows} из ${totalRows} товаров
            `);
        } else {
            $('.alert-info').html(`
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Всего товаров:</strong> ${totalRows}
            `);
        }
    }
    
    // События фильтрации
    $('#searchInput').on('input', filterProducts);
    $('#categoryFilter, #availabilityFilter').on('change', filterProducts);
    
    // Сброс фильтров
    $('#resetFilters').click(function() {
        $('#searchInput').val('');
        $('#categoryFilter').val('');
        $('#availabilityFilter').val('');
        filterProducts();
    });
    
    // Сортировка
    $('#sortFilter').on('change', function() {
        const sortValue = $(this).val();
        const $tbody = $('#productsTable tbody');
        const $rows = $tbody.find('tr').toArray();
        
        $rows.sort(function(a, b) {
            const $a = $(a);
            const $b = $(b);
            
            switch(sortValue) {
                case 'id_desc':
                    return $b.data('product-id') - $a.data('product-id');
                case 'id_asc':
                    return $a.data('product-id') - $b.data('product-id');
                case 'name_asc':
                    return $a.find('td:eq(2)').text().localeCompare($b.find('td:eq(2)').text());
                case 'name_desc':
                    return $b.find('td:eq(2)').text().localeCompare($a.find('td:eq(2)').text());
                case 'price_asc':
                    return parseFloat($a.find('td:eq(5)').text().replace(/[^\d]/g, '')) - 
                           parseFloat($b.find('td:eq(5)').text().replace(/[^\d]/g, ''));
                case 'price_desc':
                    return parseFloat($b.find('td:eq(5)').text().replace(/[^\d]/g, '')) - 
                           parseFloat($a.find('td:eq(5)').text().replace(/[^\d]/g, ''));
                default:
                    return 0;
            }
        });
        
        $tbody.empty().append($rows);
    });
    
    // Экспорт товаров
    $('#exportProducts').click(function() {
        const visibleRows = $('#productsTable tbody tr:visible');
        let csv = 'ID,Название,Артикул,Категория,Цена,Наличие,Статус\n';
        
        visibleRows.each(function() {
            const $row = $(this);
            const id = $row.data('product-id');
            const name = $row.find('td:eq(2)').text().replace(/"/g, '""');
            const articule = $row.find('td:eq(3)').text();
            const category = $row.find('td:eq(4)').text();
            const price = $row.find('td:eq(5)').text();
            const availability = $row.find('td:eq(6)').text();
            const status = $row.find('td:eq(7)').text();
            
            csv += `"${id}","${name}","${articule}","${category}","${price}","${availability}","${status}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'products_export.csv';
        link.click();
    });

    // Импорт Excel
    $('#excel_file').change(function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').text(fileName || 'Выберите файл...');
    });

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
                    
                    // Закрываем модальное окно
                    $('#importModal').modal('hide');
                    
                    // Очищаем форму
                    $('#excelImportForm')[0].reset();
                    $('.custom-file-label').text('Выберите файл...');
                    
                    // Перезагружаем страницу для обновления списка товаров
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
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

// Функция удаления товара
function deleteProduct(productId, productName) {
    console.log('deleteProduct вызван для товара:', productId, productName);
    
    $('#productNameToDelete').text(productName);
    $('#deleteModal').modal('show');
    
    $('#confirmDelete').off('click').on('click', function() {
        console.log('Подтверждение удаления для товара:', productId);
        
        // Создаем скрытую форму для удаления
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}`;
        
        console.log('Форма создана:', form.action);
        
        // Добавляем CSRF токен
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        console.log('CSRF токен:', csrfToken.value);
        
        // Добавляем метод DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        console.log('Метод:', methodField.value);
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        
        console.log('Форма готова к отправке');
        form.submit();
        
        console.log('Форма отправлена');
    });
}
</script>
@endsection
