<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Переход на Voyager - ZMART Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .btn-voyager {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-voyager:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
        .icon-large {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card text-center p-5">
                    <div class="icon-large">
                        <i class="fas fa-rocket"></i>
                    </div>
                    
                    <h2 class="mb-4">Добро пожаловать в ZMART Admin!</h2>
                    
                    <p class="text-muted mb-4">
                        Мы успешно установили Voyager - современную админ-панель для вашего интернет-магазина.
                        Теперь у вас есть мощные инструменты для управления контентом.
                    </p>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-box fa-2x text-primary mb-2"></i>
                                <h6>Товары</h6>
                                <small class="text-muted">Управление каталогом</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-tags fa-2x text-success mb-2"></i>
                                <h6>Категории</h6>
                                <small class="text-muted">Структура магазина</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                                <h6>Заказы</h6>
                                <small class="text-muted">Обработка заказов</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-3">
                        <a href="/admin" class="btn btn-voyager">
                            <i class="fas fa-arrow-right me-2"></i>
                            Перейти в Voyager
                        </a>
                        
                        <a href="/admin/products" class="btn btn-outline-primary">
                            <i class="fas fa-box me-2"></i>
                            Старая админка (временно)
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Voyager автоматически создаст все необходимые таблицы и настройки
                        </small>
                    </div>
                </div>
                
                <!-- Статистика -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h4 class="text-primary">{{ \App\Models\Product::count() }}</h4>
                            <small class="text-muted">Товаров</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h4 class="text-success">{{ \App\Models\Category::count() }}</h4>
                            <small class="text-muted">Категорий</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3">
                            <h4 class="text-warning">{{ \App\Models\Order::count() ?? 0 }}</h4>
                            <small class="text-muted">Заказов</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
