<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_ACCEPTED = 'accepted';

    const TYPE_PERSONAL_MENU = 'personal_menu';

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'goals'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    /**
     * @return array
     */
    public static function getFillableFields()
    {
        $instance = new static;
        return $instance->getFillable();
    }
}
