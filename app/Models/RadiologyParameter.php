<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RadiologyParameter
 *
 * @property int $id
 * @property string $parameter_name
 * @property string $reference_range
 * @property int $unit_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RadiologyUnit $radiologyUnit
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereParameterName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereReferenceRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyParameter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RadiologyParameter extends Model
{
    use HasFactory;

    public $table = 'radiology_parameters';

    public $fillable = [
        'parameter_name',
        'reference_range',
        'unit_id',
        'description',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'parameter_name' => 'string',
        'reference_range' => 'string',
        'unit_id' => 'integer',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'parameter_name' => 'required|unique:radiology_parameters,parameter_name',
        'reference_range' => 'required',
        'unit_id' => 'required',
    ];

    public function radiologyUnit(): BelongsTo
    {
        return $this->belongsTo(RadiologyUnit::class, 'unit_id');
    }
}
