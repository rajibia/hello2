<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PathologyTest
 *
 * @property int $id
 * @property string $bill_no
 * @property string|null $note
 * @property string|null $previous_report_value
 * @property int $discount
 * @property int $amount_paid
 * @property int $balance
 * @property int $patient_id
 * @property int|null $ipd_id
 * @property int|null $opd_id
 * @property int $doctor_id
 * @property int $case_id
 * @property int $total
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ChargeCategory|null $chargecategory
 * @property-read \App\Models\PatientCase $patientcase
 * @property-read \App\Models\PathologyTestTemplate|null $pathologytesttemplate
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\IpdPatientDepartment|null $ipd
 * @property-read \App\Models\OpdPatientDepartment|null $opd
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\PathologyTestItem|null $pathologyitem
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PathologyTestItem[] $pathologyTestItems
 * @property-read int|null $pathology_test_items_count
 * @property-read \App\Models\LabTechnician|null $lab_technician
 * @property-read \App\Models\LabTechnician|null $approved_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PathologyTestResult[] $testResults
 * @property-read int|null $test_results_count
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereBillNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereCaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereIpdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereOpdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest wherePreviousReportValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PathologyTest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PathologyTest extends Model
{
    use HasFactory;

    public $table = 'pathology_tests';

    // Status constants
    const STATUS_PENDING = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;

    const STATUS_ARR = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_IN_PROGRESS => 'In Progress',
        self::STATUS_COMPLETED => 'Completed',
    ];

    public $fillable = [
        'bill_no',
        'lab_number',
        'note',
        'previous_report_value',
        'discount',
        'amount_paid',
        'balance',
        'patient_id',
        'ipd_id',
        'opd_id',
        'maternity_id',
        'doctor_id',
        'case_id',
        'total',
        'status',
        'template_id', // Reference to dynamic template
        'test_results', // JSON field for dynamic form results
        'lab_technician_id',
        'approved_by_id',
        'approved_date',
        'collection_date',
        'expected_date',
        'diagnosis', // Add diagnosis field
        'performed_by', // Add performed_by field
    ];

    protected $casts = [
        'test_results' => 'array',
        'approved_date' => 'datetime',
        'collection_date' => 'datetime',
        'expected_date' => 'datetime',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    public static $rules = [
        // Required fields
        'patient_id' => 'required|exists:patients,id',
        'opd_id' => 'nullable|exists:opd_patient_departments,id',
        'ipd_id' => 'nullable|exists:ipd_patient_departments,id',
        'maternity_id' => 'nullable|exists:maternity_patients,id',
        'case_id' => 'required|exists:patient_cases,id',
        'template_id' => 'required|exists:pathology_test_templates,id',
        'report_date' => 'required|date',
        'doctor_id' => 'required|exists:doctors,id',

        // Optional fields
        'note' => 'nullable|string',
        'previous_report_value' => 'nullable|string|max:255',
        'lab_technician_id' => 'nullable|exists:lab_technicians,id',
        'collection_date' => 'nullable|date',
        'expected_date' => 'nullable|date',

        // Discount and Total
        'discount_percent' => 'nullable|numeric|min:0|max:100',
    ];

    public function pathologycategory(): BelongsTo
    {
        return $this->belongsTo(PathologyCategory::class, 'category_id');
    }

    public function patientcase(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id');
    }

    public function pathologytesttemplate(): BelongsTo
    {
        return $this->belongsTo(PathologyTestTemplate::class, 'template_id');
    }

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function ipd(): BelongsTo
    {
        return $this->belongsTo(IpdPatientDepartment::class, 'ipd_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(OpdPatientDepartment::class, 'opd_id');
    }

    public function maternity(): BelongsTo
    {
        return $this->belongsTo(\App\Models\MaternityPatient::class, 'maternity_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function pathologyitem(): BelongsTo
    {
        return $this->belongsTo(PathologyTestItem::class, 'id');
    }

    public function pathologyTestItems(): HasMany
    {
        return $this->hasMany(PathologyTestItem::class, 'pathology_id');
    }

    public function lab_technician(): BelongsTo
    {
        return $this->belongsTo(LabTechnician::class, 'lab_technician_id');
    }

    public function approved_by(): BelongsTo
    {
        return $this->belongsTo(LabTechnician::class, 'approved_by_id');
    }

    public function performed_by_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function testResults(): HasMany
    {
        return $this->hasMany(PathologyTestResult::class, 'pathology_test_id');
    }

    // Helper method to get dynamic form configuration from template
    public function getFormConfiguration()
    {
        if ($this->template_id) {
            $template = PathologyTestTemplate::find($this->template_id);
            return $template ? $template->form_configuration : [];
        }
        return [];
    }

    // Method to accept test request by lab technician
    public function acceptByLabTechnician($labTechnicianId = null)
    {
        $this->status = self::STATUS_IN_PROGRESS;
        $this->lab_technician_id = $labTechnicianId ?? auth()->id();
        $this->save();

        return $this;
    }

    // Method to complete test
    public function completeTest()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->save();

        return $this;
    }

    // Helper method to get test results for a specific field
    public function getFieldResult($fieldName)
    {
        return $this->test_results[$fieldName] ?? null;
    }

    // Helper method to set test result for a specific field
    public function setFieldResult($fieldName, $value)
    {
        $results = $this->test_results ?? [];
        $results[$fieldName] = $value;
        $this->test_results = $results;
        $this->save();
    }
}
