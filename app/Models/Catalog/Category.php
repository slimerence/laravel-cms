<?php

namespace App\Models\Catalog;

use App\Models\Utils\ContentTool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use DB;
use App\Models\Catalog\Product;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','parent_id','position','short_description',
        'uri','active','include_in_menu','image_path',
        'keywords','seo_description','uuid','as_link','name_cn','brands'
    ];

    protected $casts = [
        'include_in_menu' => 'boolean',
        'as_link' => 'boolean',
        'brands'=>'array'
    ];

    public $timestamps = false;

    const FEATURE_PRODUCTS_URI = 'Feature-Products';
    const PROMOTION_PRODUCTS_URI = 'Promotion';

    public static function NameList($root = 1){
        return self::where('id','!=',$root)->select('id','name')->orderBy('name','asc')->get();
    }

    public static function GetByUuid($uuid){
        return self::where('uuid',$uuid)->orderBy('id','asc')->first();
    }

    /**
     * 加载推荐产品
     * @return mixed
     */
    public static function LoadFeatureProducts(){
        $cps = CategoryProduct::select('product_id')->where('category_id',2)
            ->get();
        $productsId = [];
        foreach ($cps as $cp) {
            $productsId[] = $cp->product_id;
        }
        return Product::whereIn('id',$productsId)->get();
    }

    /**
     * 加载打折产品
     * @return mixed
     */
    public static function LoadPromotionProducts(){
        $cps = CategoryProduct::select('product_id')->where('category_id',3)
            ->get();
        $productsId = [];
        foreach ($cps as $cp) {
            $productsId[] = $cp->product_id;
        }
        return Product::whereIn('id',$productsId)->get();
    }

    /**
     * 保存目录的方法
     * @param array $data
     * @return Integer
     */
    public static function Persistent($data){
//        $data['keywords'] = ContentTool::RemoveNewLineFromString($data['keywords']);
//        $data['seo_description'] = ContentTool::RemoveNewLineFromString($data['seo_description']);

//        if($data['as_link'] === true){
//            // 目录作为链接来讲, 就不能有任何 html tag 了
//            $data['short_description'] = strip_tags($data['short_description']);
//        }

        if(!isset($data['id']) || is_null($data['id']) || empty(trim($data['id']))){
            unset($data['id']);
            $data['uuid'] = Uuid::uuid4()->toString();
            $data['uri'] = ContentTool::ConvertNameToUri($data['name']);

            $category = self::create(
                $data
            );
            if($category){
                return $category->id;
            }else{
                return 0;
            }
        }else{

            $category = self::find($data['id']);
            unset($data['id']);
            foreach ($data as $field_name=>$field_value) {
                // uri中不能出现特殊的字符
                if($field_name == 'uri'){
                    $field_value = ContentTool::ConvertNameToUri($data['name']);
                }
                $category->$field_name = $field_value;
            }
            if($category->save()){
                return $category->id;
            }else{
                return 0;
            }
        }
    }

    /**
     * 删除一个目录的方法
     * @param $id
     * @return bool|null
     * @throws \Exception
     */
    public static function Terminate($id){
        $result = false;
        DB::beginTransaction();
        $category = self::find($id);
        if($category){
            // 删除所有的子目录
            self::where('parent_id',$id)->delete();
            // 删除所有关联产品记录
            $category->removeAllProductsRelation();
            // 删除自己
            $result = $category->delete();
        }

        if($result){
            DB::commit();
        }else{
            DB::rollBack();
        }
        return $result;
    }

    /**
     * 删除当前目录所有关联的产品的关系记录
     * @return mixed
     */
    public function removeAllProductsRelation(){
        return CategoryProduct::where('category_id',$this->id)->delete();
    }

    /**
     * 返回给定id作为根的目录树结构
     * @param int $root
     * @return int
     */
    public static function Tree($root = 1){
        $root = self::find($root);
        $root->loadTree();
        return $root;
    }

    /**
     * 利用递归的方式取得目录的结构树
     */
    public function loadTree(){
        foreach ($this->children as $child) {
            $child->loadTree();
        }
    }

    /**
     * 取得所包含的产品的信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productCategories(){
        $cps = CategoryProduct::select('product_id')->where('category_id',$this->id)->get();
        $productsId = [];
        if(count($cps)>0){
            foreach ($cps as $cp) {
                $productsId[] = $cp->product_id;
            }
        }
        if(count($productsId)>0){
            return Product::whereIn('id',$productsId)->orderBy('position','ASC')->orderBy('id','DESC')->get();
        }else{
            return null;
        }
    }

    /**
     * 取得所包含的产品的信息
     * @param int $max
     * @return null
     */
    public function productCategoriesSimple($max = 6){
        $cps = CategoryProduct::select('product_id')
            ->where('category_id',$this->id)
            ->orderBy('position','asc')
            ->take($max)
            ->get();
        $productsId = [];
        if(count($cps)>0){
            foreach ($cps as $key=>$cp) {
                $productsId[] = $cp->product_id;
                if($key == $max-1){
                    break;
                }
            }
        }

        if(count($productsId)>0){
            return Product::select('uuid','name','uri','image_path')
                ->whereIn('id',$productsId)
                ->orderBy('position','ASC')
                ->orderBy('id','DESC')
                ->get();
        }else{
            return [];
        }
    }

    /**
     * 为了生成导航菜单中的目录数据
     * @return array
     */
    public function loadForNav(){
//        $children = $this->children;
        $children = self::where('parent_id', $this->id)->where('include_in_menu',true)
            ->orderBy('position','asc')
            ->get();
        $data = [
            'subs' => [],
            'brands' => Brand::whereIn('id',$this->brands)->get()->toArray(),
            'images'=>[],
            'products'=>$this->productCategoriesSimple()
        ];
        foreach ($children as $child) {
            // 只包含 include_in_menu 为 true 的子目录
            if($child->include_in_menu){
                $products = $child->productCategoriesSimple(3);
                // 子目录必须要有至少一个产品才能出现在子菜单中
                if(count($products)>0){
                    $data['subs'][] = [
                        'id'=>$child->uuid,
                        'name'=>$child->name,
                        'uri'=>$child->uri,
                        'products'=>$child->productCategoriesSimple(3)
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * 获取该目录的URL
     * @return string
     */
    public function getCategoryUrl(){
        if(empty($this->uri)){
            return '/category/view/'.$this->id;
        }else{
            return '/category/view/'.$this->uri;
        }
    }

    /**
     * 根据给定的URI来取得对应的分类对象
     * @param $uri
     * @return mixed
     */
    public static function GetByUri($uri){
        return self::select('id','name','uri','parent_id')
            ->where('uri',$uri)
            ->orderBy('id','desc')
            ->first();
    }

    /**
     * 取得当前目录的父级目录
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    /**
     * 取得当前目录对象的下一级目录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany(Category::class,'parent_id');
    }

    /**
     * 获取第一级别的目录
     */
    public static function LoadFirstLevelCategoriesInMenu(){
        return self::where('parent_id',1)
            ->where('include_in_menu', true)
            ->orderBy('position','asc')
            ->get();
    }

    /**
     * 当前产品的数量
     * @return mixed
     */
    public function productsCount(){
        return CategoryProduct::where('category_id',$this->id)->count();
    }

    public function loadBrands(){
        return Brand::whereIn('id',$this->brands)->get();
    }
}
