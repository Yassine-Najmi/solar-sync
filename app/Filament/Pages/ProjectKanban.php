<?php

namespace App\Filament\Pages;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class ProjectKanban extends KanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Project Board';
    protected static ?int $navigationSort = 2;

    protected static string $model = Project::class;
    protected static string $statusEnum = ProjectStatus::class;

    protected function getEditModalFormSchema(?int $recordId): array
    {
        return [
            TextInput::make('title')
                ->required(),
            Select::make('client_id')
                ->relationship('client', 'name')
                ->required(),
            DatePicker::make('start_date')
                ->required(),
            DatePicker::make('estimated_completion_date'),
            TextInput::make('project_value')
                ->numeric()
                ->prefix('$')
                ->required(),
        ];
    }

    protected static string $recordView = 'filament-kanban.project-record';
}
