<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class GroupProduct extends Model
{
    protected $fillable = [
        'product_id',
        'grouped_product_id',
        'quantity',
        'notes',
        'color',
        'options',
    ];

    public $timestamps = false;
}
