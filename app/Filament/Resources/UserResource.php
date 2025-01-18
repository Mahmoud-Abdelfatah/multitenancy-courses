<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Team;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Enums\RoleEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $relationship = 'members';

    public static function form(Form $form): Form
    {
        $roles = Role::all();

        if (!auth()->user()->hasRole(RoleEnum::SUPER_ADMIN)) {
            
            $roles = $roles->filter(function ($role) {
                return $role->name !== RoleEnum::SUPER_ADMIN->value; 
            });
        }

        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email(),
                TextInput::make('password')
                    ->minLength(8)
                    ->password()
                    ->label('Password')
                    ->dehydrated(fn ($state) => !empty($state))
                    ->required(fn ($record) => $record === null),

                TextInput::make('password_confirmation')
                    ->same('password')
                    ->password()
                    ->label('Confirm Password')
                    ->required(fn ($record) => $record === null), 
                
                Select::make('team_id')
                    ->label('Team')
                    ->options(Team::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),  
                    
                Select::make('role_id')
                    ->label('Role')
                    ->options($roles->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->default(fn ($record) => $record?->roles->first()?->id)
                    ,    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('team.name')->label('Team')->sortable()->searchable(),
                TextColumn::make('roles.name')->label('Role')->sortable()->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole(RoleEnum::TENANT_ADMIN) || auth()->user()->hasRole(RoleEnum::SUPER_ADMIN);
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(RoleEnum::TENANT_ADMIN) || auth()->user()->hasRole(RoleEnum::SUPER_ADMIN);
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
