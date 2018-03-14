<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Categories extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 管理后台加载分类目录管理页面的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menuName'] = 'categories';
        $this->dataForView['vuejs_libs_required'] = [
            'categories_manager'
        ];
        return view('backend.categories.index',$this->dataForView);
    }
}
