<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'shift_name',
        'shift_start',
        'shift_end',
        'break_duration',
    ];

    protected $casts = [
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
        'break_duration' => 'integer',
    ];

    public static $rules = [
        'shift_name' => 'required|string|max:255',
        'shift_start' => 'required|date_format:H:i',
        'shift_end' => 'required|date_format:H:i|after:shift_start',
        'break_duration' => 'nullable|integer|min:0',
    ];

    /**
     * Relationship with the Roster model.
     * A shift can have multiple rosters.
     */
    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }
}
