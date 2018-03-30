<?php

namespace App\Http\Controllers\Backend;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Groups extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 经销商后台管理 列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['groups'] = Group::where('id','>',1)->orderBy('name','asc')->paginate(config('system.PAGE_SIZE'));
        $this->dataForView['menuName'] = 'groups';
        return view('backend.groups.index', $this->dataForView);
    }

    public function add(){
        $this->dataForView['group'] = new Group();
        $this->dataForView['menuName'] = 'groups';
        return view('backend.groups.form', $this->dataForView);
    }

    public function edit($id){
        $this->dataForView['group'] = Group::find($id);
        $this->dataForView['menuName'] = 'groups';
        return view('backend.groups.form', $this->dataForView);
    }

    public function delete($id){
        Group::where('id',$id)->delete();
        return redirect()->route('groups');
    }

    public function save(Request $request){
        $groupData = $request->all();
        unset($groupData['_token']);

        if(empty($groupData['id'])){
            unset($groupData['id']);
            Group::Persistent($groupData);
        }else{
            $id = $groupData['id'];
            unset($groupData['id']);

            Group::where('id', $id)
                ->update($groupData);
        }

        return redirect()->route('groups');
    }
}
