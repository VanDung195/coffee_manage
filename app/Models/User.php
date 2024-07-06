<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use AuthenticatableTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'birthdate',
        'CCCD',
        'phone',
        'address',
        'account',
        'password',
        'role',
        'delete_at',
        'created_at',
        'updated_at',
        'remember_token',
        'shift_id',
    ];
    //https://laravel.com/docs/11.x/eloquent-mutators             accessors
    // public function getRoleNameAttribute(): string
    // {
    //     // $key = UserRoleEnum::getKey($this->role);
    //     // return $key;
    //     return UserRoleEnum::getKey($this->role);
    // }
    public function getRoleNameSecondAttribute(): string
    {
        return UserRoleEnum::getKey($this->role);
    }
    public function getCccdNameAttribute(): ?string
    {
        if($this->CCCD == null)
        {
            return 'Chưa nhập';
        }
        return $this->CCCD;
    }
    public function getBirthDateNameAttribute()
    {
        if($this->birthdate == null)
        {
            return 'Chưa nhập';
        }
        return $this->birthdate;
    }
}
