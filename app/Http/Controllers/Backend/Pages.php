<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Lead;

class Pages extends Controller
{
    /**
     * 静态页列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['pages'] = Page::where('type',Page::$TYPE_STATIC_PAGE)
            ->orderBy('title','asc')->paginate(config('system.PAGE_SIZE'));
        $this->dataForView['menuName'] = 'pages';
        return view('backend.pages.index', $this->dataForView);
    }

    /**
     * 添加静态页的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'pages';
        $page = new Page();
        $page->type = Page::$TYPE_STATIC_PAGE;
        $this->dataForView['page'] = $page;
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 加载修改视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['page'] = Page::find($id);
        $this->dataForView['menuName'] = 'pages';
        $this->dataForView['actionName'] = 'edit';
        $this->dataForView['vuejs_libs_required'] = [
            'pages_manager'
        ];
        return view('backend.pages.add', $this->dataForView);
    }

    /**
     * 删除页面
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id){
        $page = Page::find($id);
        if($page){
            $page->delete();
        }
        return redirect('backend/pages/index');
    }

    /**
     * Leads列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function leads(Request $request){
        $this->dataForView['leads'] = Lead::orderBy('id','desc')
            ->paginate(config('system.PAGE_SIZE'));
        $this->dataForView['menuName'] = 'leads';
        return view('backend.pages.leads', $this->dataForView);
    }

    /**
     * 删除Lead
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lead_delete($id){
        Lead::where('id',$id)->delete();
        return redirect('backend/leads');
    }
}
