<?php

namespace App\Filament\Resources\Event;

use App\Filament\Resources\Event\EventResource\Pages;
use App\Filament\Resources\Event\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Morilog\Jalali\Jalalian;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'رزرو ها ';

    protected static ?string $modelLabel = "رزرو ها ";
    protected static ?string $pluralModelLabel = "رزرو ها ";

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
//                    ->relationship('users', 'name')
                    ->label('نام کاربر')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('تاریخ رزرو')
                    ->formatStateUsing(fn ($state) => Jalalian::fromDateTime($state)->format('Y/m/d'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('time')
                    ->label('تایم رزرو')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('پیام کوتاه رزرو')
                    ->searchable()
                    ->sortable(),
//                TextColumn::make('created_at')
//                    ->label('تاریخ و ساعت پیام')
//                    ->formatStateUsing(fn ($state) => Jalalian::fromDateTime($state)->format('Y/m/d H:i'))
//                    ->searchable()
//                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
