<?php

namespace App\Filament\User\Resources;

use App\Models\Transaction;
use App\Models\Business;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\User\Resources\TransactionResource\Pages;
use App\Filament\User\Resources\TransactionResource\Widgets\TransactionPeriodSummary;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $pluralLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('businesses_id')
                    ->label('Usaha')
                    ->relationship('busines', 'name')
                    ->searchable()
                    ->required(),

                Select::make('type_transaction')
                    ->label('Tipe Transaksi')
                    ->options([
                        'income' => 'Pemasukan',
                        'outcome' => 'Pengeluaran',
                    ])
                    ->required(),

                TextInput::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),

                Textarea::make('descriptions')
                    ->label('Deskripsi')
                    ->required(),
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

                Tables\Columns\TextColumn::make('business.name')
                    ->label('Usaha'),

                Tables\Columns\TextColumn::make('type_transaction')
                    ->label('Tipe'),

                Tables\Columns\TextColumn::make('amount')
                    ->money('Rp.', true),

                Tables\Columns\TextColumn::make('descriptions')
                    ->label('Deskripsi')->wrap() // supaya bisa lebih dari 1 baris
                    ->limit(null)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            TransactionPeriodSummary::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('user');
    }
}
