@extends('admin.layouts.app')

@section('styles')
    <style>
        .rating-stars i {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-stars i.hovered,
        .rating-stars i.selected {
            color: #ffc107;
            /* Золотой */
        }
    </style>
    <style>
        .img-thumb {
            margin: 5px;
            position: relative;
        }

        .img-thumb img {
            height: 120px;
            border-radius: 5px;
            border: 1px solid #ccc;
            object-fit: cover;
        }

        .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 14px;
            line-height: 18px;
            text-align: center;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <h3 class="d-inline-block">Демонстрация</h3>

                        {{-- Главное изображение --}}
                        <div id="main-image-wrapper" class="rounded-0 mb-1 d-flex justify-content-center position-relative">
                            @if (empty($product->image_path))
                                <div class="d-flex bg-light align-items-center flex-column justify-content-center"
                                    style="width: 80%; aspect-ratio: 1 / 1; object-fit: contain; margin: auto;">
                                    <small class="text-secondary"><i class="fas fa-image fa-2x"></i></small>
                                </div>
                            @else
                                <img id="main-product-image"
                                    style="width: 80%; aspect-ratio: 1 / 1; object-fit: contain; margin: auto;"
                                    class="rounded-4 fit product-image" src="{{ $product->image_path }}" />
                            @endif
                        </div>

                        {{-- Миниатюры + управление --}}
                        <div class="d-flex justify-content-start mb-3 product-image-thumbs flex-wrap gap-1">

                            @foreach ($images as $item)
                                <div>
                                    <div class="position-relative border rounded-2 product-image-thumb"
                                        style="width: 60px; aspect-ratio: 1 / 1; overflow: hidden; cursor: pointer;">
                                        <img width="60" height="60" class="rounded-2 w-100 h-100 object-fit-cover"
                                            src="{{ $item->src }}" onclick="setMainImage(this.src)">
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-info" type="button" id="MenuMoreCartItem"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu p-0" aria-labelledby="MenuMoreCartItem">
                                            <li>
                                                <a href="{{ route('products.destroyImage', ['id' => $product->id, 'image' => $item->id]) }}"
                                                    class="btn btn-danger w-100">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> Удалить
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('products.firstImage', ['id' => $product->id, 'image' => $item->id]) }}"
                                                    class="btn btn-warning w-100">
                                                    <i class="fa fa-star" aria-hidden="true"></i> Сделать главной
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Скрытый инпут загрузки --}}
                            <form action="{{ route('products.addImage', $product->id) }}" method="POST"
                                enctype="multipart/form-data" id="addImageForm">
                                @csrf
                                {{-- Кнопка "+" --}}
                                <div onclick="triggerImageUpload()"
                                    class="border mx-1 rounded-2 d-flex align-items-center justify-content-center bg-light"
                                    style="width: 60px; height: 60px; cursor: pointer;">
                                    <i class="fas fa-plus text-secondary"></i>
                                </div>

                                {{-- Скрытый input type="file" --}}
                                <input type="file" name="image" id="imageUploadInput" class="d-none" accept="image/*">
                            </form>
                        </div>
                    </div>

                    {{-- Информация о товаре --}}
                    <div class="ps-lg-3 col-sm-6">
                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <span class="input-group-text">ART</span>
                                <input type="text" name="articule" value="{{ $product->articule }}" class="form-control">
                                <span class="input-group-text"><i class="fas fa-edit"></i></span>
                            </div>
                            <div class="form-group row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-id-card"
                                                aria-hidden="true"></i></span>
                                        <input type="text" name="title" value="{{ $product->name }}"
                                            class="form-control">
                                        <span class="input-group-text"><i class="fas fa-edit"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" value="{{ route('catalog_product_page', $product->url) }}"
                                            class="form-control overflow-scroll" onclick="this.select();" readonly>
                                        <a href="{{ route('catalog_product_page', $product->url) }}" class="btn btn-success"
                                            target="_blank">
                                            Перейти
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Категория</label>
                                <div>
                                    <select name="category" class="form-control">
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($product->categories->contains($item->id)) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="input-group mt-2">
                                <span class="input-group-text">$</span>
                                <input type="text" name="price" value="{{ $product->price }}" class="form-control">
                                <span class="input-group-text"><i class="fas fa-edit"></i></span>
                            </div>
                            <div class="input-group mt-2">
                                <span class="input-group-text">%</span>
                                <input type="text" name="discount" value="{{ $product->discount }}"
                                    class="form-control">
                                <span class="input-group-text"><i class="fas fa-edit"></i></span>
                            </div>
                            <div class="form-group mt-2">
                                <label for="summernote"><span class="input-group-text">Опис</span></label>
                                <textarea class="form-control" id="summernote" name="description">{!! $product->description !!}</textarea>
                            </div>
                            <input type="hidden" name="type_form" value="demo_full">
                            <div class="form-footer">
                                <button type="submit" class="btn btn-success">
                                    Обновить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type_form" value="demo_values">
                            <div class="form-body">
                                {{-- Характеристики --}}
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span>Характеристики</span>
                                        <button type="button" class="btn btn-sm btn-success" id="add-spec-row">Добавить
                                            характеристику</button>
                                    </div>
                                    <div class="card-body" id="specifications-container">
                                        @if (!empty($product->package))
                                            @foreach ($product->package as $index => $item)
                                                <div class="form-row mb-2 align-items-center">
                                                    <div class="col">
                                                        <input type="text" name="specs[{{ $index }}][name]"
                                                            class="form-control" placeholder="Характеристика"
                                                            value="{{ $item->name }}">
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" name="specs[{{ $index }}][value]"
                                                            class="form-control" placeholder="Значение"
                                                            value="{{ $item->value }}">
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="btn btn-danger btn-remove-spec">&times;</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @php $lastIndex = $product->package->count(); @endphp
                                        @else
                                            @php $lastIndex = 0; @endphp
                                        @endif

                                        {{-- Пустая строка для новой записи --}}
                                        <div class="form-row mb-2 align-items-center">
                                            <div class="col">
                                                <input type="text" name="specs[{{ $lastIndex }}][name]"
                                                    class="form-control" placeholder="Характеристика">
                                            </div>
                                            <div class="col">
                                                <input type="text" name="specs[{{ $lastIndex }}][value]"
                                                    class="form-control" placeholder="Значение">
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="btn btn-danger btn-remove-spec">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Комплектация --}}
                                <div class="card">
                                    <div class="card-header">Комплектация</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="package">Что входит в комплект</label>
                                            <textarea name="package" id="package" class="form-control summernote_class"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-footer">
                                <button class="btn btn-info" type="submit">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- SEO --}}
                    <div class="card col-md-6">
                        <div class="card-header">SEO-настройки</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" name="meta_title" id="meta_title" class="form-control"
                                    placeholder="Унікальний текст про товар з вживанням ключових слів (300–500 слів)">
                            </div>

                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                                    placeholder="пилосос, Samsung, пилосос з контейнером, без мішка, техніка для дому">
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control" rows="3"
                                    placeholder="Купити пилосос Samsung VC07M25E0WB/UK недорого ✔ Гарантія 12 міс ✔ Потужність 750 Вт ✔ HEPA-фільтр ✔ Доставка по Україні"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.product-image-thumb').on('click', function() {
                var $image_element = $(this).find('img')
                $('.product-image').prop('src', $image_element.attr('src'))
                $('.product-image-thumb.active').removeClass('active')
                $(this).addClass('active')
            })
        })
    </script>
    <script>
        function triggerImageUpload() {
            document.getElementById('imageUploadInput').click();
        }

        document.getElementById('imageUploadInput').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('addImageForm').submit();
            }
        });
    </script>
    <script>
        let specIndex = {{ $lastIndex + 1 }};

        document.getElementById('add-spec-row').addEventListener('click', () => {
            const container = document.getElementById('specifications-container');
            const row = document.createElement('div');
            row.className = 'form-row mb-2 align-items-center';
            row.innerHTML = `
            <div class="col">
                <input type="text" name="specs[${specIndex}][name]" class="form-control" placeholder="Характеристика">
            </div>
            <div class="col">
                <input type="text" name="specs[${specIndex}][value]" class="form-control" placeholder="Значение">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-remove-spec">&times;</button>
            </div>
        `;
            container.appendChild(row);
            specIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove-spec')) {
                e.target.closest('.form-row').remove();
            }
        });
    </script>
@endsection
