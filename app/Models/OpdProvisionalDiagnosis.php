<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class OpdDiagnosis
 *
 * @version September 8, 2020, 11:46 am UTC
 *
 * @property int $opd_patient_department_id
 * @property string $report_type
 * @property string $report_date
 * @property string $description
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereOpdPatientDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OpdDiagnosis whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property-read mixed $document_url
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @property-read mixed $opd_diagnosis_document_url
 */
class OpdProvisionalDiagnosis extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const OPD_DIAGNOSIS_PATH = 'opd_provisional_diagnosis';

    public $table = 'opd_provisional_diagnoses';

    public $fillable = [
        'opd_patient_department_id',
     
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'opd_patient_department_id' => 'integer',
        
        'description' => 'string',
    ];

    public static $rules = [
        // 'report_type' => 'required',
        // 'report_date' => 'required',
        // 'opd_patient_department_id' => 'required|exists:opd_patient_departments,id',
        // 'code' => 'required',
        // 'name' => 'required',
        // 'file' => 'nullable|mimes:jpeg,png,pdf,docx,doc',
    ];

    protected $appends = ['opd_provisional_diagnosis_document_url'];

    public function getOpdDiagnosisDocumentUrlAttribute()
    {
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }
}
