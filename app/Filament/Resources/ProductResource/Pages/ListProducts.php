<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Imports\ProductsImport;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;
    
    public $previewData = [];
    public $importStats = [];

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            Actions\Action::make('tree')
                ->label('Древовидное управление')
                ->icon('heroicon-o-template')
                ->url(route('filament.resources.products.tree'))
                ->color('success'),
            
            Actions\Action::make('import')
                ->label('Импорт из Excel')
                ->color('success')
                ->icon('heroicon-o-upload')
                ->form([
                    FileUpload::make('file')
                        ->label('Файл Excel')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/excel',
                            'application/x-excel',
                            'application/x-msexcel',
                            'text/csv',
                            'text/plain',
                            'application/csv',
                            'text/comma-separated-values',
                            'application/vnd.ms-excel.sheet.macroEnabled.12',
                        ])
                        ->maxSize(10240) // 10MB
                        ->required()
                        ->disk('public')
                        ->helperText('Поддерживаемые форматы: XLS, XLSX, CSV. Максимальный размер: 10MB'),
                    
                    Toggle::make('preview_mode')
                        ->label('Режим предпросмотра')
                        ->helperText('Если включено, данные будут только проверены без импорта')
                        ->default(false),
                    
                    Toggle::make('skip_duplicates')
                        ->label('Пропускать дубликаты')
                        ->helperText('Пропускать товары с существующими артикулами')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $startTime = microtime(true);
                    $filePath = storage_path('app/public/' . $data['file']);
                    
                    try {
                        // Логирование начала импорта
                        Log::channel('daily')->info('Начало импорта товаров', [
                            'file' => $data['file'],
                            'preview_mode' => $data['preview_mode'] ?? false,
                            'skip_duplicates' => $data['skip_duplicates'] ?? false,
                            'user' => auth()->user()->name ?? 'Unknown',
                            'time' => now()->format('Y-m-d H:i:s'),
                        ]);
                        
                        // Предпросмотр данных
                        if ($data['preview_mode'] ?? false) {
                            $preview = $this->previewImportData($filePath);
                            
                            Log::channel('daily')->info('Предпросмотр импорта завершен', [
                                'rows_found' => count($preview['rows']),
                                'errors' => count($preview['errors']),
                            ]);
                            
                            // Формируем сообщение с предпросмотром
                            $message = "Найдено строк: " . count($preview['rows']) . "\n";
                            if (!empty($preview['errors'])) {
                                $message .= "Ошибок: " . count($preview['errors']) . "\n";
                                $message .= "Первые ошибки:\n" . implode("\n", array_slice($preview['errors'], 0, 5));
                            } else {
                                $message .= "Ошибок не обнаружено. Готово к импорту.";
                            }
                            
                            Notification::make()
                                ->title('Предпросмотр завершен')
                                ->body($message)
                                ->success()
                                ->duration(10000)
                                ->send();
                            
                            // Удаляем временный файл
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            
                            return;
                        }
                        
                        // Реальный импорт с прогрессом
                        $importer = new ProductsImport();
                        
                        // Показываем уведомление о начале
                        Notification::make()
                            ->title('Импорт запущен')
                            ->body('Обработка данных... Пожалуйста, подождите.')
                            ->success()
                            ->send();
                        
                        Excel::import($importer, $filePath);
                        
                        $duration = round(microtime(true) - $startTime, 2);
                        
                        // Логирование успешного импорта
                        Log::channel('daily')->info('Импорт товаров завершен успешно', [
                            'duration' => $duration . 's',
                            'file' => $data['file'],
                        ]);
                        
                        // Удаляем временный файл
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        
                        Notification::make()
                            ->title('Импорт успешно завершен')
                            ->body("Время выполнения: {$duration} сек.")
                            ->success()
                            ->duration(5000)
                            ->send();
                        
                        // Перезагружаем страницу для обновления списка
                        return redirect()->route('filament.resources.products.index');
                        
                    } catch (\Exception $e) {
                        $duration = round(microtime(true) - $startTime, 2);
                        
                        // Логирование ошибки
                        Log::channel('daily')->error('Ошибка импорта товаров', [
                            'error' => $e->getMessage(),
                            'file' => $data['file'] ?? 'unknown',
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString(),
                            'duration' => $duration . 's',
                        ]);
                        
                        // Удаляем временный файл
                        if (isset($filePath) && file_exists($filePath)) {
                            unlink($filePath);
                        }
                        
                        Notification::make()
                            ->title('Ошибка импорта')
                            ->body("Ошибка: {$e->getMessage()}\nПроверьте логи для подробностей.")
                            ->danger()
                            ->duration(10000)
                            ->send();
                    }
                }),
            
            Actions\Action::make('export_template')
                ->label('Скачать шаблон Excel')
                ->color('secondary')
                ->icon('heroicon-o-download')
                ->action(function () {
                    // Создаем пример CSV файла
                    $template = "title,articule,price,description,category\n";
                    $template .= "Пример товара,ART-001,1000,Описание товара,Категория 1\n";
                    $template .= "Еще один товар,ART-002,2000,Другое описание,Категория 2\n";
                    
                    $filename = 'products_import_template_' . date('Y-m-d') . '.csv';
                    $path = storage_path('app/public/' . $filename);
                    
                    file_put_contents($path, "\xEF\xBB\xBF" . $template); // UTF-8 BOM для Excel
                    
                    Log::channel('daily')->info('Скачан шаблон импорта', [
                        'user' => auth()->user()->name ?? 'Unknown',
                        'filename' => $filename,
                    ]);
                    
                    Notification::make()
                        ->title('Шаблон создан')
                        ->body('Файл сохранен в storage/app/public/' . $filename)
                        ->success()
                        ->send();
                    
                    return response()->download($path)->deleteFileAfterSend(true);
                }),
            
            Actions\Action::make('view_import_logs')
                ->label('Логи импорта')
                ->color('warning')
                ->icon('heroicon-o-document-text')
                ->modalContent(function () {
                    $logs = $this->getImportLogs();
                    return view('filament.components.import-logs', ['logs' => $logs]);
                })
                ->modalButton('Закрыть')
                ->action(function () {
                    // Просто закрываем модальное окно
                }),
        ];
    }
    
    /**
     * Предпросмотр данных из Excel файла
     */
    protected function previewImportData($filePath)
    {
        $rows = [];
        $errors = [];
        
        try {
            $data = Excel::toArray(new ProductsImport, $filePath);
            
            if (empty($data) || empty($data[0])) {
                $errors[] = "Файл пустой или имеет неверный формат";
                return ['rows' => $rows, 'errors' => $errors];
            }
            
            $sheet = $data[0];
            $headers = array_shift($sheet); // Первая строка - заголовки
            
            foreach ($sheet as $index => $row) {
                $rowNum = $index + 2; // +2 потому что индекс с 0 и первая строка - заголовки
                
                // Проверка обязательных полей
                if (empty($row[0])) { // title
                    $errors[] = "Строка {$rowNum}: отсутствует название товара";
                    continue;
                }
                
                $rows[] = [
                    'row_number' => $rowNum,
                    'title' => $row[0] ?? '',
                    'articule' => $row[1] ?? '',
                    'price' => $row[2] ?? 0,
                    'description' => $row[3] ?? '',
                    'category' => $row[4] ?? '',
                ];
            }
            
        } catch (\Exception $e) {
            $errors[] = "Ошибка чтения файла: " . $e->getMessage();
        }
        
        return ['rows' => $rows, 'errors' => $errors];
    }
    
    /**
     * Получить последние логи импорта
     */
    protected function getImportLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = explode("\n", $content);
            
            // Берем последние 100 строк, связанных с импортом
            $importLines = array_filter($lines, function($line) {
                return strpos($line, 'импорт') !== false || strpos($line, 'import') !== false;
            });
            
            $logs = array_slice(array_reverse($importLines), 0, 50);
        }
        
        return $logs;
    }
}