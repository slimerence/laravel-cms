<?php

namespace App\Http\Controllers\Backend;

use App\Models\Catalog\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Catalog\Product;
use App\Models\Group;
use App\Models\Catalog\Category;
use App\Models\Catalog\Tag;
use App\Models\Catalog\Product\ProductAttributeSet;
use App\Models\Catalog\RelatedProduct;
use App\Models\Utils\JsonBuilder;

class Products extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 删除产品
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($uuid){
        if(Product::Terminate($uuid)){
            session()->flash('msg', ['content' => 'Product has been removed successfully!', 'status' => 'success']);
        }else{
            session()->flash('msg', ['content' => 'Product can not be removed successfully!', 'status' => 'danger']);
        }
        return redirect('backend/products');
    }

    /**
     * 在管理后台加载产品列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->dataForView['menuName'] = 'catalog';
        $this->dataForView['products'] = Product::orderBy('id','desc')->paginate(config('system.PAGE_SIZE'));

        return view('backend.products.index',$this->dataForView);
    }

    /**
     * 在管理后台加载添加产品的界面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request){
        $this->dataForView['menuName'] = 'catalog';
        $this->dataForView['groups'] = Group::orderBy('name','asc')->get();
        $this->dataForView['categories'] = Category::NameList();
        $this->dataForView['categoriesTree'] = Category::Tree()->toArray();
        $this->dataForView['tagslist'] = Tag::GpList();
        $this->dataForView['product'] = new Product;
        $this->dataForView['attributesSet'] = ProductAttributeSet::orderBy('name','asc')->get();
        $this->dataForView['brands'] = Brand::select('name as value','image_url')->orderBy('name','asc')->get();
        $this->dataForView['vuejs_libs_required'] = [
            'products_manager'
        ];
        return view('backend.products.form',$this->dataForView);
    }

    /**
     * 在产品管理后台加载编辑产品的界面
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, Request $request){
        $this->dataForView['menuName'] = 'catalog';
        $this->dataForView['product'] = Product::find($id);
        $this->dataForView['groups'] = Group::orderBy('name','asc')->get();
        $this->dataForView['brands'] = Brand::select('name as value','image_url')->orderBy('name','asc')->get();
        $this->dataForView['categories'] = Category::NameList();
        $this->dataForView['tagslist'] = Tag::GpList();
        $this->dataForView['categoriesTree'] = Category::Tree()->toArray();
        $this->dataForView['attributesSet'] = ProductAttributeSet::orderBy('name','asc')->get();

        $this->dataForView['vuejs_libs_required'] = [
            'products_manager'
        ];

        return view('backend.products.form',$this->dataForView);
    }

    /**
     * 加载某个产品的相关产品
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function related($id){
        $this->dataForView['menuName'] = 'catalog';
        $product = Product::find($id);
        $this->dataForView['product'] = $product;
        $this->dataForView['otherProducts'] = Product::select('id','name')->where('id','<>',$id)->get()->toArray();

        /**
         * 加载已经存在的关联产品数据
         */
        $existedRelatedProducts = $product->loadRelatedProducts(true);
        $this->dataForView['existedRelatedProducts'] = empty($existedRelatedProducts) ? $existedRelatedProducts : $existedRelatedProducts->toArray();

        $this->dataForView['vuejs_libs_required'] = [
            'related_products'
        ];
        return view('backend.products.related',$this->dataForView);
    }

    /**
     * 保存关联的产品
     * @param Request $request
     * @return string
     */
    public function save_related_products(Request $request){
        $done = RelatedProduct::Persistent($request->get('productId'),$request->get('selected'));
        return $done ? JsonBuilder::Success() : JsonBuilder::Error();
    }
}
