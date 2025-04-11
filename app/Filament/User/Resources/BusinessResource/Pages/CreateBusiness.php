<?php

namespace App\Filament\User\Resources\BusinessResource\Pages;

use App\Filament\User\Resources\BusinessResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Business; // ✅ Tambahkan ini


class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = Business::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->exists();

        if ($exists) {
            Notification::make()
                ->title('Maaf, nama usaha sudah terdaftar.')
                ->danger()
                ->send();

            // Lempar exception untuk hentikan proses
               $this->halt();
        }

        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
