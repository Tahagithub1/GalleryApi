<?php

namespace App\Filament\Resources\Event\EventResource\Pages;

use App\Filament\Resources\Event\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
