<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\PaymentMethod;

class PaymentMethods extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->dataForView['menuName'] = 'payment-methods';
    }

    /**
     * 加载支付方法管理界面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['payment_methods'] = PaymentMethod::orderBy('id','asc')->get();
        $this->dataForView['vuejs_libs_required'] = [
            'payment_methods_manager'
        ];
        return view('backend.payment_methods.index', $this->dataForView);
    }

    /**
     * 保存 Payment Method
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request){
        PaymentMethod::Persistent($request->get('pm'));
        return redirect()->route('payment-methods');
    }
}
