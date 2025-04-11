<?php

namespace App\Filament\User\Resources\BusinessResource\Pages;

use App\Filament\User\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord(int|string $key): \App\Models\Business
    {
        return static::getModel()::with(['user', 'category'])->findOrFail($key);
    }

    protected function afterSave(): void
    {
        $this->redirect(static::getResource()::getUrl('index'));
    }

}
