<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id','group_id'];

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
