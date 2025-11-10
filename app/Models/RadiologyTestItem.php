<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiologyTestItem extends Model
{
    use HasFactory;

    public $fillable = [
        'radiology_id',
        'report_date',
        'test_name',
    ];

    public function radiologytest(): BelongsTo
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_id');
    }

    public function radiologyTestItems(): HasMany
    {
        return $this->hasMany(RadiologyTestItem::class, 'radiology_id');
    }

    public function radiologytesttemplate(): BelongsTo
    {
        return $this->belongsTo(RadiologyTestTemplate::class, 'test_name', 'id');
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
