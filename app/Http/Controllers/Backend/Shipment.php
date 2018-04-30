<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shipment\DeliveryFee;

class Shipment extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 管理后台加载运费管理页面的方法
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menuName'] = 'shipment';
        $this->dataForView['deliveryFees'] = DeliveryFee::all();
        return view('backend.shipment.index',$this->dataForView);
    }

    /**
     * 添加新的运费记录
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(){
        $this->dataForView['menuName'] = 'blog';
        $this->dataForView['deliveryFee'] = new DeliveryFee();
        $this->dataForView['countries'] = DeliveryFee::GetAvailableCountries();

        $this->dataForView['vuejs_libs_required'] = [
            'shipment_manager'
        ];
        return view('backend.shipment.form', $this->dataForView);
    }

    /**
     * 保存运费
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request){
        $data = $request->all();
        unset($data['_token']);
        $id = $data['id'];
        unset($data['id']);
        if(empty($id)){
            DeliveryFee::create($data);
        }else{
            DeliveryFee::where('id',$id)->update($data);
        }
        return redirect('backend/shipment');
    }

    /**
     * 加载运费视图
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $this->dataForView['deliveryFee'] = DeliveryFee::find($id);
        $this->dataForView['menuName'] = 'shipment';
        $this->dataForView['actionName'] = 'edit';
        $this->dataForView['countries'] = DeliveryFee::GetAvailableCountries();
        $this->dataForView['vuejs_libs_required'] = [
            'shipment_manager'
        ];
        return view('backend.shipment.form', $this->dataForView);
    }

    /**
     * 删除博客文章
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id){
        $fee = DeliveryFee::find($id);
        if($fee){
            $fee->delete();
        }
        return redirect('backend/shipment');
    }
}
