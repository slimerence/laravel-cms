<?php

namespace App\Models\Widget;

use App\Models\Media;
use App\Models\Utils\MediaTool;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'gallery_id',
        'media_id',
        'type',
        'wrapper_classes',
        'caption',
        'extra_html',
        'position',
    ];

    public function gallery(){
        return $this->belongsTo(Gallery::class);
    }

    public function media(){
        return $this->belongsTo(Media::class);
    }

    /**
     * 根据指定的 gallery id 加载所有的关联图片
     * @param $galleryId
     * @return mixed
     */
    public static function LoadImages($galleryId){
        $galleryImages = self::where('gallery_id',$galleryId)->orderBy('position','asc')->get();
        return $galleryImages;
    }

    /**
     * 删除 Gallery Item 记录
     * @param $id
     * @return mixed
     */
    public static function Terminate($id){
        self::where('id',$id)->delete();
        Media::where('target_id',$id)
            ->where('for',MediaTool::$FOR_GALLERY)
            ->delete();
    }
}
