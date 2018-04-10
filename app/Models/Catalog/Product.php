<?php

namespace App\Models\Catalog;

use App\Models\Catalog\Product\AttributeValue;
use App\Models\Catalog\Product\Colour;
use App\Models\Catalog\Product\ProductAttribute;
use App\Models\Catalog\Product\ProductAttributeSet;
use App\Models\Catalog\Product\ProductOption;
use App\Models\Group;
use App\Models\Utils\MediaTool;
use App\Models\Utils\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use DB;
use App\Models\Media;
use Storage;
use App\Models\Utils\ContentTool;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uuid','name','sku',
        'type',
        'attribute_set_id',
        'min_quantity',
        'manage_stock',
        'stock',
        'promote',
        'position',
        'group_id',
        'uri',
        'default_price',
        'special_price',
        'tax',
        'unit_text',
        'image_path',
        'short_description',
        'description',
        'keywords',
        'seo_description',
        'brand'
    ];

    /**
     * 关联的Group
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(){
        return $this->belongsTo(Group::class);
    }

    /**
     * 产品的颜色
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function colours(){
        return $this->hasMany(Colour::class);
    }

    /**
     * @param bool $asArray
     * @return array
     */
    public static function GetFeatureProducts($asArray = false){
        $feature = Category::select('id','name')
            ->where('name','Feature Products')
            ->orderBy('id','asc')
            ->first();
        if($asArray){
            return $feature ? $feature->productCategories()->toArray() : [];
        }
        return $feature ? $feature->productCategories() : [];
    }

    /**
     * 列出通用产品
     * @return mixed
     */
    public static function GetAllGeneralItems(){
        return self::where('type',ProductType::$GENERAL_ITEM)
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();
    }

    /**
     * 列出某个 Group 的产品
     * @param $groupId
     * @return mixed
     */
    public static function GetGroupSpecified($groupId){
        return self::where('type',ProductType::$GROUP_SPECIFIED)
            ->where('group_id',$groupId)
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();
    }

    /**
     * 根据产品的URI查询. 如果没有找到, 主动尝试通过uuid再试试
     * @param $uri
     * @return mixed
     */
    public static function GetByUri($uri){
        $product = self::where('uri',$uri)->first();
        if(!$product){
            $product = self::GetByUuid($uri);
        }
        return $product;
    }

    /**
     * 产品所属的AttributeSet
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attributeSet(){
        return $this->belongsTo(ProductAttributeSet::class);
    }

    /**
     * 获取当前产品的属性列表
     * @return mixed
     */
    public function productAttributes(){
        return ProductAttribute::where('product_attribute_set_id', $this->attribute_set_id)
            ->orderBy('position','asc')
            ->orderBy('name','asc')
            ->get();
    }

    /**
     * 产品的Options
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options(){
        return ProductOption::where('product_id',$this->id)
            ->orderBy('position','asc')
            ->get();
    }

    /**
     * 该产品是否有 options
     * @return bool
     */
    public function hasOptions(){
        return ProductOption::select('id')
            ->where('product_id',$this->id)
            ->count() > 0;
    }

    /**
     * 从数据库中删除产品的所有信息
     * @param $uuid
     * @return bool
     */
    public static function Terminate($uuid){
        $product = self::GetByUuid($uuid);
        if($product){
            DB::beginTransaction();
            try{
                // 删除对应该产品的目录关联关系
                CategoryProduct::where('product_id',$product->id)->delete();

                ProductOption::TerminateByProduct($product->id);

                AttributeValue::where('product_id',$product->id)->delete();

                // 删除所有关联的多媒体文件
                $medias = Media::where('target_id',$product->id)->get();
                foreach ($medias as $media) {
                    Media::Terminate($media->id);
                }

                // 删除颜色相关数据
                Colour::where('product_id',$product->id)->delete();
                $product->delete();

                DB::commit();
            }catch (\Exception $e){
                DB::rollback();
                return false;
            }
        }
        return true;
    }

    /**
     * 克隆产品的方法
     * @param $productData
     * @param $images
     * @param $categories
     * @param $productOptionsData
     * @param $productAttributeData
     * @param $productColours           // 产品的颜色
     * @return mixed
     */
    public static function DoClone($productData, $images, $categories, $productOptionsData, $productAttributeData,$productColours){
        $result = false;
        $productData = ContentTool::RemoveNewLine($productData);

        DB::beginTransaction();
        if(empty($productData['id'])){
            $productData['uuid'] = Uuid::uuid4()->toString();
            $randomString = str_random(8);
            $productData['name'] = $productData['name'].' - CLONE-'. $randomString;
            $productData['uri'] = $productData['uri'].'-'. $randomString;

            $product = self::create($productData);
            if($product){
                // 处理图片的操作
                if($images && is_array($images) && count($images)>0){
                    // 处理图片: 如果有id,就什么也不用干,因为根本没有修改图片的功能提供;
                    // 如果没ID, 就添加
                    foreach ($images as $image) {
                        Media::DoClone($product->id, MediaTool::$TYPE_IMAGE, $image['url'], $product->name, MediaTool::$FOR_PRODUCT);
                    }
                }else{
                    // 没有上传图片,则使用默认的
                    $product->image_path = '/uploads/default.png';
                }

                // 处理分类
                if($categories && is_array($categories)){
                    foreach ($categories as $categoryId) {
                        CategoryProduct::create(
                            [
                                'product_id'=>$product->id,
                                'category_id'=>$categoryId,
                                'product_name'=>$product->name,
                                'position'=>$product->position,
                                'price'=>$product->default_price
                            ]
                        );
                    }
                }
                // 处理产品的附加选项
                if($productOptionsData && is_array($productOptionsData)){
                    foreach ($productOptionsData as $productOption) {
                        $productOption['id'] = null;
                        ProductOption::DoClone($product, $productOption);
                    }
                }

                // 处理产品的属性数据集合
                if($productAttributeData && is_array($productAttributeData)){
                    foreach ($productAttributeData as $productAttributeValueData) {
                        AttributeValue::Persistent($product, $productAttributeValueData);
                    }
                }

                // 处理产品的颜色
                if($productColours && is_array($productColours)){
                    foreach ($productColours as $productColourData) {
                        Colour::DoClone($product, $productColourData);
                    }
                }

                $result = $product->save();
            }
        }


        if($result){
            DB::commit();
            $result = $product;
        }else{
            DB::rollback();
        }
        return $result;
    }

    /**
     * @param $productData
     * @param $images
     * @param $categories
     * @param $productOptionsData   // 产品的附加选项
     * @param $productAttributeData   // 产品的属性数据集合
     * @param $productColours   // 产品的颜色数据集合
     * @return bool
     */
    public static function Persistent($productData, $images, $categories, $productOptionsData, $productAttributeData, $productColours=[]){
        $result = false;
        $productData = ContentTool::RemoveNewLine($productData);

        DB::beginTransaction();
        if(isset($productData['id']) && !empty($productData['id'])){
            /**
             * 更新产品的操作
             * 由于产品更新界面前台的处理, 在更新产品的时候,对产品的图片, 产品的Option和 Option的Items
             * 采用的处理方式为: 检查,如果有id就更新,如果没有id就添加。 凡是在前端删除的, 已经在服务器删除了, 并且不会被传到这里
             */
            $product = self::find($productData['id']);
            if($product){
                unset($productData['id']);
                foreach ($productData as $fieldName => $fieldValue) {
                    $product->$fieldName = $fieldValue;
                }

                $result = $product->save();

                if($images && is_array($images) && count($images)>0){
                    // 处理图片: 如果有id,就什么也不用干,因为根本没有修改图片的功能提供;
                    // 如果没ID, 就添加
                    foreach ($images as $key=>$image) {
                        if(!isset($image['id']) || empty($image['id'])){
                            Media::Persistent($product->id, MediaTool::$TYPE_IMAGE, $image['url'], $product->name, MediaTool::$FOR_PRODUCT);
                        }else{
                            if(_isAFakeMediaId($image['id'])){
                                Media::Persistent($product->id, MediaTool::$TYPE_IMAGE, $image, $product->name, MediaTool::$FOR_PRODUCT);
                            }
                        }
                    }
                }

                // 处理分类
                if($categories && is_array($categories)){
                    // 先把原有的删除
                    CategoryProduct::where('product_id',$product->id)->delete();
                    // 把提交的从新添加进去
                    foreach ($categories as $categoryId) {
                        CategoryProduct::create(
                            [
                                'product_id'=>$product->id,
                                'category_id'=>$categoryId,
                                'product_name'=>$product->name,
                                'position'=>$product->position,
                                'price'=>$product->default_price
                            ]
                        );
                    }
                }
                // 处理产品的附加选项
                if($productOptionsData && is_array($productOptionsData)){
                    // 处理附加选项: 如果有id,就更新;
                    // 如果没ID, 就添加
                    foreach ($productOptionsData as $productOption) {

                        ProductOption::Persistent($product, $productOption);

//                        $optionSaved = false;
//                        if(empty($productOption['id'])){
//                            $optionSaved = ProductOption::Persistent($product, $productOption);
//                        }else{
//                            $pOption = ProductOption::find($productOption['id']);
//                            if($pOption){
//                                // 更新 Product option的记录
//                                $pOption->name = $productOption['name'];
//                                $pOption->type = $productOption['type'];
//                                $optionSaved = $pOption->save();
//                            }
//                        }
//                        if($optionSaved){
//                            // 选项被保存了。 可能是新增, 可能是更新, 但是无所谓
//                        }
                    }
                }
                // 处理产品的属性数据集合
                if($productAttributeData && is_array($productAttributeData)){
                    foreach ($productAttributeData as $productAttributeValueData) {
                        AttributeValue::Persistent($product, $productAttributeValueData);
                    }
                }

                // 处理产品的颜色
                if($productColours && is_array($productColours)){
                    foreach ($productColours as $productColourData) {
                        Colour::Persistent($product, $productColourData);
                    }
                }
            }
        }else{
            // 添加产品的操作
            $productData['uuid'] = Uuid::uuid4()->toString();
            $product = self::create($productData);
            if($product){
                // 检查 $images 是否其内部元素为数组, 如果是数组,则要调整为字符串
                $imagesData = $images;
                if(isset($imagesData[0]) && is_array($imagesData[0])){
                    foreach ($imagesData as $idx=>$image) {
                        $images[$idx] = $image['url'];
                    }
                }
                // 处理图片的操作
                if($images && is_array($images) && count($images)>0){
                    foreach ($images as $key=>$imageUrl) {
                        $media = Media::Persistent($product->id, MediaTool::$TYPE_IMAGE, $imageUrl, $product->name, MediaTool::$FOR_PRODUCT);
                        if($key == 0 && $media){
                            $product->image_path = $imageUrl;
                        }
                    }
                }else{
                    // 没有上传图片,则使用默认的
                    $product->image_path = '/uploads/default.png';
                }
                // 处理分类
                if($categories && is_array($categories)){
                    foreach ($categories as $categoryId) {
                        CategoryProduct::create(
                            [
                                'product_id'=>$product->id,
                                'category_id'=>$categoryId,
                                'product_name'=>$product->name,
                                'position'=>$product->position,
                                'price'=>$product->default_price
                            ]
                        );
                    }
                }
                // 处理产品的附加选项
                if($productOptionsData && is_array($productOptionsData)){
                    foreach ($productOptionsData as $productOption) {
                        ProductOption::Persistent($product, $productOption);
                    }
                }

                // 处理产品的属性数据集合
                if($productAttributeData && is_array($productAttributeData)){
                    foreach ($productAttributeData as $productAttributeValueData) {
                        AttributeValue::Persistent($product, $productAttributeValueData);
                    }
                }

                // 处理产品的颜色
                if($productColours && is_array($productColours)){
                    foreach ($productColours as $productColourData) {
                        Colour::Persistent($product, $productColourData);
                    }
                }
                $result = $product->save();
            }
        }


        if($result){
            DB::commit();
            $result = $product;
        }else{
            DB::rollback();
        }
        return $result;
    }

    /**
     * 获取产品的URL
     * @return string
     */
    public function getProductUrl(){
        if(empty($this->uri)){
            return $this->uuid;
        }else{
            return $this->uri;
        }
    }

    /**
     * 获取产品的所有其他价格
     * @param bool $availablePricesOnly
     * @return array
     */
    public function getPrices($availablePricesOnly = true){
        $prices = [];
        $otherPrices = Price::getByProduct($this);
        foreach ($otherPrices as $otherPrice) {
            if($availablePricesOnly){
                if($otherPrice->isAvailableNow()){
                    $prices[] = [
                        $otherPrice
                    ];
                }
            }else{
                $prices[] = [
                    $otherPrice
                ];
            }
        }
        return $prices;
    }

    /**
     * 获取产品最终价格
     * @return bool|string
     */
    public function getFinalPriceGst(){
        return $this->getSpecialPriceGST() ? $this->getSpecialPriceGST() : $this->getDefaultPriceGST();
    }

    /**
     * 获取产品的折扣价格的方法
     * @return bool|string
     */
    public function getSpecialPriceGST(){
        if($this->special_price && $this->special_price > 0){
            return number_format(((100 + $this->tax)/100) * $this->special_price, 2);
        }
        return false;
    }

    /**
     * 获取产品的折扣价格的方法
     * @return bool|string
     */
    public function getDefaultPriceGST(){
        if($this->default_price && $this->default_price > 0){
            return number_format(((100 + $this->tax)/100) * $this->default_price, 2);
        }
        return false;
    }

    /**
     * 取得所归属的目录信息
     * @return array
     */
    public function categories(){
        $categoriesId = $this->getCategoriesId();
        if(count($categoriesId)>0){
            return Category::whereIn('id',$categoriesId)->orderBy('position','ASC')->orderBy('id','DESC')->get();
        }else{
            return null;
        }
    }

    /**
     * 获取所关联的category的id数组
     * @return array
     */
    public function getCategoriesId(){
        $cps = CategoryProduct::select('category_id')->where('product_id',$this->id)->get();
        $categoriesId = [];
        if(count($cps)>0){
            foreach ($cps as $cp) {
                $categoriesId[] = $cp->category_id;
            }
        }
        return $categoriesId;
    }

    /**
     * 根据给定的UUID获取产品对象
     * @param $uuid
     * @return Product
     */
    public static function GetByUuid($uuid){
        return self::where(
            'uuid',
            ProductType::RestoreProductUuidWithoutTail($uuid))
            ->first();
    }

    /**
     * 根据给定的ID获取所有的产品图片
     * @param $uuid
     * @return null
     */
    public static function GetAllImages($uuid){
        $product = self::GetByUuid($uuid);
        if($product){
            return Media::GetByProduct($product->id);
        }else{
            return null;
        }
    }

    /**
     * 获取当前产品对象的所有图片
     * @return mixed
     */
    public function get_AllImages(){
        return Media::GetByProduct($this->id);
    }

    /**
     * 获取产品缺省图片的url
     * @return mixed
     */
    public function getProductDefaultImage(){
        $images = $this->get_AllImages();
        $defaultImage = null;
        if($images){
            foreach ($images as $key => $image){
                $defaultImage = $image;
                if ($key==0)
                    break;
            }
        }
        return $defaultImage;
    }

    /**
     * 获取产品缺省图片的url
     * @return mixed
     */
    public function getProductDefaultImageUrl(){
        $defaultImage = $this->getProductDefaultImage();

        return $defaultImage ? $defaultImage->url : Storage::url('/uploads/default.png');
    }

    /**
     * 根据图片的尺寸和比例给出一个css class 字符串
     * @param bool $fixHeight
     * @return null|string
     */
    public function getProductThumbnailClass($fixHeight=true){
        $result = null;
        if($fixHeight){
            $img = $this->getProductDefaultImage();
            if($img){
                $result = $img->width <= $img->height ? 'fix-height' : 'fix-width';
            }
        }
        return $result;
    }

    /**
     * 加载和当前产品相关的产品的方法
     * @param bool $idAndNameOnly
     * @return mixed
     */
    public function loadRelatedProducts($idAndNameOnly = false){
        return $this->relatedProduct ? $this->relatedProduct->load($idAndNameOnly) : [];
    }

    /**
     * 获取关联产品的对象
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function relatedProduct(){
        return $this->hasMany(RelatedProduct::class);
    }
}
