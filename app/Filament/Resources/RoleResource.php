<?php

namespace App\Filament\Resources;

use App\Contracts\PermissionsGroup;
use App\Filament\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use App\Permissions\PropertyPermissions;
use App\Permissions\RolePermissions;
use App\Permissions\UserPermissions;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Contracts\HasLabel;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->columnSpanFull(),
                Forms\Components\Tabs::make('permissions_groups')
                    ->columnSpanFull()
                    ->tabs([
                        self::buildPermissionsTabFor(UserPermissions::class),
                        self::buildPermissionsTabFor(RolePermissions::class),
                        self::buildPermissionsTabFor(PropertyPermissions::class),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('description')
                        ->searchable()
                        ->color('gray')
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    private static function buildPermissionsTabFor(string $class): ?Tab
    {
        if(! is_a($class, BackedEnum::class, true))
            return null;

        if(! is_a($class, HasLabel::class, true))
            return null;

        if(! is_a($class, PermissionsGroup::class, true))
            return null;

        return Tab::make($class::getTabTitle())
            ->schema([
                CheckboxList::make($class::getCategory() . '_permissions')
                    ->label('')
                    ->relationship(
                        'permissions',
                        'name',
                        fn (Builder $query) => $query->where('name', 'like', $class::getCategory() . '%')
                    )
                    ->columns(4)
                    ->getOptionLabelFromRecordUsing(function(Permission $record) use ($class): string {
                        $value = explode(':', $record->name)[1];
                        $enum = $class::tryFrom($value);
                        return is_null($enum) ? $record->name : $enum->getLabel();
                    })
            ]);
    }
}
