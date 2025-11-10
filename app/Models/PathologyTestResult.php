<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PathologyTestResult extends Model
{
    use HasFactory;

    protected $table = 'pathology_test_results';

    public $fillable = [
        'pathology_test_id',
        'template_field_id',
        'field_value',
        'field_status',
        'notes',
    ];

    protected $casts = [
        'field_value' => 'string',
    ];

    public function pathologyTest(): BelongsTo
    {
        return $this->belongsTo(PathologyTest::class, 'pathology_test_id');
    }

    public function templateField(): BelongsTo
    {
        return $this->belongsTo(PathologyTemplateField::class, 'template_field_id');
    }
}
