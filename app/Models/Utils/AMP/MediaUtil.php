<?php
/**
 * Ref: https://www.ampproject.org/docs/media/amp_replacements
 * 输出 图片,视频和音频的工具类
 */
namespace App\Models\Utils\AMP;

class MediaUtil
{
    const SUPPORT_NO_JS = false;
    const AMP_IMAGE_TAG = 'amp-img';
    const AMP_VIDEO_TAG = 'amp-video';
    const AMP_AUDIO_TAG = 'amp-audio';

    /**
     * 输出 AMP Image 的标签
     * @param $src
     * @param $alt
     * @param $width
     * @param $height
     * @param null $class
     * @param null $id
     * @return string
     */
    public static function Image($src, $alt, $width, $height, $class = null, $id = null){
        return '<'.self::AMP_IMAGE_TAG.' layout="responsive" src="'.
            $src.'" alt="'.
            $alt.'" height="'.
            $height.'" width="'.
            $width.'" class="'.
            $class.'" id="'.
            $id.'">'.
            (self::SUPPORT_NO_JS ? self::NormalImage($src, $alt, $width, $height, $class, $id) : null)
            .'</'.self::AMP_IMAGE_TAG.'>';
    }

    /**
     * 输出 AMP Video 的标签
     * @param $src
     * @param $poster
     * @param $width
     * @param $height
     * @param null $class
     * @param null $id
     * @param bool $needControls
     * @return string
     */
    public static function Video($src, $poster, $width, $height, $class = null, $id = null, $needControls = true){
        return '<'.self::AMP_VIDEO_TAG.' '.($needControls ? 'controls ' : null).'src="'.
        $src.'" poster="'.
        $poster.'" height="'.
        $height.'" width="'.
        $width.'" class="'.
        $class.'" id="'.
        $id.'"><div fallback><p>This browser does not support the video element.</p></div></'.self::AMP_VIDEO_TAG.'>';
    }

    /**
     * 输出 AMP Audio 的标签
     * @param $src
     * @param $width
     * @param $height
     * @param null $class
     * @param null $id
     * @param null $sourceMpeg
     * @param null $sourceOgg
     * @return string
     */
    public static function Audio($src, $width, $height, $class = null, $id = null, $sourceMpeg = null, $sourceOgg = null){
        $html = '<'.self::AMP_VIDEO_TAG.' src="'.
        $src.'" height="'.
        $height.'" width="'.
        $width.'" class="'.
        $class.'" id="'.
        $id.'"><div fallback><p>Your browser does not support HTML5 audio.</p></div>';
        if($sourceMpeg){
            $html .= '<source type="audio/mpeg" src="'.$sourceMpeg.'">';
        }
        if($sourceOgg){
            $html .= '<source type="audio/ogg" src="'.$sourceOgg.'">';
        }
        return $html.'</'.self::AMP_VIDEO_TAG.'>';
    }

    /**
     * 正常的Image标签, 用来在不支持js的时候显示
     * @param $src
     * @param $alt
     * @param $width
     * @param $height
     * @param null $class
     * @param null $id
     * @return string
     */
    public static function NormalImage($src, $alt, $width, $height, $class = null, $id = null){
        return '<img alt="'.$alt.'" src="'.$src.'" width="'.$width.'" height="'.$height.'" class="'.$class.'" id="'.$id.'" />';
    }
}
