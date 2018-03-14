<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\ProductAttributeSet;
use App\Models\Catalog\Product\ProductAttribute;

class AttributeSet extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * 属性集列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menuName'] = 'attribute_sets';

        $this->dataForView['attributeSets'] = ProductAttributeSet::where('id','>',1)
            ->orderBy('name','asc')->paginate(config('system.PAGE_SIZE'));
        return view('backend.attribute_sets.index',$this->dataForView);
    }

    /**
     * 加载新属性集表单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){
        $this->dataForView['menuName'] = 'attribute_sets';
        $this->dataForView['attributeSet'] = new ProductAttributeSet;

        $this->dataForView['parents'] = ProductAttributeSet::orderBy('name','asc')->get();
        return view('backend.attribute_sets.form',$this->dataForView);
    }

    /**
     * 加载编辑属性集表单
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['menuName'] = 'attribute_sets';
        $this->dataForView['attributeSet'] = ProductAttributeSet::find($id);

        $this->dataForView['parents'] = ProductAttributeSet::where('id','!=',$id)->orderBy('name','asc')->get();
        return view('backend.attribute_sets.form',$this->dataForView);
    }

    /**
     * 列出某个属性集合的所有属性
     * @param $id
     * @param Integer | null $productAttributeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listing($id, $productAttributeId=null ){
        $this->dataForView['menuName'] = 'attribute_sets';

        // 加载并注入页面属性集的信息
        $as = ProductAttributeSet::find($id);
        $this->dataForView['attributeSet'] = $as;
        $this->dataForView['attributes'] = $as->attributes;
        $this->dataForView['parentAttributes'] = $as->parent->attributes;

        // 加载属性集的时候,同时加载此属性的记录
        $this->dataForView['productAttributeId'] = $productAttributeId;
        if($productAttributeId){
            $this->dataForView['productAttribute'] = ProductAttribute::find($productAttributeId);
        }else{
            $this->dataForView['productAttribute'] = new ProductAttribute;
        }

        $this->dataForView['vuejs_libs_required'] = [
            'attributes_manager'
        ];

        return view('backend.product_attributes.index',$this->dataForView);
    }

    /**
     * 删除产品属性
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete_product_attribute($id){
        if ($id){
            $pa = ProductAttribute::find($id);
            $attributeSetId = $pa ? $pa->product_attribute_set_id : null;
            $attributeSetName = $pa ? $pa->name : null;
            if($pa && $pa->delete()){
                session()->flash('msg', ['content'=>'Product Attribute "'.$attributeSetName.'" is removed successfully!','status'=>'success']);
                return redirect('backend/attribute-sets/listing/'.$attributeSetId);
            }else{
                session()->flash('msg', ['content'=>'Product Attribute "'.$attributeSetName.'" can not be removed, please try again!','status'=>'danger']);
            }
        }
    }

    /**
     * 删除属性集
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id, Request $request){
        $as = ProductAttributeSet::find($id);
        if($as && $as->delete()){
            session()->flash('msg', ['content'=>'Attribute Set is removed successfully!','status'=>'success']);
        }else{
            session()->flash('msg', ['content'=>'Attribute Set can not be saved, please try again!','status'=>'danger']);
        }

        return redirect('backend/attribute-sets');
    }

    /**
     * 保存属性集
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request){
        if(is_null($request->get('id'))){
            $as = ProductAttributeSet::create(
                [
                    'name'=>$request->get('name'),
                    'parent_id'=>$request->get('parent_id'),
                ]
            );

            if($as){
                session()->flash('msg', ['content'=>'Attribute Set: "'.$request->get('name').'" is saved successfully!','status'=>'success']);
            }else{
                session()->flash('msg', ['content'=>'Attribute Set: "'.$request->get('name').'" can not be saved, please try again!','status'=>'danger']);
            }
        }else{
            $as = ProductAttributeSet::find($request->get('id'));
            if($as){
                $as->name = $request->get('name');
                $as->parent_id = $request->get('parent_id');
                if($as->save()){
                    session()->flash('msg', ['content'=>'Attribute Set: "'.$request->get('name').'" is updated successfully!','status'=>'success']);
                }else{
                    session()->flash('msg', ['content'=>'Attribute Set: "'.$request->get('name').'" can not be updated, please try again!','status'=>'danger']);
                }
            }
        }
        return redirect('backend/attribute-sets');
    }
}
