<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RadiologyParameterItem
 *
 * @property int $id
 * @property int $radiology_id
 * @property string $patient_result
 * @property int $parameter_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RadiologyParameter $radiologyParameter
 * @property-read \App\Models\RadiologyTest $radiologyTest
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem whereParameterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem whereRadiologyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem wherePatientResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameterItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RadiologyParameterItem extends Model
{
    use HasFactory;


    public $table = 'radiology_parameter_items';

    public $fillable = [
        'radiology_id',
        'patient_result',
        'parameter_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'radiology_id' => 'integer',
        'patient_result' => 'string',
        'parameter_id' => 'integer',
    ];

    public function radiologyTest(): BelongsTo
    {
        return $this->belongsTo(RadiologyTest::class, 'radiology_id');
    }

    public function radiologyParameter(): BelongsTo
    {
        return $this->belongsTo(RadiologyParameter::class, 'parameter_id');
    }
}
