<?php

namespace App\Filament\User\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TransactionPeriodSummary extends BaseWidget
{
    protected static ?string $heading = 'Ringkasan Transaksi Harian / Mingguan / Bulanan';

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        return Transaction::query()
            ->select([
                'businesses.name as business_name',
                'type_transaction',

                // Subquery untuk total per hari, minggu, dan bulan
                DB::raw("SUM(CASE WHEN DATE(transactions.created_at) = '{$today->toDateString()}' THEN amount ELSE 0 END) as total_today"),
                DB::raw("SUM(CASE WHEN transactions.created_at BETWEEN '{$weekStart}' AND '{$weekEnd}' THEN amount ELSE 0 END) as total_week"),
                DB::raw("SUM(CASE WHEN transactions.created_at BETWEEN '{$monthStart}' AND '{$monthEnd}' THEN amount ELSE 0 END) as total_month"),
            ])
            ->join('businesses', 'transactions.businesses_id', '=', 'businesses.id')
            ->where('transactions.user_id', auth()->id())
            ->groupBy('businesses.name', 'type_transaction')
            ->orderBy('businesses.name');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('business_name')
                ->label('Usaha'),

            Tables\Columns\TextColumn::make('type_transaction')
                ->label('Tipe Transaksi')
                ->badge()
                ->color(fn (string $state) => $state === 'income' ? 'success' : 'danger')
                ->formatStateUsing(fn (string $state) => $state === 'income' ? 'Pemasukan' : 'Pengeluaran'),

            Tables\Columns\TextColumn::make('total_today')
                ->label('Hari Ini')
                ->money('IDR', true),

            Tables\Columns\TextColumn::make('total_week')
                ->label('Minggu Ini')
                ->money('IDR', true),

            Tables\Columns\TextColumn::make('total_month')
                ->label('Bulan Ini')
                ->money('IDR', true),
        ];
    }
}
