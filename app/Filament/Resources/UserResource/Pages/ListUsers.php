<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('invite')
                ->label('Invite User')
                ->model(User::class)
                ->authorize(['invite'])
                ->url(UserResource::getUrl('invite'))
        ];
    }
}
