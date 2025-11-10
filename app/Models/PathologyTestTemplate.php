<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PathologyTestTemplate extends Model
{
    use HasFactory;

    // specify the table name
    protected $table = 'pathology_test_templates';

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
        'pathology_id', // Add pathology_id to make it fillable but optional
        // 'patient_id',
        // 'ipd_id',
        // 'opd_id',
        // 'doctor_id',
        // 'status',
    ];

    protected $casts = [
        'form_configuration' => 'array',
        'is_dynamic_form' => 'boolean',
        // 'id' => 'integer',
        // 'test_name' => 'string',
        // 'short_name' => 'string',
        // 'test_type' => 'string',
        // 'category_id' => 'integer',
        // 'unit' => 'integer',
        // 'subcategory' => 'string',
        // 'method' => 'string',
        // 'report_days' => 'integer',
        // 'charge_category_id' => 'integer',
        // 'standard_charge' => 'integer',
        // 'patient_id' => 'integer',
        // 'ipd_id' => 'integer',
        // 'opd_id' => 'integer',
        // 'doctor_id' => 'integer',
        // 'status' => 'integer',
    ];

    public static $rules = [
        // 'test_name' => 'required|unique:pathology_tests,test_name',
        // 'short_name' => 'required',
        // 'test_type' => 'required',
        // 'category_id' => 'required',
        // 'charge_category_id' => 'required',
        // 'standard_charge' => 'required',
    ];

    public function pathologycategory(): BelongsTo
    {
        return $this->belongsTo(PathologyCategory::class, 'category_id');
    }
    public function pathologyunit(): BelongsTo
    {
        return $this->belongsTo(PathologyUnit::class, 'unit');
    }

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
    public function parameterItems(): HasMany
    {
        return $this->hasMany(PathologyParameterItem::class, 'pathology_id');
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

    public function templateFields(): HasMany
    {
        return $this->hasMany(PathologyTemplateField::class, 'template_id');
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(PathologyTestResult::class, 'template_id');
    }
}
