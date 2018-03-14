<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class Press extends Controller
{
    /**
     * 新闻列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['pages'] = Page::where('type',Page::$TYPE_NEWS)->orderBy('title','asc')->get();
        $this->dataForView['menuName'] = 'news';
        return view('backend.pages.index_news', $this->dataForView);
    }

    /**
     * 添加新闻的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'news';
        $page = new Page();
        $page->type = Page::$TYPE_NEWS;
        $this->dataForView['page'] = $page;
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 加载新闻视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['page'] = Page::find($id);
        $this->dataForView['menuName'] = 'news';
        $this->dataForView['actionName'] = 'edit';
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 删除新闻
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id){
        $page = Page::find($id);
        if($page){
            $page->delete();
        }
        return redirect('backend/press/index');
    }
}
