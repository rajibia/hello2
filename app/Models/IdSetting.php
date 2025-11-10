<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdSetting extends Model
{
    protected $fillable = ['scope','enabled','prefix','digits','current_counter'];
    use HasFactory;
}
