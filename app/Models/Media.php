<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Image;
use Ramsey\Uuid\Uuid;
use App\Models\Utils\MediaTool;
use Log;

class Media extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'uuid','type','target_id','url','width','height','size','duration','alt','for'
    ];

    /**
     * @param $targetId
     * @param $type
     * @param $filePath
     * @param string $alt
     * @param int $for
     * @return mixed
     */
    public static function Persistent($targetId, $type, $filePath, $alt='img',$for=0){
        if($type == MediaTool::$TYPE_IMAGE){
            if(isset($filePath['url'])){
                $filePath = $filePath['url'];
            }

            $dimension = self::getImageDimension($filePath);
            if($dimension){
                return self::create([
                    'uuid'=>Uuid::uuid4()->toString(),
                    'type'=>$type,
                    'target_id'=>$targetId,
                    'url'=>$filePath,
                    'width'=>$dimension['width'],
                    'height'=>$dimension['height'],
                    'size'=>$dimension['size'],
                    'duration'=>0,
                    'for'=>$for,
                    'alt'=>$alt
                ]);
            }
        }else{
            return self::create([
                'uuid'=>Uuid::uuid4()->toString(),
                'type'=>$type,
                'target_id'=>$targetId,
                'url'=>$filePath,
                'width'=>0,
                'height'=>0,
                'size'=>MediaTool::GetFileSizeInKB($filePath),
                'duration'=>0,
                'for'=>$for,
                'alt'=>$alt
            ]);
        }
    }

    /**
     * 克隆media对象
     * @param $targetId
     * @param $type
     * @param $filePath
     * @param string $alt
     * @param int $for
     * @return mixed
     */
    public static function DoClone($targetId, $type, $filePath, $alt='img',$for=0){
        if($type == MediaTool::$TYPE_IMAGE){
            $dimension = self::getImageDimension($filePath);
            if($dimension){
                return self::create([
                    'uuid'=>Uuid::uuid4()->toString(),
                    'type'=>MediaTool::$TYPE_IMAGE,
                    'target_id'=>$targetId,
                    'url'=>self::_CopyImage($filePath),
                    'width'=>$dimension['width'],
                    'height'=>$dimension['height'],
                    'size'=>$dimension['size'],
                    'duration'=>0,
                    'for'=>$for,
                    'alt'=>$alt
                ]);
            }
        }
    }

    /**
     * 将给定路径的文件拷贝并返回新路径的方法
     * @param $filePath
     * @return null|string
     */
    private static function _CopyImage($filePath){
        $filePath = str_replace('/storage','',$filePath);
        $exist = Storage::exists($filePath);
        if($exist==true){
            $fileName = _buildUploadFolderPath() .'/'.Uuid::uuid4()->toString().'.jpg';
            Storage::copy(
                $filePath,
                $fileName
            );
            return _buildFrontendAssertPath($fileName);
        }
        return null;
    }

    /**
     * 删除Media
     * @param $id
     * @return bool
     */
    public static function Terminate($id){
        $media = self::find($id);
        if($media){
            $path = $media->url;
            if($media->delete()){
                Storage::delete($path);
                return true;
            }
        }
        return false;
    }

    /**
     * 获得图片的宽,高和文件大小
     * @param $filePath
     * @return array
     */
    public static function getImageDimension($filePath){
        if (is_null($filePath)){
            return null;
        }

        if(is_array($filePath)){
            $filePath = $filePath['url'];
        }

        $filePath = str_replace('/storage','',$filePath);
        $exist = Storage::exists($filePath);
        if($exist){
            $image = Image::make(storage_path('app/public').'/'.$filePath);
            $size = MediaTool::GetFileSizeInKB($filePath);
            return [
                'width'=>$image->width(),
                'height'=>$image->height(),
                'size'=>$size
            ];
        }else{
            Log::info('Error',['path'=>storage_path('app/public').$filePath]);
        }
        return null;
    }



    /**
     * 根据给定的产品ID获取所有图片
     * @param $productId
     * @return mixed
     */
    public static function GetByProduct($productId){
        return self::where('target_id',$productId)
            ->where('type',MediaTool::$TYPE_IMAGE)
            ->where('for',MediaTool::$FOR_PRODUCT)
            ->get();
    }

    /**
     * @param null $classNames
     * @return string
     */
    public function toHtml($classNames = null){
        if($this->type == MediaTool::$TYPE_IMAGE){
            return '<img class="image '.$classNames.'" src="'.$this->url.'" alt="'.$this->alt.'">';
        }
    }
}
