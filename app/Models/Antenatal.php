<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antenatal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'bleeding',
        'headache',
        'pain',
        'constipation',
        'urinary_symptoms',
        'vomiting',
        'cough',
        'vaginal_discharge',
        'oedema',
        'haemorrhoids',
        'date',
        'condition',
        'special_findings_and_remark',
        'pelvic_examination',
        'sp',
        'uter_size',
        'uterus_size',
        'presentation_position',
        'presenting_part_to_brim',
        'foetal_heart',
        'blood_pressure',
        'antenatal_oedema',
        'urine_sugar',
        'urine_albumin',
        'antenatal_weight',
        'remark',
        'next_visit',
        'previous_antenatal_details',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */

    protected $casts = [
        'bleeding' => 'string',
        'headache' => 'string',
        'constipation' => 'string',
        'vomiting' => 'string',
        'cough' => 'string',
        'oedema' => 'string',
        'haemorrhoids' => 'string',
        'date' => 'date',
        'next_visit' => 'date',
    ];


     /**
     * Get the patient that owns the antenatal record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
