<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Brand;

class Brands extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->dataForView['menuName'] = 'brands';
    }

    /**
     * 品牌后台管理 列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['brands'] = Brand::orderBy('name','asc')->paginate(config('system.PAGE_SIZE'));

        $this->dataForView['vuejs_libs_required'] = [
            'brands_manager'
        ];

        return view('backend.brands.index', $this->dataForView);
    }

    public function add(){
        $this->dataForView['brand'] = new Brand();
        return view('backend.brands.form', $this->dataForView);
    }

    public function edit($id){
        $this->dataForView['brand'] = Brand::find($id);
        return view('backend.brands.form', $this->dataForView);
    }

    public function delete($id){
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('brands');
    }

    /**
     * 保存Brand的信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        $groupData = $request->all();
        $imageUrl = null;
        if($request->hasFile('file')){
            $uploaded = $request->file('file');
            $storagePath = _buildUploadFolderPath();
            if(is_array($uploaded)){

            }else{
                $imageUrl = $request->file('file')
                    ->store($storagePath,'public');
            }
        }

        unset($groupData['_token']);
        unset($groupData['file']);

        if(empty($groupData['id'])){
            unset($groupData['id']);
            $groupData['image_url'] = $imageUrl;
            Brand::Persistent($groupData);
        }else{
            $id = $groupData['id'];
            unset($groupData['id']);

            if($imageUrl){
                $groupData['image_url'] = $imageUrl;
            }

            Brand::where('id', $id)
                ->update($groupData);
        }

        return redirect()->route('brands');
    }
}
