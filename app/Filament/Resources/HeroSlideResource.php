<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\UnableToCheckFileExistence;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photograph';

    protected static ?string $navigationGroup = 'Контент сайта';

    protected static ?string $navigationLabel = 'Хедер / Слайды';

    protected static ?string $modelLabel = 'Слайд';

    protected static ?string $pluralLabel = 'Слайды хедера';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Заголовок')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Основной текст на баннере'),

                        Forms\Components\Textarea::make('subtitle')
                            ->label('Подзаголовок')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('Необязательный текст под заголовком'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('button_text')
                                    ->label('Текст кнопки')
                                    ->maxLength(100)
                                    ->placeholder('Перейти до каталогу'),

                                Forms\Components\TextInput::make('button_url')
                                    ->label('Ссылка кнопки')
                                    ->maxLength(255)
                                    ->placeholder('/catalog')
                                    ->helperText('Можно указать относительный или полный URL'),
                            ]),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Порядок')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Меньше число — раньше в слайдшоу. Также можно менять порядок перетаскиванием в списке.'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Активен')
                                    ->default(true)
                                    ->inline(false),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('image_help')
                            ->label('Адаптивное изображение')
                            ->content('Одна картинка на все экраны. Рекомендуемый размер: 1920×720 (соотношение ~21:8). На мобильных она автоматически обрежется по сетке.'),

                        FileUpload::make('image_path')
                            ->label('Баннер')
                            ->image()
                            ->disk('public')
                            ->directory('hero-slides')
                            ->visibility('public')
                            ->imagePreviewHeight('220')
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('JPG/PNG/WebP до 4 МБ. Файл сохраняется на CDN.')
                            ->afterStateHydrated(static function (BaseFileUpload $component, string | array | null $state): void {
                                if (blank($state)) {
                                    $component->state([]);

                                    return;
                                }

                                $files = collect(Arr::wrap($state))
                                    ->filter(static function (string $file) use ($component): bool {
                                        if (blank($file)) {
                                            return false;
                                        }

                                        if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
                                            return true;
                                        }

                                        try {
                                            return $component->getDisk()->exists($file);
                                        } catch (UnableToCheckFileExistence $exception) {
                                            // Relative path may live only on CDN.
                                            return filled(env('BUNNY_CDN_URL'));
                                        }
                                    })
                                    ->mapWithKeys(static fn (string $file): array => [((string) Str::uuid()) => $file])
                                    ->all();

                                $component->state($files);
                            })
                            ->getUploadedFileUrlUsing(static function (BaseFileUpload $component, string $file): ?string {
                                return static::resolveImageUrl($file);
                            }),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Баннер')
                    ->height(56)
                    ->width(100),

                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Подзаголовок')
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('Кнопка')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлён')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активен')
                    ->placeholder('Все')
                    ->trueLabel('Только активные')
                    ->falseLabel('Только неактивные'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }

    public static function resolveImageUrl(?string $file): ?string
    {
        if (blank($file)) {
            return null;
        }

        if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
            return $file;
        }

        $relative = ltrim($file, '/');

        if (Storage::disk('public')->exists($relative)) {
            // Relative path works on any host (localhost / 127.0.0.1:8000).
            return '/storage/' . $relative;
        }

        $cdn = rtrim((string) (env('BUNNY_CDN_URL') ?: config('app.cdn_url')), '/');
        if ($cdn !== '') {
            return $cdn . '/' . $relative;
        }

        return '/storage/' . $relative;
    }

    public static function uploadImageToCdn(?string $imagePath): ?string
    {
        if (blank($imagePath)) {
            return $imagePath;
        }

        $cdnBase = rtrim((string) env('BUNNY_CDN_URL'), '/');
        if ($cdnBase !== '' && str_contains($imagePath, $cdnBase)) {
            return $imagePath;
        }

        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        $relative = ltrim($imagePath, '/');
        $localPath = storage_path('app/public/' . $relative);

        if (! is_file($localPath)) {
            return $imagePath;
        }

        try {
            $cdnUrl = uploadToBunnyCDN($localPath, $relative);
            @unlink($localPath);

            return $cdnUrl;
        } catch (\Throwable $e) {
            \Log::error('Hero slide CDN upload failed: ' . $e->getMessage(), [
                'imagePath' => $imagePath,
                'localPath' => $localPath,
            ]);

            return $imagePath;
        }
    }
}
