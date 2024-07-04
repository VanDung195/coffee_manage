<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceUser extends Model
{
    use HasFactory;
    // protected $table = 'attendance_users';
    
    // protected $primaryKey = 'attendance_id';
    // protected $primaryKey = null;
    // public $incrementing = false;

    public $timestamps = false;
    
    protected $fillable = [
        'attendance_id',
        'user_id',
        'salary_inf_id',
        'status',
    ];

    protected static function booted()
    {
        static::creating(static function($object){
            $object->salary_inf_id = 1;
        });
    }
}
