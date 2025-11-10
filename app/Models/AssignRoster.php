<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignRoster extends Model
{
    use HasFactory;

    protected $table = 'assign_rosters';

    const STATUS_ARR = [
        '2' => 'All',
        '0' => 'Pending',
        '1' => 'Completed',
        '3' => 'Cancelled',
    ];

    protected $fillable = [
        'roster_id',
        'user_id',
        'department_id',
    ];

    /**
     * Relationships (if needed)
     */
    
    public function roster()
    {
        return $this->belongsTo(Roster::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);  
    }

    public function department()
    {
        return $this->belongsTo(Department::class);  
    }
}
