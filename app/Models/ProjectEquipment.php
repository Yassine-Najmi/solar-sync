<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEquipment extends Model
{
    protected $fillable = [
        'project_id',
        'equipment_id',
        'quantity',
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
