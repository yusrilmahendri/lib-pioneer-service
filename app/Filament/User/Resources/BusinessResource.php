<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\BusinessResource\Pages;
use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions\ActionException;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Usaha')
                    ->description(fn($livewire) =>
                        $livewire->getRecord()
                            ? 'Silakan perbarui informasi usaha Anda.'
                            : 'Silakan daftar informasi usaha Anda.'
                    )
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->helperText('🔔 Tidak menemukan kategori usaha Anda? Klik tombol + untuk menambah kategori baru.')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nama Kategori')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->label('Daftar kategori berdasarkan usaha anda')
                                            ->modalHeading('Daftar Kategori Baru')
                                            ->modalButton('Simpan')
                                            ->action(function (array $data, Select $component): void {
                                                $exists = Category::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->exists();

                                                if ($exists) {
                                                    Notification::make()
                                                        ->title('Maaf, kategori usaha Anda sudah terdaftar.')
                                                        ->danger()
                                                        ->send();

                                                    return; // Kembalikan tanpa lempar exception, modal tetap terbuka
                                                }

                                                $category = Category::create([
                                                    'name' => $data['name'],
                                                ]);

                                                $component->state($category->getKey());

                                                Notification::make()
                                                    ->title('Kategori berhasil didaftarkan.')
                                                    ->success()
                                                    ->send();
                                            });
                                    }),
                                    
                                TextInput::make('name')
                                    ->label('Nama Usaha')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Pemilik')
                    ->description('Usaha ini dimiliki oleh')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Placeholder::make('Pemilik')
                            ->content(auth()->user()->name)
                            ->label('Nama pemilik')
                            ->disabled()
                            ->dehydrated(false)
                            ->default(auth()->id()),
                    ])
                    ->columns(1)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rowNumber')
                    ->label('No.')
                    ->state(
                        static function ($record, $livewire, $rowLoop) {
                            return $rowLoop->iteration;
                        }
                    ),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                   ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                   ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
             Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('user');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
