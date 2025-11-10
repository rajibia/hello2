<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPostnatalHistory extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = 'ipd_postnatal_history';

    protected $fillable = [
        'patient_id',
        'labour_time',
        'delivery_time', 
        'routine_question',
        'general_remark',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'labour_time' => 'string',
        'delivery_time' => 'string',
        'routine_question' => 'string',
        'general_remark' => 'string',
    ];

    /**
     * Get the patient that owns the antenatal record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
