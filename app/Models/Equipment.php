<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    protected $fillable = [
        'type',
        'model',
        'manufacturer',
        'power_rating',
        'unit_price',
        'specifications',
        'stock_quantity',
        'active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'active' => 'boolean',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_equipment')
            ->withPivot('quantity', 'total_price');
    }
}
