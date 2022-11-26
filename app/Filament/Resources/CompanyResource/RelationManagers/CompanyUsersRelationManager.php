<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class CompanyUsersRelationManager extends RelationManager
{
    protected static string $relationship = 'companyUsers';

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->helperText('The email address of the user. They must already have an account on ' . config('app.name') . '.')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->exists(User::class, 'email'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('user.email'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add user to company')
                    ->modalButton('Add user')
                    ->label('Add user')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = User::firstWhere('email', $data['email'])->getKey();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
