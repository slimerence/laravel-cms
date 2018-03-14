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