<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class LabTestResult
 *
 * @property int $id
 * @property int $lab_visit_id
 * @property string $test_name
 * @property string|null $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read LabVisit $labVisit
 */
class LabTestResult extends Model
{
    public $table = 'lab_test_results';

    public $fillable = [
        'lab_visit_id',
        'test_name',
        'result',
    ];

    protected $casts = [
        'result' => 'string',
    ];

    public function labVisit(): BelongsTo
    {
        return $this->belongsTo(LabVisit::class);
    }

    // Scope: Filter by test name
    public function scopeTest(Builder $query, $name)
    {
        return $query->where('test_name', $name);
    }
}