<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'description', 'dept_head_id', 'profile_picture', 'is_active', 'created_by', 'updated_by', 'company_id'];

    public function departmentHead()
    {
        return $this->belongsTo(Employee::class, 'dept_head_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    
}