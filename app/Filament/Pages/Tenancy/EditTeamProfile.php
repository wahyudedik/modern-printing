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
                \Filament\Forms\Components\Section::make('Informasi Vendor')
                    ->description('Informasi dasar vendor')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Vendor')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->reactive()
                                    ->disabled()
                                    ->placeholder('Masukkan nama vendor')
                                    ->suffixIcon('heroicon-m-building-storefront')
                                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->disabled()
                                    ->live(onBlur: true)
                                    ->placeholder('Slug akan otomatis terisi')
                                    ->suffixIcon('heroicon-m-link')
                                    ->afterStateUpdated(
                                        fn($state, callable $set, $get) =>
                                        $set('slug', str($get('name'))->slug())
                                    ),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('contoh@email.com')
                                    ->suffixIcon('heroicon-m-envelope'),
                                TextInput::make('website')
                                    ->label('Website')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://www.website.com')
                                    ->suffixIcon('heroicon-m-globe-alt'),
                            ]),
                    ]),
                \Filament\Forms\Components\Section::make('Kontak & Media')
                    ->description('Informasi kontak dan media vendor')
                    ->schema([
                        \Filament\Forms\Components\Grid::make(2)
                            ->schema([
                                TextInput::make('address')
                                    ->label('Alamat')
                                    ->maxLength(255)
                                    ->placeholder('Masukkan alamat lengkap')
                                    ->suffixIcon('heroicon-m-map-pin'),
                                TextInput::make('phone')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('Masukkan nomor telepon')
                                    ->suffixIcon('heroicon-m-phone'),
                            ]),
                        FileUpload::make('logo')
                            ->label('Logo Vendor')
                            ->disk('public')
                            ->directory('vendor')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->circleCropper()
                            ->downloadable()
                            ->openable()
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadProgressIndicatorPosition('left'),
                    ]),
            ]);
    }
}
