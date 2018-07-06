<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use DB;
use App\Models\Catalog\Product;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','position','uri','active','keywords','seo_description','uuid','name_cn'
    ];

    public $timestamps = false;

    public static function GpList(){
        $tagdata =self::select('id','name')->orderBy('name','asc')->get();

/*        $tagsGp = [];
        if(count($tagdata)>0){
            foreach ($tagdata as $tag) {
                $tagsGp[] = [ 'id'=>$tag->id, 'name'=>$tag->name];
            }
        }*/
        return $tagdata;
    }

    public static function GetByUuid($uuid){
        return self::where('uuid', $uuid)->orderBy('id', 'asc')->first();
    }


    /**
     * 保存目录的方法
     * @param array $data
     * @return Integer
     */
    public static function Persistent($data){

        if(!isset($data['id']) || is_null($data['id']) || empty(trim($data['id']))){
            unset($data['id']);
            $data['uuid'] = Uuid::uuid4()->toString();
            $data['uri'] = urlencode(str_replace(' ','-',$data['name']));

            $tag = self::create(
                $data
            );
            if($tag){
                return $tag->id;
            }else{
                return 0;
            }
        }else{

            $tag = self::find($data['id']);
            unset($data['id']);
            foreach ($data as $field_name=>$field_value) {
                $tag->$field_name = $field_value;
            }
            if($tag->save()){
                return $tag->id;
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
        $tag = self::find($id);
        if($tag){
            // 删除所有关联产品记录
            $tag->removeAllProductsRelation();
            // 删除自己
            $result = $tag->delete();
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
        return TagProduct::where('tag_id',$this->id)->delete();
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
    public function productTags(){
        $cps = TagProduct::select('product_id')->where('tag_id',$this->id)->get();
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
    public function productTagsSimple($max = 6){
        $cps = TagProduct::select('product_id')
            ->where('tag_id',$this->id)
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
     * 获取该目录的URL
     * @return string
     */
    public function getTagsUrl(){
        if(empty($this->uri)){
            return '/tag/view/'.$this->id;
        }else{
            return '/tag/view/'.$this->uri;
        }
    }

    /**
     * 根据给定的URI来取得对应的分类对象
     * @param $uri
     * @return mixed
     */
    public static function GetByUri($uri){
        return self::select('id','name','uri')
            ->where('uri',$uri)
            ->orderBy('id','desc')
            ->first();
    }


    /**
     * 当前产品的数量
     * @return mixed
     */
    public function productsCount(){
        return TagProduct::where('tag_id',$this->id)->count();
    }

}
