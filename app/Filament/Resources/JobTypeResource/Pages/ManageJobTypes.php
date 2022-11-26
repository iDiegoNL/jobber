<?php

namespace App\Filament\Resources\JobTypeResource\Pages;

use App\Filament\Resources\JobTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJobTypes extends ManageRecords
{
    protected static string $resource = JobTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
