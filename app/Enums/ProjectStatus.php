<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum ProjectStatus: string
{
    use IsKanbanStatus;

    case Lead = 'lead';
    case Assessment = 'assessment';
    case Proposal = 'proposal';
    case Contracted = 'contracted';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case UnderMaintenance = 'under_maintenance';
    case Cancelled = 'cancelled';
}
