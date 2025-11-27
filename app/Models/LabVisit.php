<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class LabVisit
 *
 * @property int $id
 * @property int $patient_id
 * @property int|null $company_id
 * @property string $visit_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Patient $patient
 * @property-read Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection|LabTestResult[] $testResults
 *
 * @method static Builder|LabVisit whereMonth($month)
 * @method static Builder|LabVisit whereYear($year)
 */
class LabVisit extends Model
{
    public $table = 'lab_visits';

    public $fillable = [
        'patient_id',
        'company_id',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(LabTestResult::class);
    }

    // Scope: Filter by month/year
    public function scopeInMonth(Builder $query, $year, $month)
    {
        return $query->whereYear('visit_date', $year)
                     ->whereMonth('visit_date', $month);
    }
}