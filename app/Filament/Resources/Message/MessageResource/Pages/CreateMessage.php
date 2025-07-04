<?php

namespace App\Filament\Resources\Message\MessageResource\Pages;

use App\Filament\Resources\Message\MessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMessage extends CreateRecord
{
    protected static string $resource = MessageResource::class;
}
