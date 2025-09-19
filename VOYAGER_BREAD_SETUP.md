# 🚀 **Настройка BREAD в Voyager для связей many-to-many**

## 📋 **Пошаговая инструкция:**

### 1. **Запустите миграции:**
```bash
php artisan migrate
```

### 2. **Войдите в Voyager Admin Panel:**
- Перейдите по адресу: `/admin`
- Войдите с учетными данными администратора

### 3. **Настройте BREAD для таблицы `products`:**

#### **Основные поля:**
- `id` - Primary Key, Hidden
- `name` - Text, Required
- `articule` - Text, Optional
- `description` - Text Area, Optional
- `url` - Text, Optional
- `discount` - Number, Optional
- `price` - Number, Required
- `image_path` - Image, Optional
- `brand` - Text, Optional
- `availability` - Select Dropdown, Optional
  - Options: `1` => "В наличии", `2` => "Нет в наличии"
- `condition_item` - Select Dropdown, Optional
  - Options: `1` => "Новый", `2` => "Б/У"
- `complectation` - Text Area, Optional
- `seo_title` - Text, Optional
- `seo_keywords` - Text, Optional
- `seo_description` - Text Area, Optional
- `created_at` - Timestamp, Hidden
- `updated_at` - Timestamp, Hidden

#### **Поле для связи many-to-many с категориями:**
- `categories_list` - Multiple Select, Optional
  - Model: `App\Models\Category`
  - Relationship: `categories`

### 4. **Настройте BREAD для таблицы `categories`:**

#### **Основные поля:**
- `id` - Primary Key, Hidden
- `name` - Text, Required
- `url` - Text, Optional
- `parent_id` - Select Dropdown, Optional
  - Model: `App\Models\Category`
  - Relationship: `parent`
- `order` - Number, Optional
- `description` - Text Area, Optional
- `image` - Image, Optional
- `created_at` - Timestamp, Hidden
- `updated_at` - Timestamp, Hidden

#### **Поле для связи many-to-many с товарами:**
- `products_list` - Multiple Select, Optional
  - Model: `App\Models\Product`
  - Relationship: `products`

### 5. **Настройка связей many-to-many:**

#### **В таблице `products`:**
1. Добавьте поле `categories_list`
2. Тип: `Multiple Select`
3. Model: `App\Models\Category`
4. Relationship: `categories`
5. Column: `id`
6. Display Column: `name`

#### **В таблице `categories`:**
1. Добавьте поле `products_list`
2. Тип: `Multiple Select`
3. Model: `App\Models\Product`
4. Relationship: `products`
5. Column: `id`
6. Display Column: `name`

### 6. **Проверьте настройки:**
- Сохраните BREAD настройки
- Перейдите в раздел Products
- Создайте новый товар
- В поле "Categories" должны отображаться все доступные категории
- Выберите нужные категории
- Сохраните товар

### 7. **Проверьте связи:**
- Перейдите в раздел Categories
- Откройте любую категорию
- В поле "Products" должны отображаться все связанные товары

## 🔧 **Возможные проблемы и решения:**

### **Проблема: Поле categories_list не отображается**
**Решение:** Убедитесь, что в модели Product есть аксессоры `getCategoriesListAttribute` и `setCategoriesListAttribute`

### **Проблема: Связи не сохраняются**
**Решение:** Проверьте, что таблица `category_product` существует и имеет правильную структуру

### **Проблема: Ошибка "Class not found"**
**Решение:** Убедитесь, что модели находятся в правильном namespace и правильно импортированы

## 📚 **Дополнительные настройки:**

### **Валидация:**
- Добавьте правила валидации для обязательных полей
- Настройте уникальность для URL товаров и категорий

### **Поиск:**
- Настройте поля для поиска (name, description, brand)
- Добавьте фильтры по категориям, цене, наличию

### **Сортировка:**
- Настройте сортировку по цене, названию, дате создания
- Добавьте возможность изменения порядка категорий

## ✅ **Результат:**
После правильной настройки BREAD у вас будет:
- Полнофункциональная админ-панель для управления товарами и категориями
- Работающие связи many-to-many между товарами и категориями
- Удобный интерфейс для выбора категорий при создании/редактировании товаров
- Возможность управления связями с обеих сторон (товар ↔ категория)
