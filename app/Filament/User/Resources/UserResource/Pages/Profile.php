<?php

namespace App\Filament\User\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class Profile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.user.pages.profile';
    protected static ?string $title = 'Profil Saya';
    protected static ?string $navigationGroup = 'Akun';
    protected static ?int $navigationSort = 1;

    public $name;
    public $email;
    public $password;

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            Forms\Components\TextInput::make('password')
                ->label('Password Baru')
                ->password()
                ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->autocomplete('new-password')
                ->hint('Kosongkan jika tidak ingin mengubah'),
        ];
    }

    public function save(): void
    {
        $user = auth()->user();

        $data = $this->form->getState();

        $user->update($data);

        Notification::make()
            ->title('Profil berhasil diperbarui.')
            ->success()
            ->send();
    }
}
