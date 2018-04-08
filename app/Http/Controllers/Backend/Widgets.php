<?php

namespace App\Http\Controllers\Backend;

use App\Models\Utils\JsonBuilder;
use App\Models\Widget\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Widget\Slider;
use App\Models\Widget\Block;

class Widgets extends Controller
{
    /**
     * 加载 Sliders 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list_sliders(){
        $this->dataForView['menuName'] = 'sliders';
        $this->dataForView['vuejs_libs_required'] = [
            'sliders_manager'
        ];

        $this->dataForView['sliders'] = Slider::all();

        return view('backend.widgets.sliders',$this->dataForView);
    }

    /**
     * 加载 Blocks 列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list_blocks(){
        $this->dataForView['menuName'] = 'blocks';
        $this->dataForView['blocks'] = Block::all();
        return view('backend.widgets.blocks',$this->dataForView);
    }

    public function list_galleries(){
        $this->dataForView['menuName'] = 'galleries';
        $this->dataForView['vuejs_libs_required'] = [
            'galleries_manager'
        ];

        $this->dataForView['galleries'] = Gallery::all();

        return view('backend.widgets.galleries',$this->dataForView);
    }

    /**
     * 加载添加内容块
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_block(){
        $this->dataForView['menuName'] = 'blocks';
        $this->dataForView['block'] = new Block();
        $this->dataForView['vuejs_libs_required'] = [
            'blocks_manager'
        ];
        return view('backend.widgets.block_form',$this->dataForView);
    }

    /**
     * 加载内容区数据
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_block($id){
        $this->dataForView['menuName'] = 'blocks';
        $this->dataForView['block'] = Block::find($id);
        $this->dataForView['vuejs_libs_required'] = [
            'blocks_manager'
        ];
        return view('backend.widgets.block_form',$this->dataForView);
    }

    /**
     * 删除内容区数据
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete_block($id){
        $this->dataForView['menuName'] = 'blocks';
        Block::where('id',$id)->delete();
        return redirect('backend/widgets/blocks');
    }

    /**
     * 保存内容区
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save_block(Request $request){
        $data = $request->get('block');
        $data['position'] = intval($data['position']);

        if(empty($data['id'])){
            unset($data['id']);
            Block::create($data);
        }else{
            $id = $data['id'];
            unset($data['id']);
            Block::where('id',$id)->update($data);
        }
        return JsonBuilder::Success();
    }
}
