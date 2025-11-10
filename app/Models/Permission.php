<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'add',
        'edit',
        'delete',
        'view',
        'guard_name',
    ];

    public function getModuleAttribute(): string
    {
        return ucfirst($this->name);
    }

    public function getPermissionsArray(): array
    {
        return [
            'add' => (bool) $this->add,
            'edit' => (bool) $this->edit,
            'delete' => (bool) $this->delete,
            'view' => (bool) $this->view,
        ];
    }
}
