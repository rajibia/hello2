<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vital extends Model
{
    use HasFactory;
    public $table = 'vitals';

    public $fillable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'maternity_id',
        'bp',
        'pulse',
        'respiration',
        'temperature',
        'oxygen_saturation',
        'height',
        'weight',
        'notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'ipd_id' => 'integer',
        'opd_id' => 'integer',
        'maternity_id' => 'integer',
        'bp' => 'array',
        'pulse' => 'array',
        'respiration' => 'array',
        'temperature' => 'array',
        'oxygen_saturation' => 'array',
        'height' => 'array',
        'weight' => 'array',
        'notes' => 'array',
    ];

    public static $rules = [
        'patient_id' => 'nullable',
        'ipd_id' => 'nullable',
        'opd_id' => 'nullable',
        'maternity_id' => 'nullable',
        'bp' => 'nullable',
        'pulse' => 'nullable',
        'respiration' => 'nullable',
        'temperature' => 'nullable',
        'oxygen_saturation' => 'nullable',
        'height' => 'nullable',
        'weight' => 'nullable',
        'notes' => 'nullable',

    ];
     public function getBmiAttribute()
{
    // Return null if missing data
    if (empty($this->weight) || empty($this->height)) {
        return null;
    }

    // Convert height to meters based on assumed unit
    $heightInMeters = $this->convertHeightToMeters($this->height);
    
    // Convert weight to kg if needed (e.g., if stored in pounds)
    $weightInKg = $this->convertWeightToKg($this->weight);
    
    // Calculate BMI with validation
    if ($heightInMeters > 0 && $weightInKg > 0) {
        return round($weightInKg / ($heightInMeters * $heightInMeters), 1);
    }
    
    return null;
}

protected function convertHeightToMeters($height)
{
    // If height < 3, assume it's already in meters (e.g., 1.7)
    if ($height < 3) {
        return $height;
    }
    // If height between 50-300, assume cm (convert to meters)
    elseif ($height >= 50 && $height <= 300) {
        return $height / 100;
    }
    // If height > 3, assume inches (convert to meters)
    else {
        return $height * 0.0254;
    }
}

protected function convertWeightToKg($weight)
{
    // If weight < 500, assume it's already in kg
    if ($weight < 500) {
        return $weight;
    }
    // Otherwise assume pounds (lbs) and convert to kg
    else {
        return $weight * 0.453592;
    }
}
    public function getBmiAttributexs()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters ** 2), 1);
        }

        return null;
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
    
    public function maternity(): BelongsTo
    {
        return $this->belongsTo(Maternity::class, 'maternity_id');
    }
}
