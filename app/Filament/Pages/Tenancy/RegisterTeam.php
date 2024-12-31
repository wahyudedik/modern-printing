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
                    ->placeholder('Enter vendor name')
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', str($state)->slug())),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->live(onBlur: true)
                    ->placeholder('Auto-generated slug')
                    ->disabled()
                    ->dehydrated()
                    ->afterStateUpdated(
                        fn($state, callable $set, $get) =>
                        $set('slug', str($get('name'))->slug())
                    ),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('example@company.com')
                    ->autocomplete('email'),
                TextInput::make('website')
                    ->url()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('https://example.com')
                    ->prefix('https://'),
                TextInput::make('address')
                    ->maxLength(255)
                    ->placeholder('Enter complete address')
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('09876543212')
                    ->mask('99999999999')
                    ->prefix('+'),
                FileUpload::make('logo')
                    ->disk('public')
                    ->required()
                    ->directory('vendor')
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->maxSize(1024),
            ]);
    }

    protected function handleRegistration(array $data): Vendor
    {
        $vendor = Vendor::create($data);

        $vendor->members()->attach(Auth::user());

        return $vendor;
    }
}
