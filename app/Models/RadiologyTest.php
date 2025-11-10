<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\LabTechnician;

/**
 * Class RadiologyTest
 *
 * @version April 14, 2020, 9:33 am UTC
 *
 * @property string test_name
 * @property string short_name
 * @property string test_type
 * @property int category_id
 * @property int unit
 * @property string subcategory
 * @property string method
 * @property int report_days
 * @property int charge_category_id
 * @property int standard_charge
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\ChargeCategory $chargecategory
 * @property-read \App\Models\RadiologyCategory $radiologycategory
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereChargeCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereReportDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereStandardCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereSubcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereTestName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereTestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RadiologyTest whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 */
class RadiologyTest extends Model
{
    use HasFactory;

    public $table = 'radiology_tests';

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
        'report_date' => 'date',
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
        'maternity_id' => 'nullable|exists:maternity_patient_departments,id',
        'case_id' => 'required|exists:patient_cases,id',
        'template_id' => 'required|exists:radiology_test_templates,id',
        'report_date' => 'required|date_format:Y-m-d',
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

    public function radiologycategory(): BelongsTo
    {
        return $this->belongsTo(RadiologyCategory::class, 'category_id');
    }

    public function patientcase(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id');
    }

    public function radiologytesttemplate(): BelongsTo
    {
        return $this->belongsTo(RadiologyTestTemplate::class, 'template_id');
    }

    public function chargecategory(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }
    public function parameterItems(): HasMany
    {
        return $this->hasMany(RadiologyParameterItem::class, 'radiology_id');
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
        return $this->belongsTo(MaternityPatientDepartment::class, 'maternity_id');
    }
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function radiologyitem(): BelongsTo
    {
        return $this->belongsTo(RadiologyTestItem::class, 'id');
    }
    public function radiologyTestItems(): HasMany
    {
        return $this->hasMany(RadiologyTestItem::class, 'radiology_id');
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



    // Helper method to get dynamic form configuration from template
    public function getFormConfiguration()
    {
        if ($this->template_id) {
            $template = RadiologyTestTemplate::find($this->template_id);
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
