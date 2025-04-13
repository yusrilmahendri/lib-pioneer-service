<?php

namespace App\Filament\User\Resources;

use App\Models\Transaction;
use App\Models\Business;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Tables\Filters\SelectFilter;
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
                Card::make()
                    ->label('Form Transaksi') // Menambahkan label untuk card
                    ->schema([
                        Select::make('businesses_id')
                            ->label('Usaha')
                            ->relationship('business', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('type_transaction')
                            ->label('Tipe Transaksi')
                            ->options([
                                'income' => 'Pemasukan',
                                'outcome' => 'Pengeluaran',
                            ])
                            ->required()
                            ->columnSpanFull(),

                         TextInput::make('amount')
                            ->label('Jumlah transaksi')
                            ->numeric()
                            ->required()
                            ->prefix('Rp.')
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->columnSpanFull(),
                         

                        Textarea::make('descriptions')
                            ->label('Deskripsi')
                            ->autosize()
                            ->rows(5)
                            ->maxLength(1000)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible() // Optional: agar card bisa di-collapse
                    ->collapsed(false) // Optional: agar card tidak dalam keadaan collapse
            ])
            ->columns(2); // Optional: bikin layout 2 kolom
            
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
                    ->label('Tipe transaksi')
                    ->getStateUsing(function ($record) {
                            // Mengubah nilai 'income' dan 'expense' menjadi 'Pemasukan' dan 'Pengeluaran'
                            return $record->type_transaction === 'income' ? 'Pemasukan' : 
                                ($record->type_transaction === 'outcome' ? 'Pengeluaran' : $record->type_transaction);
                        }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('Rp.', true),
                Tables\Columns\TextColumn::make('descriptions')
                    ->label('Deskripsi')->wrap()
                    ->limit(null)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i'),
            ])
            ->filters([
                SelectFilter::make('type_transaction')
                    ->label('Tipe Transaksi')
                    ->options([
                        'income' => 'Pemasukan',
                        'outcome' => 'Pengeluaran',
                    ])
                    ->searchable(),
            ])
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
