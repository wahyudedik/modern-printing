<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationGroup = 'Vendors';

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter vendor name')
                    ->label('Vendor Name')
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter email address')
                    ->label('Email Address')
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://example.com')
                    ->label('Website URL')
                    ->columnSpan('full'),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter complete address')
                    ->label('Complete Address')
                    ->columnSpan('full')
                    ->rows(3),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter phone number')
                    ->label('Phone Number')
                    ->columnSpan('full'),
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->directory('vendor-logos')
                    ->label('Company Logo')
                    ->imagePreviewHeight('150')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->columnSpan('full'),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required()
                    ->label('Vendor Status')
                    ->native(false)
                    ->searchable()
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('website')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->url(fn($record) => $record->website, true)
                    ->icon('heroicon-o-globe-alt'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->icon('heroicon-o-map-pin'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                Tables\Columns\ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->size(40),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVendors::route('/'),
            'view' => Pages\ViewVendor::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProdukRelationManager::class,
            RelationManagers\TransaksiRelationManager::class,
        ];
    }

}
