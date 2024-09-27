<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class InviteUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $breadcrumb = "Invite";

    protected static ?string $title = "Invite User";

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label(__('Invite'));
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('User invited');
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl();
    }
}
