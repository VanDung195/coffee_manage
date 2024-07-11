<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryInformation extends Model
{
    use HasFactory;
    protected $fillable = [
        'payroll_date',
        'work_hours',
        'total_amount',
        'bonus',
        'penalties',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
