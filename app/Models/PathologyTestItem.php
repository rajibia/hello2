<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PathologyTestItem extends Model
{
    use HasFactory;

    public $fillable = [
        'pathology_id',
        'report_date',
        'test_name',
    ];


    public function pathologytest(): BelongsTo
    {
        return $this->belongsTo(PathologyTest::class, 'pathology_id');
    }

    public function pathologyTestItems(): HasMany
    {
        return $this->hasMany(PathologyTestItem::class, 'pathology_id');
    }

    public function pathologytesttemplate(): BelongsTo
    {
        return $this->belongsTo(PathologyTestTemplate::class, 'test_name', 'id');
    }
    // public function patient(): BelongsTo
    // {
    //     return $this->belongsTo(Patient::class, 'id');
    // }

    public function lab_technician(): BelongsTo
    {
        return $this->belongsTo(LabTechnician::class, 'lab_technician_id');
    }

    public function approved_by(): BelongsTo
    {
        return $this->belongsTo(LabTechnician::class, 'approved_by_id');
    }
}
