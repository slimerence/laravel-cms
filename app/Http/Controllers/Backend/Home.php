<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuration;

class Home extends Controller
{
    /**
     * 加载配置项
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $this->dataForView['menuName'] = 'config';
        $this->dataForView['config'] = Configuration::find(1);
        return view('backend.home.dashboard', $this->dataForView);
    }

    /**
     * 保存系统的配置信息
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save_config(Request $request){
        $data = $request->all();

        $path = '';
        $pathDarkLogo = '';
        if($request->has('file')){
            if($request->file('file'))
                $path = $request->file('file')->store(_buildUploadFolderPath(),'public');
        }

        if($request->has('image')){
            if($request->file('image'))
                $pathDarkLogo = $request->file('image')->store(_buildUploadFolderPath(),'public');
        }

        /**
         * 整理配置数据,准备更新
         */
        if(!empty($path)){
            $data['logo'] = _buildFrontendAssertPath($path);
        }else{
            unset($data['logo']);
        }

        if(!empty($pathDarkLogo)){
            $data['logo_dark'] = _buildFrontendAssertPath($pathDarkLogo);
        }else{
            unset($data['logo_dark']);
        }

        unset($data['_token']);
        unset($data['file']);
        unset($data['image']);

        Configuration::where('id',1)->update($data);
        return redirect('/home');
    }
}
