<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    
    protected $fillable = [
        'code',
        'name',
        'description',
        'created_by',
        'is_active',
    ];
    
    public function departments()
    {
        return $this->hasMany(Department::class, 'company_id');
    }
    
    public function devices()
    {
        return $this->hasMany(Device::class, 'company_id');
    }
    
    public function timelogs()
    {
        return $this->hasMany(Timelog::class, 'company_id');
    }
}
