<?php

namespace App\Filament\Resources;

use App\Enums\CompanyEmployeeCount;
use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Camya\Filament\Forms\Components\TitleWithSlugInput;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use League\ISO3166\ISO3166;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema([
                        TitleWithSlugInput::make(
                            fieldTitle: 'name',
                            fieldSlug: 'slug',
                            urlPath: '/companies/',
                            titleRules: ['required', 'max:255'],
                            slugRules: ['required', 'max:255'],
                        ),
                        Forms\Components\MarkdownEditor::make('description')
                            ->maxLength(255)
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                            ])
                            ->required(),
                    ]),
                Forms\Components\Select::make('country')
                    ->required()
                    ->searchable()
                    ->options(collect((new ISO3166)->all())->mapWithKeys(function ($item) {
                        return [$item['alpha2'] => $item['name']];
                    })),
                Forms\Components\TextInput::make('founded_in')
                    ->label('Founding year')
                    ->numeric()
                    ->maxValue(now()->year)
                    ->required(),
                Forms\Components\TextInput::make('website')
                    ->label('Website')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Select::make('employee_count')
                    ->required()
                    ->options(CompanyEmployeeCount::optionsWithDescription()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->description(fn(Company $record) => Str::limit($record->description, 20)),
                Tables\Columns\TextColumn::make('founded_in')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_count')
                    ->enum(CompanyEmployeeCount::optionsWithDescription())
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CompanyUsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereRelation('companyUsers', 'user_id', auth()->id());
    }
}
