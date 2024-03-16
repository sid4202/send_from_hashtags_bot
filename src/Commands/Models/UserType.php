<?php

namespace SendMessages\Commands\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type'
    ];

    protected $table = 'user_type';
}