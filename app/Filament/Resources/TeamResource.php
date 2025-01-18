<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleEnum;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     protected static ?string $tenantOwnershipRelationshipName = 'members';

     protected static ?string $navigationLabel = 'Teams';
     protected static ?string $navigationIcon = 'heroicon-o-users';
     protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('slug')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug')->sortable()->searchable(),
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

    public static function getNavigation(): array
    {
        if (auth()->user()->hasRole(RoleEnum::SUPER_ADMIN)) {
            return [
                [
                    'label' => static::$navigationLabel,
                    'icon' => static::$navigationIcon,
                    'url' => static::getUrl(),
                ]
            ];
        }

        return [];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return auth()->user()->hasRole(RoleEnum::SUPER_ADMIN) ? 'Management' : null;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(RoleEnum::SUPER_ADMIN);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
