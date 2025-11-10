<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\MaternityTimeline
 *
 * @property-read mixed $maternity_timeline_document_url
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline query()
 *
 * @mixin Model
 *
 * @property int $id
 * @property int $maternity_id
 * @property string $title
 * @property string $date
 * @property string|null $description
 * @property bool $visible_to_person
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereMaternityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline whereVisibleToPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaternityTimeline visible()
 */
class MaternityTimeline extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const MATERNITY_TIMELINE_PATH = 'maternity_timelines';

    public $table = 'maternity_timelines';

    public $fillable = [
        'maternity_id',
        'title',
        'date',
        'description',
        'visible_to_person',
    ];

    protected $casts = [
        'id' => 'integer',
        'maternity_id' => 'integer',
        'title' => 'string',
        'date' => 'date',
        'description' => 'string',
        'visible_to_person' => 'boolean',
    ];

    public static $rules = [
        'title' => 'required',
        'date' => 'required',
        'attachment' => 'nullable|mimes:jpeg,png,pdf,docx,doc',
    ];

    protected $appends = ['maternity_timeline_document_url'];

    public function getMaternityTimelineDocumentUrlAttribute()
    {
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }

    public function scopeVisible($query)
    {
        return $query->where('visible_to_person', 1);
    }
}
