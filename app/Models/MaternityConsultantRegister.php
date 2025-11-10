<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaternityConsultantRegister extends Model
{
    protected $fillable = [
        'maternity_id',
        'applied_date',
        'doctor_id',
        'instruction',
        'instruction_date',
    ];

    protected $casts = [
        'applied_date' => 'datetime',
        'instruction_date' => 'date',
    ];

    public function maternity(): BelongsTo
    {
        return $this->belongsTo(Maternity::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
