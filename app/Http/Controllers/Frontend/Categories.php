<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Catalog\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Categories extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($uri){
        $category = Category::where('uri',$uri)->first();
    }
}
