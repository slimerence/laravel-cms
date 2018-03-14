<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

class Menus extends Controller
{
    /**
     * 菜单列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menus'] = Menu::orderBy('position','asc')->get();
        $this->dataForView['menuName'] = 'menus';
        return view('backend.menus.index', $this->dataForView);
    }

    /**
     * 添加菜单的表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menus'] = Menu::getRootMenus();
        $this->dataForView['menuName'] = 'menus';
        return view('backend.menus.add', $this->dataForView);
    }

    /**
     * 加载修改视图
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['menu'] = Menu::find($id);
        $this->dataForView['menus'] = Menu::getRootMenus();
        $this->dataForView['menuName'] = 'menus';
        return view('backend.menus.edit', $this->dataForView);
    }

    /**
     * 删除菜单
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id, Request $request){
        if(Menu::Terminate($id)){
            return redirect('backend/menus/index');
        }
    }

    /**
     * 保存菜单
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function persistent(Request $request){
        $data = $request->all();
        unset($data['_token']);
        if(Menu::Persistent($data)){
            return redirect('backend/menus/index');
        }
    }
}
