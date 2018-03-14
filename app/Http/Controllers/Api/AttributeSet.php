<?php

namespace App\Http\Controllers\Api;

use App\Models\Utils\ContentTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product\ProductAttribute;
use App\Models\Utils\JsonBuilder;
use App\Models\Catalog\Product\AttributeValue;

class AttributeSet extends Controller
{
    /**
     * 加载产品属性记录数据的接口
     * @param Request $request
     * @return string
     */
    public function load_attributes_by_set_ajax(Request $request){
        $pas = ProductAttribute::select('id','name','type','default_value')
            ->where('product_attribute_set_id',$request->get('set_id'))
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();

        $avs = [];
        if(!empty($request->get('product_id'))){
            foreach ($pas as $key=>$pa) {
                $avList = AttributeValue::where('product_id',$request->get('product_id'))
                    ->where('product_attribute_id',$pa->id)
                    ->get();
                if(count($avList)>0){
                    if(count($avList)==1){
                        // 找到了
                        $av = $avList[0];
                        $avs[$key] = $av->value;
                    }else{
                        // 多条记录
                        $avs[$key] = [];
                        foreach ($avList as $av) {
                            $avs[$key][] = [
                                'name'=>$av->notes,
                                'url'=>$av->value,
                                'idx'=>$key
                            ];
                        }
                    }

                }else{
                    $avs[$key] = null;
                }
            }
        }

        if($pas){
            return JsonBuilder::Success(['avs'=>$avs,'pas'=>$pas]);
        }else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 保存产品属性记录数据的接口
     * @param Request $request
     * @return string
     */
    public function save_product_attribute_ajax(Request $request){
        $productAttributeData = $request->get('productAttribute');
        $productAttributeData['default_value'] = ContentTool::RemoveNewLineFromString($productAttributeData['default_value']);
        if(empty($productAttributeData['id'])){
            unset($productAttributeData['id']);
            $pa = ProductAttribute::create($productAttributeData);
            if($pa){
                return JsonBuilder::Success($pa->id);
            }else{
                return JsonBuilder::Error();
            }
        }else{
            $pa = ProductAttribute::find($productAttributeData['id']);
            unset($productAttributeData['id']);
            if($pa){
                foreach ($productAttributeData as $fieldName => $item) {
                    $pa->$fieldName = $item;
                }
                if($pa->save()){
                    return JsonBuilder::Success($pa->id);
                }
            }
            return JsonBuilder::Error();
        }
    }

    /**
     * 加载产品属性记录数据的接口
     * @param Request $request
     * @return string
     */
    public function load_product_attribute_ajax(Request $request){
        $pa = ProductAttribute::find($request->get('id'));
        if($pa){
            return JsonBuilder::Success($pa);
        }else{
            return JsonBuilder::Error();
        }
    }
}
