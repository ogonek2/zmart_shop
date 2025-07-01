@extends('admin.layouts.app')

@section('header')
    <div class="col-sm-6">
        <h1 class="m-0">Создать продукт</h1>
    </div><!-- /.col -->
    {{-- <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
        </ol>
    </div><!-- /.col --> --}}
@endsection

@section('content')
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

    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link" href="#upload_file" data-toggle="tab">Загрузить
                                        файл</a></li>
                                <li class="nav-item"><a class="nav-link active" href="#create" data-toggle="tab">Создать в
                                        ручную</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane" id="upload_file">
                                    <form id="uploadForm" class="form-horizontal" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputFile">Загрузите <code style="color: green">Excel</code>
                                                файл</label>
                                            <input type="file" class="form-control" name="excel_file" id="inputFile"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-info">
                                            Загрузить <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                        </button>
                                    </form>

                                    <div class="progress mt-3" style="height: 25px; display:none;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;" id="uploadProgress">
                                            0%</div>
                                    </div>

                                    <div id="uploadStatus" class="mt-2"></div>
                                </div>

                                <div class="active tab-pane" id="create">
                                    <form id="createProductForm" class="form-horizontal" method="POST"
                                        action="{{ route('products.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Название</label>
                                                <div>
                                                    <input type="text" class="form-control"
                                                        placeholder="Микроволновка SAMSUNG..." name="title">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Артикуль</label>
                                                <div>
                                                    <input type="text" class="form-control" placeholder="A-23424..."
                                                        name="articule">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Цена</label>
                                                <div>
                                                    <input type="text" class="form-control only-float" placeholder="1010"
                                                        name="price">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Скидка</label>
                                                <div>
                                                    <input type="text" class="form-control only-int" placeholder="25%"
                                                        name="discount">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Категория</label>
                                                <div>
                                                    <select name="category" class="form-control">
                                                        @foreach ($categories as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Описание</label>
                                                <div>
                                                    <textarea name="description" id="summernote" placeholder="Простой в использовании, круто стоит"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="card card-default">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Загрузить изображения</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="actions" class="row">
                                                            <div class="col-lg-6">
                                                                <div class="btn-group w-100">
                                                                    <span class="btn btn-success col fileinput-button">
                                                                        <i class="fas fa-plus"></i>
                                                                        <span>Add files</span>
                                                                    </span>
                                                                    <button type="reset"
                                                                        class="btn btn-warning col cancel">
                                                                        <i class="fas fa-times-circle"></i>
                                                                        <span>Cancel upload</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 d-flex align-items-center">
                                                                <div class="fileupload-process w-100">
                                                                    <div id="total-progress"
                                                                        class="progress progress-striped active"
                                                                        role="progressbar" aria-valuemin="0"
                                                                        aria-valuemax="100" aria-valuenow="0">
                                                                        <div class="progress-bar progress-bar-success"
                                                                            style="width:0%;" data-dz-uploadprogress></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table table-striped files" id="previews">
                                                            <div id="template" class="row mt-2">
                                                                <div class="col-auto">
                                                                    <span class="preview"><img src="data:,"
                                                                            alt="" data-dz-thumbnail /></span>
                                                                </div>
                                                                <div class="col d-flex align-items-center">
                                                                    <p class="mb-0">
                                                                        <span class="lead" data-dz-name></span>
                                                                        (<span data-dz-size></span>)
                                                                    </p>
                                                                    <strong class="error text-danger"
                                                                        data-dz-errormessage></strong>
                                                                </div>
                                                                <div class="col-4 d-flex align-items-center">
                                                                    <div class="progress progress-striped active w-100"
                                                                        role="progressbar" aria-valuemin="0"
                                                                        aria-valuemax="100" aria-valuenow="0">
                                                                        <div class="progress-bar progress-bar-success"
                                                                            style="width:0%;" data-dz-uploadprogress></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto d-flex align-items-center">
                                                                    <div class="btn-group">
                                                                        <button data-dz-remove
                                                                            class="btn btn-warning cancel">
                                                                            <i class="fas fa-times-circle"></i>
                                                                            <span>Cancel</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                                <!-- /.card -->
                                            </div>
                                        </div> --}}
                                        <div class="form-group">
                                            <label>Добавить изображения</label>
                                            <input type="file" id="imageInput" class="form-control" multiple hidden>
                                            <div>
                                                <button type="button" class="btn btn-warning" id="addImageBtn">
                                                    + Добавить изображение
                                                </button>
                                            </div>
                                        </div>

                                        <div id="previewArea" class="row mt-3"></div>

                                        <div class="progress mt-3" style="display:none;">
                                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                                style="width: 0%">0%</div>
                                        </div>

                                        <div class="form-group">
                                            <div class="mt-2">
                                                <button type="submit" class="btn btn-success"><i
                                                        class="fa fa-plus-circle" aria-hidden="true"></i></button>
                                            </div>
                                        </div>

                                        <div id="uploadStatus2" class="mt-2"></div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
@endsection

@section('scripts')
    <script>
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            $('.progress').show();
            $('#uploadProgress').css('width', '0%').text('0%');
            $('#uploadStatus').text('');

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);
                            $('#uploadProgress').css('width', percent + '%').text(percent +
                                '%');
                        }
                    });
                    return xhr;
                },
                url: "{{ route('excel.upload') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#uploadStatus').text('Файл загружен и обработка началась.');
                    $('#uploadProgress').css('width', '100%').text('100%');
                },
                error: function(err) {
                    $('#uploadStatus').text('Ошибка при загрузке файла.');
                    $('.progress').hide();
                }
            });
        });
    </script>
    <script>
        $('#createProductForm').on('submit', function(e) {
            e.preventDefault();

            if (selectedFiles.length === 0) {
                alert('Выберите хотя бы одно изображение.');
                return;
            }

            const form = $('#createProductForm')[0];
            const formData = new FormData(form);

            // Добавляем картинки вручную
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            $('.progress').show();
            $('#uploadProgress2').css('width', '0%').text('0%');
            $('#uploadStatus2').text('');

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);
                            $('#uploadProgress2').css('width', percent + '%').text(percent +
                                '%');
                        }
                    });
                    return xhr;
                },
                url: "{{ route('products.store') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    $('#uploadStatus2').html('<span class="text-success">Успешно загружено!</span>');
                    $('#uploadProgress2').css('width', '100%').text('100%');

                    // ✅ Очистка формы
                    form.reset();

                    // ✅ Очистка Summernote
                    $('#summernote').summernote('reset');

                    // ✅ Очистка выбранных файлов
                    selectedFiles = [];
                    $('#imagePreviewList').empty(); // или другой контейнер с превью

                    // ✅ Скрыть прогресс
                    setTimeout(() => {
                        $('.progress').hide();
                        $('#uploadProgress2').css('width', '0%').text('0%');
                        $('#uploadStatus2').text('');
                    }, 1500);
                },
                error: function(err) {
                    console.log(err.responseJSON);
                    let errors = err.responseJSON?.errors;
                    if (errors) {
                        let msg = Object.entries(errors).map(([key, val]) =>
                            `${key}: ${val.join(', ')}`).join('<br>');
                        $('#uploadStatus2').html('<div class="text-danger">' + msg + '</div>');
                    } else {
                        $('#uploadStatus2').html('<div class="text-danger">Ошибка при загрузке.</div>');
                    }
                    $('.progress').hide();
                }
            });
        });
    </script>

    <script>
        $('.only-int').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('.only-float').on('input', function() {
            let val = this.value;
            val = val.replace(/[^0-9.]/g, '');
            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
            this.value = val;
        });
    </script>
    <script>
        let selectedFiles = [];

        function formatSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) {
                bytes /= 1024;
                i++;
            }
            return `${bytes.toFixed(1)} ${units[i]}`;
        }

        function updatePreview() {
            const preview = $('#previewArea');
            preview.html('');
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const html = `
                    <div class="col-md-3 img-thumb" data-index="${index}">
                        <button class="remove-btn" data-index="${index}">&times;</button>
                        <img src="${e.target.result}" alt="preview">
                        <div>${file.name}</div>
                        <small>${formatSize(file.size)}</small>
                    </div>
                `;
                    preview.append(html);
                };
                reader.readAsDataURL(file);
            });
        }

        $('#addImageBtn').on('click', function() {
            $('#imageInput').click();
        });

        $('#imageInput').on('change', function(e) {
            const files = Array.from(e.target.files);
            selectedFiles = selectedFiles.concat(files);
            updatePreview();
            $(this).val(null); // очистка input, чтобы можно было выбрать те же файлы снова
        });

        $(document).on('click', '.remove-btn', function() {
            const index = $(this).data('index');
            selectedFiles.splice(index, 1);
            updatePreview();
        });
    </script>
@endsection
