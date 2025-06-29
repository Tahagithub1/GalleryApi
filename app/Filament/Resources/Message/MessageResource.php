<?php

namespace App\Filament\Resources\Message;

use App\Filament\Resources\Message\MessageResource\Pages;
use App\Filament\Resources\Message\MessageResource\RelationManagers;
use App\Models\Message\Message;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'مسیج ها';

    protected static ?string $modelLabel = "مسیج ها";
    protected static ?string $pluralModelLabel = "مسیج ها";
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
                TextColumn::make('message')
                    ->label('پیام کاربر')
                    ->limit(50)
                    ->tooltip(fn($record): string => $record->message)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('تاریخ ارسال مسیج')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMessages::route('/'),
            'create' => Pages\CreateMessage::route('/create'),
            'edit' => Pages\EditMessage::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
