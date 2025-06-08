<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'devices';
    
    protected $fillable = [
        'company_id',
        'serial_name',
        'endpoint',
        'port',
        'path',
        'status',
        'created_by',
        'is_active',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
