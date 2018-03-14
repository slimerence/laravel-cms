<?php

namespace App\Models\Newsletter;

use Illuminate\Database\Eloquent\Model;

class UserSubscribe extends Model
{
    protected $fillable = ['email','type','user_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
