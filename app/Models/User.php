<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property string avatar_url
 * @property boolean is_admin
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'telegram_id',
        'avatar_url',
        'is_admin',
        'weight',
        'phone',
        'allergy',
        'growth',
        'weight',
        'confirm_token'
    ];

    protected $appends = [
        'full_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','telegarm_id'
    ];

    /**
     * @return string
     */
    public function getFullNameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * @return array
     */
    public static function getFillableFields()
    {
        $instance = new static;
        return $instance->getFillable();
    }

    public function pendingOrder(){
       return Order::where('user_id',$this->id)->where('status',Order::STATUS_PENDING)->first();
    }
}
