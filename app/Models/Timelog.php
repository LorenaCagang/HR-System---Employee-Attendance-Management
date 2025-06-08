<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timelog extends Model
{
    protected $table = 'timelogs';
    
    protected $fillable = [
        'company_id',
        'device_id',
        'ip_address',
        'enroll_id',
        'name',
        'mode',
        'timelogs',
        'is_copied',
        'status',   
        'response',
        'created_by',
        'updated_by',
        'is_active',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
