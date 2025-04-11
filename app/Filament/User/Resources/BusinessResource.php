<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\BusinessResource\Pages;
use App\Filament\User\Resources\BusinessResource\RelationManagers;
use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use App\Models\User;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Usaha')
                    ->description('Silakan perbarui informasi usaha Anda.')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('name')
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
                    ->date('d/m/Y') // format dd/mm/yyyy
                    ->sortable(), // jangan toggleable

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->date('d/m/Y') // format dd/mm/yyyy
                    ->sortable(), // jangan toggleable
            ])
            ->filters([
                // contoh filter by kategori (optional)
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('user'); // atau pengecekan sesuai guard kamu
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
