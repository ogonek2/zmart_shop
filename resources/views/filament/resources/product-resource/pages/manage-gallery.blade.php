<x-filament::page>
    <div class="space-y-6">
        <!-- Главное изображение -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Главное изображение товара</h2>
            
            @if($record->image_path)
                <div class="flex items-start gap-6">
                    <div class="flex-shrink-0">
                        <img src="{{ $record->getImagePath() }}" 
                             alt="Главное изображение" 
                             class="w-64 h-64 object-cover rounded-lg border-4 border-blue-500 shadow-lg">
                    </div>
                    <div class="flex-1">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>Текущее главное изображение
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-mono break-all">
                                {{ $record->image_path }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">
                            Это изображение отображается как основное на страницах каталога и карточке товара.
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <i class="fas fa-image text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">Главное изображение не установлено</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Выберите изображение из галереи или загрузите новое</p>
                </div>
            @endif
        </div>

        <!-- Галерея изображений -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Галерея изображений</h2>
            
            @if($record->images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($record->images as $image)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <!-- Изображение -->
                            <div class="relative">
                                <div class="aspect-square overflow-hidden">
                                    <img src="{{ $image->getImagePath() }}" 
                                         alt="Gallery image" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Метка главного изображения -->
                                @if($record->image_path === $image->src)
                                    <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-semibold shadow-lg">
                                        <i class="fas fa-star mr-1"></i>ГЛАВНОЕ
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Кнопки управления под изображением -->
                            <div class="p-3">
                                <div class="flex gap-2 mb-2">
                                    @if($record->image_path !== $image->src)
                                        <button 
                                            onclick="setAsMainImage({{ $image->id }})"
                                            style="flex: 1; background-color: #10b981; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                            onmouseover="this.style.backgroundColor='#059669'" 
                                            onmouseout="this.style.backgroundColor='#10b981'"
                                            title="Установить как главное">
                                            <i class="fas fa-star" style="margin-right: 4px;"></i>Главное
                                        </button>
                                    @else
                                        <button 
                                            disabled
                                            style="flex: 1; background-color: #6b7280; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; cursor: not-allowed; opacity: 0.7;"
                                            title="Это изображение уже является главным">
                                            <i class="fas fa-check" style="margin-right: 4px;"></i>Главное
                                        </button>
                                    @endif
                                    
                                    <button 
                                        onclick="deleteImage({{ $image->id }})"
                                        style="flex: 1; background-color: #ef4444; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='#dc2626'" 
                                        onmouseout="this.style.backgroundColor='#ef4444'"
                                        title="Удалить изображение">
                                        <i class="fas fa-trash" style="margin-right: 4px;"></i>Удалить
                                    </button>
                                </div>
                                
                                <!-- Вторая строка кнопок -->
                                <div class="flex gap-2 mb-2">
                                    <button 
                                        onclick="replaceImage({{ $image->id }})"
                                        style="flex: 1; background-color: #3b82f6; color: white; padding: 8px 12px; border-radius: 6px; border: none; font-size: 12px; font-weight: 500; cursor: pointer; transition: background-color 0.2s;"
                                        onmouseover="this.style.backgroundColor='#2563eb'" 
                                        onmouseout="this.style.backgroundColor='#3b82f6'"
                                        title="Заменить изображение">
                                        <i class="fas fa-upload" style="margin-right: 4px;"></i>Заменить
                                    </button>
                                </div>
                                
                                <!-- Путь к файлу -->
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate bg-gray-50 dark:bg-gray-700 p-2 rounded" title="{{ $image->src }}">
                                    <strong>Файл:</strong> {{ basename($image->src) }}<br>
                                    <strong>ID:</strong> {{ $image->id }} (тип: {{ gettype($image->id) }})<br>
                                    <strong>Product ID:</strong> {{ $image->product_id }}<br>
                                    <strong>Record ID:</strong> {{ $record->id }}<br>
                                    <strong>Главное:</strong> {{ $record->image_path === $image->src ? 'ДА' : 'НЕТ' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">Управление изображениями</p>
                            <p class="text-sm text-blue-800 dark:text-blue-200 mt-1">
                                Используйте кнопки под каждым изображением для управления галереей. 
                                Вы можете установить любое изображение как главное или удалить его. 
                                Главное изображение отмечено зеленой меткой "ГЛАВНОЕ".
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <i class="fas fa-images text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Галерея пуста</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Нажмите кнопку "Загрузить изображения" выше, чтобы добавить фотографии</p>
                </div>
            @endif
        </div>

        <!-- Информационный блок -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mt-0.5 mr-3"></i>
                <div class="flex-1">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Информация о работе с изображениями</h3>
                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                        <li><i class="fas fa-check-circle mr-2 text-green-500"></i>Изображения загружаются на CDN для быстрой загрузки</li>
                        <li><i class="fas fa-check-circle mr-2 text-green-500"></i>Рекомендуемое разрешение: минимум 800x800 пикселей</li>
                        <li><i class="fas fa-check-circle mr-2 text-green-500"></i>Поддерживаемые форматы: JPG, PNG, WebP</li>
                        <li><i class="fas fa-check-circle mr-2 text-green-500"></i>За один раз можно загрузить до 10 изображений</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для замены изображения -->
    <div id="replaceImageModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div style="background-color: white; margin: 15% auto; padding: 20px; border-radius: 8px; width: 400px; max-width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: bold;">Заменить изображение</h3>
                <button onclick="closeReplaceModal()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            </div>
            
            <form id="replaceImageForm" enctype="multipart/form-data">
                <input type="hidden" id="replaceImageId" name="image_id">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Выберите новое изображение:</label>
                    <input type="file" id="newImageFile" name="new_image" accept="image/*" required 
                           style="width: 100%; padding: 8px; border: 2px dashed #ccc; border-radius: 4px;">
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeReplaceModal()" 
                            style="padding: 8px 16px; border: 1px solid #ccc; background: white; border-radius: 4px; cursor: pointer;">
                        Отмена
                    </button>
                    <button type="submit" 
                            style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Заменить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentReplaceImageId = null;
        
        function replaceImage(imageId) {
            console.log('replaceImage вызван с ID:', imageId, 'тип:', typeof imageId);
            currentReplaceImageId = imageId;
            document.getElementById('replaceImageId').value = imageId;
            document.getElementById('replaceImageModal').style.display = 'block';
        }
        
        function setAsMainImage(imageId) {
            console.log('setAsMainImage вызван с ID:', imageId);
            
            if (!imageId) {
                alert('Ошибка: ID изображения не найден');
                return;
            }
            
            const formData = new FormData();
            formData.append('image_id', imageId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/filament-admin/products/{{ $record->id }}/set-main-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Server returned non-JSON response:', text);
                        throw new Error('Сервер вернул неверный формат ответа');
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data && data.success) {
                    alert('Главное изображение успешно установлено!');
                    location.reload();
                } else {
                    alert('Ошибка: ' + (data?.message || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при установке главного изображения: ' + error.message);
            });
        }
        
        function deleteImage(imageId) {
            console.log('deleteImage вызван с ID:', imageId);
            
            if (!imageId) {
                alert('Ошибка: ID изображения не найден');
                return;
            }
            
            if (!confirm('Вы уверены, что хотите удалить это изображение?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('image_id', imageId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/filament-admin/products/{{ $record->id }}/delete-gallery-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Изображение успешно удалено!');
                    location.reload();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении изображения');
            });
        }
        
        function closeReplaceModal() {
            document.getElementById('replaceImageModal').style.display = 'none';
            document.getElementById('newImageFile').value = '';
            currentReplaceImageId = null;
        }
        
        // Обработка формы замены изображения
        document.getElementById('replaceImageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('newImageFile');
            if (!fileInput.files[0]) {
                alert('Пожалуйста, выберите изображение');
                return;
            }
            
            const formData = new FormData();
            formData.append('image_id', currentReplaceImageId);
            formData.append('new_image', fileInput.files[0]);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Показываем индикатор загрузки
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Загрузка...';
            submitBtn.disabled = true;
            
            fetch('/filament-admin/products/{{ $record->id }}/replace-gallery-image', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeReplaceModal();
                    location.reload();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при загрузке изображения');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Закрытие модального окна при клике вне его
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('replaceImageModal');
            if (event.target === modal) {
                closeReplaceModal();
            }
        });
    </script>
</x-filament::page>

