<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    use HasFactory;

    protected $table = 'rosters';

    protected $fillable = [
        'shift_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Validation rules
    public static $rules = [
        'shift_id' => 'required|exists:shifts,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    // Relationship with Shift model
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
