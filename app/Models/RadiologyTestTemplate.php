<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiologyTestTemplate extends Model
{
    use HasFactory;

    // specify the table name
    protected $table = 'radiology_test_templates';

    public $fillable = [
        'test_name',
        'short_name',
        'test_type',
        'category_id',
        'unit',
        'subcategory',
        'method',
        'report_days',
        'charge_category_id',
        'standard_charge',
        'form_configuration',
        'template_type',
        'icon_class',
        'icon_color',
        'is_dynamic_form',
    ];

    protected $casts = [
        'form_configuration' => 'array',
        'is_dynamic_form' => 'boolean',
    ];

    public static $rules = [
        // Add validation rules as needed
    ];

    public function radiologycategory(): BelongsTo
    {
        return $this->belongsTo(RadiologyCategory::class, 'category_id');
    }

    public function radiologyunit(): BelongsTo
    {
        return $this->belongsTo(RadiologyUnit::class, 'unit');
    }

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    public function parameterItems(): HasMany
    {
        return $this->hasMany(RadiologyParameterItem::class, 'radiology_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function ipd(): BelongsTo
    {
        return $this->belongsTo(IpdPatientDepartment::class, 'ipd_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(OpdPatientDepartment::class, 'opd_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
