<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/3/18
 * Time: 1:53 AM
 */

if(!function_exists('_buildUploadFolderPath')){
    /**
     * 为了避免在单一的目录中保存太多文件,因此根据时间创建目录的路径
     * @return string
     */
    function _buildUploadFolderPath(){
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $hour = date('h');
        $minute = date('i');
        return 'uploads/'.$year.'/'.$month.'/'.$day.'/'.$hour.'/'.$minute;
    }
}

if(!function_exists('_buildFrontendAssertPath')){
    /**
     * 根据保存路径获取Media的前端路径,用来保存到数据库中. 因为 public 目录中依赖 storage symbol Link
     * @param $uploadFolderPath
     * @return string
     */
    function _buildFrontendAssertPath($uploadFolderPath){
        return '/storage/'.$uploadFolderPath;
    }
}

if(!function_exists('_isAFakeMediaId')){
    /**
     * 判断是否给定的 id 对于Media来说是假的,不可能存在的ID
     * @param $id
     * @return bool
     */
    function _isAFakeMediaId($id){
        return strlen($id) == 16;
    }
}

if(!function_exists('_get_frontend_theme_prefix')){
    /**
     * 获得前端的主题文件路径前缀
     * @return \Illuminate\Config\Repository|mixed
     */
    function _get_frontend_theme_prefix(){
        return env('frontend_theme',false) ? env('frontend_theme',false) : config('system.frontend_theme');
    }

    /**
     * 获得前端的主题文件路径
     * @param $path
     * @return \Illuminate\Config\Repository|mixed
     */
    function _get_frontend_theme_path($path){
        // 检查文件是否存在
        $filename = resource_path('views/frontend/').str_replace('.','/',_get_frontend_theme_prefix()).'/'.str_replace('.','/',$path).'.blade.php';

        $finalPath = 'frontend.'.config('system.frontend_theme').'.'.$path;
        if(file_exists($filename)){
            $finalPath = 'frontend.'._get_frontend_theme_prefix().'.'.$path;
        }

        if(env('APP_DEBUG', false)){
            // Log layout path file if in APP_DEBUG mode
            \Illuminate\Support\Facades\Log::info('Theme: '.$path, ['location'=>$finalPath]);
        }

        return $finalPath;
    }

    /**
     * 获得前端的主题文件路径前缀
     * @return \Illuminate\Config\Repository|mixed
     */
    function _get_frontend_layout_prefix(){
        return env('frontend_theme',false) ? env('frontend_theme',false) : '';
    }

    /**
     * 获得前端的主题文件路径
     * @param $path
     * @return string
     */
    function _get_frontend_layout_path($path){
//        $filename = resource_path('views/layouts/').str_replace('.','/',_get_frontend_layout_prefix()).'/'.str_replace('.','/',$path).'.blade.php';
        $filename = resource_path('views/frontend/').str_replace('.','/',_get_frontend_layout_prefix()).'/layouts/'.str_replace('.','/',$path).'.blade.php';

        $finalPath = 'layouts.'.$path;
        if(file_exists($filename)){
            if(strlen(_get_frontend_layout_prefix()) > 0){
                $finalPath = 'frontend.'._get_frontend_layout_prefix().'.layouts.'.$path;
            }
        }

        if(env('APP_DEBUG', false)){
            // Log layout path file if in APP_DEBUG mode
            \Illuminate\Support\Facades\Log::info('Layout: '.$path, ['location'=>$finalPath]);
        }

        return $finalPath;
    }
}