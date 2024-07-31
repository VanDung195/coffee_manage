<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function getCreatedAtFormattedAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getPayrollDateFormattedAttribute()
    {
        if($this->payrolll_date)
        {
            return $this->payroll_date;
        }
        return 'Chưa';
    }

    public function getWorkingNumberAttribute()
    {
        // return $this->created_at->diffInDays(Carbon::now());
        if($this->work_hours)
        {
            return $this->work_hours / 4;
        }
        return 'Chưa';
    }

    public function getAbsentNumberAttribute()
    {
        $work_days = $this->work_hours / 4;
        $total_day = $this->created_at->diffInDays(Carbon::now());

        return $total_day - $work_days;
    }

    public function getSalaryFormattedAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.');
    }
}
