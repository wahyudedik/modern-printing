<?php

namespace App\Filament\App\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\App\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Pengguna';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static bool $isScopedToTenant = true;

    protected static ?string $tenantOwnershipRelationshipName = 'vendor';

    protected static ?string $tenantRelationshipName = 'members';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile Information')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image')
                            ->image()
                            ->directory('profile-photos')
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('email_verified_at')
                                    ->type('datetime-local')
                                    ->nullable(),
                            ]),
                    ]),
                Forms\Components\Section::make('Authentication')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(string $context): bool => $context === 'create'),
                                Forms\Components\Select::make('usertype')
                                    ->options([
                                        'staff' => 'Staff',
                                    ])
                                    ->default('staff')
                                    ->required(),
                                // Using Select Component
                                Forms\Components\Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable(),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image')
                    ->circular()
                    ->label('Profile Picture')
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->email)
                    ->copyable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->icon('heroicon-m-check-circle')
                    ->iconColor(fn($record) => $record->email_verified_at ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('usertype')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning',
                        'user' => 'success',
                    })
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable()
                    ->description('User Role Type'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->description('Account Created')
                    ->icon('heroicon-m-calendar'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->description('Last Modified')
                    ->icon('heroicon-m-clock')
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('usertype')
                    ->options([
                        'admin' => 'Admin',
                        'staff' => 'Staff',
                        'user' => 'User',
                    ])
                    ->label('User Role')
                    ->indicator('Role'),
                Tables\Filters\Filter::make('email_verified')
                    ->label('Email Verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->toggle()
                    ->indicator('Verified'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->form([
                            Forms\Components\FileUpload::make('profile_image')
                                ->image()
                                ->disk('public')
                                ->directory('profile-photos')
                                ->imageEditor()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DateTimePicker::make('email_verified_at')
                                ->label('Email Verification Date'),
                            Forms\Components\Select::make('usertype')
                                ->options([
                                    'admin' => 'Admin',
                                    'staff' => 'Staff',
                                    'user' => 'User',
                                ])
                                ->required()
                        ]),
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->form([
                            Forms\Components\FileUpload::make('profile_image')
                                ->image()
                                ->disk('public')
                                ->directory('profile-photos')
                                ->imageEditor()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DateTimePicker::make('email_verified_at')
                                ->label('Email Verification Date'),
                            Forms\Components\Select::make('usertype')
                                ->options([
                                    'admin' => 'Admin',
                                    'staff' => 'Staff',
                                    'user' => 'User',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->dehydrated(fn($state) => filled($state))
                                ->required(fn(string $context): bool => $context === 'create')
                        ]),
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading('Delete User')
                        ->modalDescription('Are you sure you want to delete this user? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete user')
                        ->modalCancelActionLabel('No, cancel'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalHeading('Delete Selected Users')
                        ->modalDescription('Are you sure you want to delete these users? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete users')
                        ->modalCancelActionLabel('No, cancel'),
                    Tables\Actions\BulkAction::make('updateUserType')
                        ->label('Update User Type')
                        ->icon('heroicon-o-user-group')
                        ->form([
                            Forms\Components\Select::make('usertype')
                                ->label('User Type')
                                ->options([
                                    'staff' => 'Staff',
                                    'user' => 'User',
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $record->update([
                                    'usertype' => $data['usertype']
                                ]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('verifyEmails')
                        ->label('Verify Email')
                        ->icon('heroicon-o-envelope')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'email_verified_at' => now()
                                ]);
                            });
                        })
                        ->deselectRecordsAfterCompletion()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
