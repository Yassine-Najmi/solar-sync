<?php

namespace App\Filament\Resources;

use App\Enums\ProjectStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Equipment;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-sun';
    protected static ?string $navigationGroup = 'Projects';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Client Information')
                ->schema([
                    Select::make('client_id')
                        ->relationship('client', 'name')
                        ->required()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required(),
                            TextInput::make('phone')->tel()->required(),
                            TextInput::make('address')->required(),
                        ]),
                ])->columns(2),

            Section::make('Project Details')
                ->schema([
                    Select::make('status')
                        ->options(ProjectStatus::class)
                        ->required(),
                    DatePicker::make('start_date')
                        ->required(),
                    DatePicker::make('estimated_completion_date'),
                    TextInput::make('total_cost')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                    TextInput::make('system_size_kw')
                        ->numeric()
                        ->label('System Size (kW)')
                        ->required(),
                ])->columns(2),

            Section::make('Equipment')
                ->schema([
                    Repeater::make('projectEquipment')
                        ->relationship()
                        ->schema([
                            Select::make('equipment_id')
                                ->relationship('equipment', 'model')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('unit_price', Equipment::find($state)?->unit_price ?? 0)
                                ),
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive(),
                            TextInput::make('unit_price')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated(false),
                            TextInput::make('total_price')
                                ->numeric()
                                ->prefix('$')
                                ->disabled()
                                ->dehydrated(true)
                                ->default(0)
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    if ($record) {
                                        $component->state($record->quantity * $record->equipment->unit_price);
                                    }
                                }),
                        ])
                        ->columns(4),
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (ProjectStatus $state): string => match ($state) {
                        ProjectStatus::Lead => 'gray',
                        ProjectStatus::Assessment => 'blue',
                        ProjectStatus::Proposal => 'yellow',
                        ProjectStatus::Contracted => 'purple',
                        ProjectStatus::InProgress => 'orange',
                        ProjectStatus::Completed => 'green',
                    }),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('system_size_kw')
                    ->numeric(2)
                    ->suffix(' kW')
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ProjectStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('generate_proposal')
                    ->icon('heroicon-o-document-text')
                    ->action(fn (Project $record) => $record->generateProposal()),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
