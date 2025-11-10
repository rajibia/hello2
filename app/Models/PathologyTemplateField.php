<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PathologyTemplateField extends Model
{
    use HasFactory;

    protected $table = 'pathology_template_fields';

    public $fillable = [
        'template_id',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'field_placeholder',
        'field_validation',
        'is_required',
        'field_order',
        'field_group',
        'field_unit',
        'reference_range',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(PathologyTestTemplate::class, 'template_id');
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(PathologyTestResult::class, 'template_field_id');
    }
}
