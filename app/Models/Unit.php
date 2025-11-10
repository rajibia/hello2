<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Unit extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = 'units';

    public $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'boolean',
    ];

    public static $rules = [
        'name' => 'required|unique:units,name',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
