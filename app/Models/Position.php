<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name', 
        'salary'
    ];

    public function getSalaryFormattedAttribute()
    {
        $salary = number_format($this->salary, 0, ',', '.');
        return $salary;
    }

    public function getSalaryDividedAttribute()
    {
        $salary = $this->salary / 1000;
        return $salary;
    }
}
