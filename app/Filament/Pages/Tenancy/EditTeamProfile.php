<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Vendor profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Vendor')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->reactive()
                    ->disabled()
                    ->placeholder('Masukkan nama vendor')
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->disabled()
                    ->live(onBlur: true)
                    ->placeholder('Slug akan otomatis terisi')
                    ->afterStateUpdated(
                        fn($state, callable $set, $get) =>
                        $set('slug', str($get('name'))->slug())
                    ),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('contoh@email.com'),
                TextInput::make('website')
                    ->label('Website')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://www.website.com'),
                TextInput::make('address')
                    ->label('Alamat')
                    ->maxLength(255)
                    ->placeholder('Masukkan alamat lengkap'),
                TextInput::make('phone')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(255)
                    ->placeholder('Masukkan nomor telepon'),
                FileUpload::make('logo')
                    ->label('Logo Vendor')
                    ->disk('public')
                    ->directory('vendor')
                    ->image()
                    ->maxSize(2048),
            ]);
    }
}
