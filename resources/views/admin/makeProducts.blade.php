@extends('admin.layouts.app')

@section('header')
    <div class="col-sm-6">
        <h1 class="m-0">Создание товара</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.edit_products') }}">Товары</a></li>
            <li class="breadcrumb-item active">Создание</li>
        </ol>
    </div>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus mr-2"></i>Новый товар
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                                @csrf
                                
                                <div class="row">
                                    <!-- Основная информация -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-info-circle mr-2"></i>Основная информация
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="title">Название товара <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                                           placeholder="Введите название товара" value="{{ old('title') }}" required>
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="articule">Артикул <span class="text-danger">*</span></label>
                                                    <input type="text" name="articule" id="articule" class="form-control @error('articule') is-invalid @enderror" 
                                                           placeholder="Введите артикул" value="{{ old('articule') }}" required>
                                                    @error('articule')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="price">Цена <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                                               placeholder="0" value="{{ old('price') }}" min="0" step="0.01" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">₴</span>
                                                        </div>
                                                    </div>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="discount">Скидка (%)</label>
                                                    <input type="number" name="discount" id="discount" class="form-control" 
                                                           placeholder="0" value="{{ old('discount', 0) }}" min="0" max="100">
                                                </div>

                                                <div class="form-group">
                                                    <label for="description">Описание</label>
                                                    <textarea name="description" id="description" class="form-control" rows="4" 
                                                              placeholder="Подробное описание товара">{{ old('description') }}</textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="brand">Бренд</label>
                                                    <input type="text" name="brand" id="brand" class="form-control" 
                                                           placeholder="Название бренда" value="{{ old('brand') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Категории -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-tags mr-2"></i>Категории
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Выберите категории</label>
                                                    <div class="row">
                                                        @foreach($categories as $category)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" 
                                                                           id="category_{{ $category->id }}" name="category[]" 
                                                                           value="{{ $category->id }}" {{ in_array($category->id, old('category', [])) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="category_{{ $category->id }}">
                                                                        {{ $category->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Изображения -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-images mr-2"></i>Изображения
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="images">Загрузить изображения <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('images') is-invalid @enderror" 
                                                               id="images" name="images[]" multiple accept="image/*" required>
                                                        <label class="custom-file-label" for="images">Выберите файлы...</label>
                                                    </div>
                                                    @error('images')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Поддерживаемые форматы: JPG, PNG, GIF, WEBP. Максимальный размер: 5MB
                                                    </small>
                                                </div>

                                                <!-- Предварительный просмотр -->
                                                <div id="imagePreview" class="mt-3" style="display: none;">
                                                    <h6>Предварительный просмотр:</h6>
                                                    <div id="previewContainer" class="row"></div>
                                                </div>

                                                <!-- Прогресс загрузки -->
                                                <div id="uploadProgress" class="mt-3" style="display: none;">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                             role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                    <small class="text-muted">Загрузка...</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Дополнительные настройки -->
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-cog mr-2"></i>Дополнительно
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="availability">Наличие</label>
                                                    <select name="availability" id="availability" class="form-control">
                                                        <option value="1">В наличии</option>
                                                        <option value="2">Нет в наличии</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="condition_item">Состояние</label>
                                                    <select name="condition_item" id="condition_item" class="form-control">
                                                        <option value="1">Новое</option>
                                                        <option value="2">Б/У</option>
                                                        <option value="3">Отремонтировано</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Кнопки действий -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <button type="submit" class="btn btn-primary btn-lg mr-3" id="submitBtn">
                                                    <i class="fas fa-save mr-2"></i>Создать товар
                                                </button>
                                                <a href="{{ route('admin.edit_products') }}" class="btn btn-secondary btn-lg">
                                                    <i class="fas fa-times mr-2"></i>Отмена
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
    // Предварительный просмотр изображений
    $('#images').change(function() {
        const files = this.files;
        const previewContainer = $('#previewContainer');
        const imagePreview = $('#imagePreview');
        
        if (files.length > 0) {
            imagePreview.show();
            previewContainer.empty();
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = $(`
                            <div class="col-6 mb-2">
                                <div class="position-relative">
                                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0">
                                        <span class="badge badge-primary">${index + 1}</span>
                                    </div>
                                </div>
                            </div>
                        `);
                        previewContainer.append(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Обновляем label
            $('.custom-file-label').text(`${files.length} файл(ов) выбрано`);
        } else {
            imagePreview.hide();
            $('.custom-file-label').text('Выберите файлы...');
        }
    });

    // Валидация формы
    $('#createProductForm').submit(function(e) {
        const submitBtn = $('#submitBtn');
        const uploadProgress = $('#uploadProgress');
        
        // Проверяем наличие изображений
        if ($('#images').get(0).files.length === 0) {
            e.preventDefault();
            alert('Пожалуйста, выберите хотя бы одно изображение');
            return false;
        }
        
        // Показываем прогресс
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Создание...');
        uploadProgress.show();
        
        // Имитируем прогресс загрузки
        let progress = 0;
        const progressBar = $('.progress-bar');
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.css('width', progress + '%');
        }, 200);
        
        // Очищаем интервал при завершении
        $(this).one('submit', function() {
            clearInterval(interval);
            progressBar.css('width', '100%');
        });
    });

    // Автоматическое заполнение артикула
    $('#title').on('input', function() {
        const title = $(this).val();
        if (title && !$('#articule').val()) {
            const articule = title.replace(/[^a-zA-Zа-яА-Я0-9]/g, '').substring(0, 10).toUpperCase();
            $('#articule').val(articule);
        }
    });

    // Подсветка обязательных полей
    $('input[required], select[required]').on('blur', function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endsection
