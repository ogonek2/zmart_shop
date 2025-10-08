<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Orders;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Orders::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    
    protected static ?string $navigationGroup = 'Заказы';
    
    protected static ?string $navigationLabel = 'Заказы';
    
    protected static ?string $pluralLabel = 'Заказы';
    
    // Переопределяем методы для работы с зашифрованными данными
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
    
    public static function getRecord($key)
    {
        $record = parent::getRecord($key);
        
        // Принудительно расшифровываем все зашифрованные поля
        if ($record) {
            $encryptable = [
                'delivery_service', 'city', 'warehouse', 'manual_address',
                'name', 'lastname', 'fathername', 'phone', 'comment',
                'cart', 'total_price', 'payment'
            ];
            
            foreach ($encryptable as $field) {
                if ($record->getRawOriginal($field)) {
                    try {
                        $record->setAttribute($field, $record->getRawOriginal($field));
                    } catch (\Exception $e) {
                        // Если не удалось расшифровать, оставляем как есть
                    }
                }
            }
        }
        
        return $record;
    }
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Имя')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('lastname')
                                    ->label('Фамилия')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('fathername')
                                    ->label('Отчество'),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Телефон')
                                    ->tel()
                                    ->required(),
                                
                                Forms\Components\TextInput::make('total_price')
                                    ->label('Сумма заказа')
                                    ->numeric()
                                    ->suffix('₴'),
                            ]),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('delivery_service')
                            ->label('Способ доставки')
                            ->options([
                                'novaposhta' => 'Новая Почта',
                                'ukrposhta' => 'Укрпошта',
                                'meest' => 'Meest Express',
                                'pickup' => 'Самовывоз',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('city')
                            ->label('Город'),
                        
                        Forms\Components\TextInput::make('warehouse')
                            ->label('Отделение'),
                        
                        Forms\Components\Textarea::make('manual_address')
                            ->label('Адрес доставки')
                            ->rows(2),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('payment')
                            ->label('Способ оплаты')
                            ->options([
                                'card' => 'Банковская карта',
                                'cash' => 'Наличные',
                                'nalogenniy' => 'Наложенный платеж',
                                'bank_transfer' => 'Банковский перевод',
                            ]),
                        
                        Forms\Components\Toggle::make('purchase_tracked')
                            ->label('Покупка отслежена'),
                        
                        Forms\Components\Textarea::make('comment')
                            ->label('Комментарий')
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('cart')
                            ->label('Корзина (JSON)')
                            ->rows(5)
                            ->helperText('JSON данные корзины')
                            ->formatStateUsing(function ($state) {
                                if (is_string($state)) {
                                    $decoded = json_decode($state, true);
                                    return $decoded ? json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $state;
                                }
                                return $state;
                            }),
                    ])
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('№')
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Клиент')
                    ->getStateUsing(function ($record) {
                        return $record->full_name; // Используем accessor из модели
                    })
                    ->searchable(['name', 'lastname'])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Сумма')
                    ->getStateUsing(function ($record) {
                        return $record->formatted_total_price; // Используем accessor из модели
                    })
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('delivery_service')
                    ->label('Доставка')
                    ->colors([
                        'primary' => 'novaposhta',
                        'success' => 'pickup',
                        'secondary' => 'ukrposhta',
                        'warning' => 'meest',
                    ])
                    ->enum([
                        'novaposhta' => 'Новая Почта',
                        'ukrposhta' => 'Укрпошта',
                        'meest' => 'Meest',
                        'pickup' => 'Самовывоз',
                    ])
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('payment')
                    ->label('Оплата')
                    ->colors([
                        'success' => 'card',
                        'warning' => 'cash',
                        'secondary' => 'nalogenniy',
                        'primary' => 'bank_transfer',
                    ])
                    ->enum([
                        'card' => 'Карта',
                        'cash' => 'Наличные',
                        'nalogenniy' => 'Наложенный',
                        'bank_transfer' => 'Перевод',
                    ])
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('purchase_tracked')
                    ->label('Отслежена')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('cart_items_count')
                    ->label('Товаров')
                    ->getStateUsing(function ($record) {
                        $cartItems = $record->cart_items;
                        return count($cartItems) . ' шт.';
                    })
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата заказа')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('delivery_service')
                    ->label('Способ доставки')
                    ->options([
                        'novaposhta' => 'Новая Почта',
                        'ukrposhta' => 'Укрпошта',
                        'meest' => 'Meest Express',
                        'pickup' => 'Самовывоз',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('payment')
                    ->label('Способ оплаты')
                    ->options([
                        'card' => 'Банковская карта',
                        'cash' => 'Наличные',
                        'nalogenniy' => 'Наложенный платеж',
                        'bank_transfer' => 'Банковский перевод',
                    ])
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('purchase_tracked')
                    ->label('Покупка отслежена')
                    ->placeholder('Все')
                    ->trueLabel('Только отслеженные')
                    ->falseLabel('Только неотслеженные'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('От'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('До'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_invoice')
                    ->label('📄 PDF')
                    ->icon('heroicon-o-document-download')
                    ->url(fn ($record) => route('invoice.download', $record->id))
                    ->openUrlInNewTab()
                    ->color('success'),
                Tables\Actions\Action::make('view_invoice')
                    ->label('👁️ Переглянути')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('invoice.view', $record->id))
                    ->openUrlInNewTab()
                    ->color('info'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
