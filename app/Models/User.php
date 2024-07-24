<?php

namespace App\Models;

use App\Enums\UserRoleEnum;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

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
        'is_hidden',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class,'shift_id');
    }

    //https://laravel.com/docs/11.x/eloquent-mutators             accessors
    // public function getRoleNameAttribute(): string
    // {
    //     // $key = UserRoleEnum::getKey($this->role);
    //     // return $key;
    //     return UserRoleEnum::getKey($this->role);
    // }
    public function getRoleNameSecondAttribute(): string
    {
        $role = UserRoleEnum::getKey($this->role);
        $role = Str::lower($role);
        return Str::ucfirst($role);
    }
    public function getCccdNameAttribute(): ?string
    {
        if($this->CCCD == null)
        {
            return 'Chưa nhập';
        }
        return $this->CCCD;
    }
    public function getBirthDateNameAttribute(): string
    {
        if($this->birthdate == null)
        {
            return 'Chưa nhập';
        }
        return $this->birthdate;
    }
    public function getGenderNameAttribute(): string
    {
        if($this->gender == 1)
        {
            return 'Nam';
        }
        return 'Nữ';
    }
    public function getAddressNameAttribute(): string
    {
        if($this->address == null)
        {
            return 'Chưa cập nhật';
        }
        return $this->address;
    }
}
