<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VendorActivity;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VendorActivityResource\Pages;
use App\Filament\Resources\VendorActivityResource\RelationManagers;

class VendorActivityResource extends Resource
{
    protected static ?string $model = VendorActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Monitoring';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('vendor')
                    ->relationship('vendor', 'name'),
                Tables\Filters\SelectFilter::make('action')
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVendorActivities::route('/'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('vendor.name'),
                TextEntry::make('user.name'),
                TextEntry::make('action'),
                TextEntry::make('description'),
                TextEntry::make('changes')
                ->formatStateUsing(fn($state) => json_encode($state, JSON_PRETTY_PRINT))
                    ->prose(),
                TextEntry::make('created_at')
                ->dateTime(),
            ]);
    }
}
