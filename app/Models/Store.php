<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Store extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = 'stores';

    const ACTIVE = 1;
    const INACTIVE = 0;

    const STATUS_ARR = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    public $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'integer',
    ];

    public static $rules = [
        'name' => 'required|unique:stores,name',
    ];

    public function itemStocks()
    {
        return $this->hasMany(ItemStock::class, 'store_id');
    }
}
