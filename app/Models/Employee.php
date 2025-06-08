<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Timelog;


class Employee extends Model
{

    use HasFactory;

    protected $fillable = [
        'photo',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'birthday',
        'department_id',
        'position',
        'hire_date',
        'email',
        'contact_number',
        'remarks',
        'gender',
        'is_active',
        'enroll_id',
        'created_by',
        'created_at',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function timelogs()
    {
        return $this->hasMany(Timelog::class);
    }


}
