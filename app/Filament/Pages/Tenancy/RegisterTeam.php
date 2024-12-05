<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use App\Models\Vendor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Vendor';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn($state, callable $set, $get) =>
                        $set('slug', str($get('name'))->slug())
                    ),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('website')
                    ->url()
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->disk('public')
                    ->required()
                    ->directory('vendor'),
            ]);
    }

    protected function handleRegistration(array $data): Vendor
    {
        $vendor = Vendor::create($data);

        $vendor->members()->attach(Auth::user());

        return $vendor;
    }
}
