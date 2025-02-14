<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ProjectStatus;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id',
        'status',
        'start_date',
        'estimated_completion_date',
        'total_cost',
        'system_size_kw',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'start_date' => 'datetime',
    ];

    public function projectEquipment()
    {
        return $this->hasMany(ProjectEquipment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'project_equipment')
            ->withPivot('quantity', 'total_price')
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
