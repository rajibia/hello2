<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DoctorMaternityCharge
 *
 * @property int $id
 * @property int $doctor_id
 * @property float $standard_charge
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Doctor $doctor
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge whereStandardCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DoctorMaternityCharge whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DoctorMaternityCharge extends Model
{
    public $table = 'doctor_maternity_charges';

    public $fillable = [
        'doctor_id',
        'standard_charge',
        'currency_symbol',
    ];

    protected $casts = [
        'id' => 'integer',
        'doctor_id' => 'integer',
        'standard_charge' => 'double',
        'currency_symbol' => 'double',
    ];

    public static $rules = [
        'doctor_id' => 'required|unique:doctor_maternity_charges,doctor_id',
        'standard_charge' => 'required',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
