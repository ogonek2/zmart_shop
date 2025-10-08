# Vue.js Управление категориями - Руководство

## 📋 Обзор

Создана система управления категориями на Vue.js с API для Filament админ-панели.

## 🗂️ Созданные файлы

### Vue Компоненты

1. **`resources/js/components/CategoryTreeManager.vue`**
   - Главный компонент управления категориями
   - Двухпанельный интерфейс (дерево + детали)
   - Функции: создание, редактирование, удаление категорий
   - Управление товарами в категориях

2. **`resources/js/components/CategoryTreeNode.vue`**
   - Компонент для отображения узла дерева категорий
   - Поддержка вложенности
   - Иконки действий (создать, редактировать, удалить)

### Backend

3. **`app/Http/Controllers/Api/CategoryController.php`**
   - API контроллер для работы с категориями
   - Методы:
     - `tree()` - получить дерево категорий
     - `show($id)` - получить детали категории
     - `store()` - создать категорию
     - `update($id)` - обновить категорию
     - `destroy($id)` - удалить категорию
     - `detachProduct()` - отвязать товар от категории
     - `move()` - переместить категорию

### Filament

4. **`app/Filament/Resources/ProductResource/Pages/VueTreeManager.php`**
   - Страница Filament для Vue компонента

5. **`resources/views/filament/resources/product-resource/pages/vue-tree-manager.blade.php`**
   - Blade шаблон страницы

### Routing

6. **`routes/api.php`**
   - Добавлены API маршруты:
     - `GET /api/categories/tree` - дерево категорий
     - `GET /api/categories/{id}` - детали категории
     - `POST /api/categories` - создать категорию
     - `PUT /api/categories/{id}` - обновить категорию
     - `DELETE /api/categories/{id}` - удалить категорию
     - `DELETE /api/categories/{categoryId}/products/{productId}` - отвязать товар

## 🚀 Использование

### Доступ к странице

1. Войдите в Filament админ-панель: `/filament-admin`
2. Перейдите в раздел "Товары"
3. Нажмите кнопку "Древо категорий (Vue)"
4. Откроется `/filament-admin/products/vue-tree`

### Функционал

#### Левая панель - Дерево категорий
- **Клик на категорию** - выбрать и показать детали справа
- **Стрелка** - развернуть/свернуть подкатегории
- **Иконка `+`** - создать подкатегорию
- **Иконка карандаша** - редактировать название
- **Иконка корзины** - удалить категорию

#### Правая панель - Детали
- **Заголовок** - информация о выбранной категории
- **Кнопки:**
  - `+ Подкатегория` - создать дочернюю категорию
  - `✏️ Редактировать` - изменить название
  - `🗑️ Удалить` - удалить категорию
- **Подкатегории** - список дочерних категорий (клик для перехода)
- **Товары** - список товаров в категории
  - Кнопка корзины - отвязать товар от категории

## 🔧 API Endpoints

### Получить дерево категорий
```
GET /api/categories/tree
```
Ответ:
```json
[
  {
    "id": 1,
    "name": "Категория 1",
    "products_count": 5,
    "children": [...]
  }
]
```

### Получить детали категории
```
GET /api/categories/{id}
```
Ответ:
```json
{
  "category": {...},
  "subcategories": [...],
  "products": [...]
}
```

### Создать категорию
```
POST /api/categories
Content-Type: application/json

{
  "name": "Новая категория",
  "parent_id": 1,  // опционально
  "is_active": true,
  "sort_order": 0
}
```

### Обновить категорию
```
PUT /api/categories/{id}
Content-Type: application/json

{
  "name": "Обновленное название"
}
```

### Удалить категорию
```
DELETE /api/categories/{id}
```
*Примечание: Дочерние категории будут перемещены на уровень выше*

### Отвязать товар от категории
```
DELETE /api/categories/{categoryId}/products/{productId}
```

## 🛠️ Компиляция

Для пересборки JavaScript:

```bash
npm run dev       # Для разработки
npm run prod      # Для production
```

## 📝 Примечания

1. **CSRF Token** - все POST/PUT/DELETE запросы требуют CSRF токен из meta тега:
   ```html
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

2. **Vue.js приложение** - создается отдельный экземпляр Vue для админ-панели:
   ```javascript
   const categoryApp = createApp({});
   categoryApp.component('category-tree-manager', CategoryTreeManager);
   categoryApp.mount('#category-tree-app');
   ```

3. **Уведомления** - встроенный Toast компонент для отображения результатов операций

## 🐛 Отладка

Проверьте консоль браузера на наличие ошибок:
- `Vue Category Tree Manager успешно инициализирован` - компонент загружен
- Ошибки fetch - проверьте API маршруты
- Ошибки компиляции - запустите `npm run dev`

## 📦 Зависимости

- Vue.js 3.x (уже установлен в проекте)
- Laravel 8.x
- Filament v2
- API категорий работает без дополнительных зависимостей

## ✅ Преимущества Vue подхода

1. ✅ **Надежность** - использует уже установленный Vue.js
2. ✅ **Реактивность** - автоматическое обновление интерфейса
3. ✅ **API-driven** - чистое разделение frontend/backend
4. ✅ **Отладка** - легко отслеживать в консоли
5. ✅ **Расширяемость** - легко добавлять новые функции
