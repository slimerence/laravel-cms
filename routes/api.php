<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('page')->group(function(){
    // Save Page
    Route::post('save','Api\Pages@save');
    Route::post('is-uri-unique','Api\Pages@is_uri_unique');
    Route::post('search_ajax','Api\Pages@search_ajax');
});

// 为上传图片提供的接口
Route::prefix('images')->group(function(){
    // 保存图片
    Route::post('upload','Api\Medias@upload_ajax');

    // 加载所有的图片
    Route::get('load-all','Api\Medias@load_all');
    // 加载指定产品的图片
    Route::post('product','Api\Medias@load_by_product');
    // 删除图片
    Route::post('delete','Api\Medias@delete_ajax');
});

Route::prefix('files')->group(function(){
    // 保存图片
    Route::post('upload','Api\Medias@upload_file_ajax');
    // 加载所有的图片
    Route::get('load-all','Api\Medias@load_all_files');
});

/**
 * Slider 相关的接口
 */
Route::prefix('sliders')->group(function(){
    Route::get('loadAll','Api\Sliders@load_all');
    Route::get('load/{id}','Api\Sliders@load_slider');
    Route::get('load-slider-images/{id}','Api\Sliders@load_slider_images');
    Route::get('load-slider-images/{id}','Api\Sliders@load_slider_images');
    Route::post('save','Api\Sliders@save');
    Route::post('save-slider-image','Api\Sliders@save_slider_image');
    Route::post('delete','Api\Sliders@delete');
});

/**
 * Gallery 相关的接口
 */
Route::prefix('galleries')->group(function(){
    Route::get('loadAll','Api\Galleries@load_all');
    Route::get('load/{id}','Api\Galleries@load_gallery');
    Route::get('load-slider-images/{id}','Api\Galleries@load_slider_images');
    Route::post('save','Api\Galleries@save');
    Route::post('save-gallery-image','Api\Galleries@save_gallery_image');
    Route::post('delete','Api\Galleries@delete');
    Route::get('delete_gallery_item/{id}','Api\Galleries@delete_gallery_item');
});

Route::prefix('widgets')->group(function(){
    Route::post('is-uri-unique','Api\ShortCodes@is_uri_unique');
    Route::get('load-short-codes','Api\ShortCodes@load_short_codes');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * 分类目录相关api接口组
 */
Route::prefix('category')->group(function(){
    // Save Category
    Route::post('save','Api\Categories@save');
    // Delete Category
    Route::post('delete','Api\Categories@delete');
    // 为加载目录树的接口
    Route::get('tree','Api\Categories@tree');
    // 为了菜单显示目录详情用
    Route::get('load-nav/{uuid}','Api\Categories@load_nav');
    // 和品牌相关
    Route::get('load-brands/{categoryId?}','Api\Brands@load_all');
});

/**
 * 产品相关api接口组
 */
Route::prefix('products')->group(function(){
    Route::post('save','Api\Products@save');
    Route::post('clone','Api\Products@clone_product');
    Route::post('delete','Api\Products@delete');
    Route::post('options/load','Api\Products@load_options_ajax');
    Route::post('options/delete','Api\Products@delete_options_ajax');
    Route::post('colour/delete','Api\Products@delete_colour_ajax');
    Route::post('option_item/delete','Api\Products@delete_option_item_ajax');
    Route::post('ajax_search','Api\Products@ajax_search');
    Route::post('ajax_search_for_group','Api\Products@ajax_search_for_group');
    Route::post('confirm-to-add-new-product','Api\Products@save_group_product');
    Route::post('rm-group-product','Api\Products@delete_group_product');
    Route::get('get-group-products','Api\Products@get_group_products');
});

// 加载产品属性的记录
Route::prefix('product-attributes')->group(function(){
    Route::post('load','Api\AttributeSet@load_product_attribute_ajax');
    Route::post('save','Api\AttributeSet@save_product_attribute_ajax');
    Route::post('set-to-load','Api\AttributeSet@load_attributes_by_set_ajax');
});

// 支付API的回调接口
Route::prefix('payment')->group(function(){
//    Route::get('weixin/notify','Api\Payment@weixin_notify');
//    Route::get('weixin/success','Api\Payment@weixin_success');
});

// Brand的功能接口
Route::prefix('brands')->group(function(){
    Route::post('load-by-name','Api\Brands@load_by_name');
    Route::post('save-serial','Api\Brands@save_serial');
    Route::get('get-serial','Api\Brands@get_serial');
    Route::get('delete-serial','Api\Brands@delete_serial');
});
Route::prefix('group-products')->group(function(){
//    Route::post('search-name','Api\Brands@load_by_name');
//    Route::post('save-serial','Api\Brands@save_serial');
//    Route::get('get-serial','Api\Brands@get_serial');
//    Route::get('delete-serial','Api\Brands@delete_serial');
});