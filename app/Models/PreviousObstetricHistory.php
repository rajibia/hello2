<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreviousObstetricHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $table = 'previous_obstetric_history';

    protected $fillable = [
        'patient_id',
        'place_of_delivery',
        'duration_of_pregnancy',
        'complication_in_pregnancy_or_puerperium',
        'birth_weight',
        'gender',
        'infant_feeding',
        'birth_status',
        'alive',
        'alive_or_dead_date',
        'previous_medical_history',
        'special_instruction',
    ];

    /**
     * Relationship with Patient model.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
