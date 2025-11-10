<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RadiologyUnit
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RadiologyUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RadiologyUnit extends Model
{
    use HasFactory;

    public $table = 'radiology_units';

    public $fillable = [
        'name',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:radiology_units,name',
    ];
}
