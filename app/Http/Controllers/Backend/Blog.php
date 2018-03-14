<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;

class Blog extends Controller
{
    /**
     * 博客文章列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['pages'] = Page::where('type',Page::$TYPE_BLOG)->orderBy('title','asc')->get();
        $this->dataForView['menuName'] = 'blog';
        return view('backend.pages.index_blog', $this->dataForView);
    }

    /**
     * 添加博客文章的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'blog';
        $page = new Page();
        $page->type = Page::$TYPE_BLOG;
        $this->dataForView['page'] = $page;
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 加载博客文章视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['page'] = Page::find($id);
        $this->dataForView['menuName'] = 'blog';
        $this->dataForView['actionName'] = 'edit';
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 删除博客文章
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id){
        $page = Page::find($id);
        if($page){
            $page->delete();
        }
        return redirect('backend/blog/index');
    }
}
