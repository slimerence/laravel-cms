<?php

namespace App\Models\Customer;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'company_name',
        'accountant_name',
        'accountant_email',
        'accountant_phone',
        'discount',
        'ABN',
        'ACN',
    ];

    public function customer(){
        return $this->belongsTo(User::class);
    }
}
