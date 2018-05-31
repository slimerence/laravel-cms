<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Frontend\Pages@index')->name('home');
Route::get('/contact-us', 'Frontend\Pages@contact_us')->name('contact_us');
Route::post('/contact-us', 'Frontend\Pages@contact_us_handler');
Route::get('/terms', 'Frontend\Pages@terms')->name('terms');

// 特定的URI
Route::prefix('page')->group(function(){
    Route::get('/blog', 'Frontend\Pages@blog');
    Route::get('/blog/{uri}', 'Frontend\Pages@blog_view');
    Route::get('/news', 'Frontend\Pages@news');
    Route::get('/news/{uri}', 'Frontend\Pages@news_view');

    // 查看某个独立页面内容的路由
    Route::get('/{uri}', 'Frontend\Pages@view');
});


// 加载产品目录的内容
Route::get('/category/view/{uri}', 'Frontend\Categories@view');
Route::post('/products/add_to_cart','Frontend\ShoppingCartController@add_to_cart');
Route::get('/view_cart','Frontend\ShoppingCartController@view_cart');
Route::post('/cart/remove','Frontend\ShoppingCartController@remove_item');
Route::post('/checkout','Frontend\ShoppingCartController@prepare_checkout');

Route::prefix('catalog')->group(function(){
    Route::get('product/{uri}', 'Frontend\Products@view');
    Route::get('brand/load', 'Frontend\Products@view_by_brand');
});

// 前端页面显示相关路由组
Route::prefix('frontend')->group(function () {
    // 用户登录与注册
    Route::post('customer/is_email_exist', 'Frontend\CustomersController@is_email_exist');
    Route::get('customers/login', 'Frontend\CustomersController@login');
    Route::post('customers/login', 'Frontend\CustomersController@login_check');
    Route::get('customers/forget-password', 'Frontend\CustomersController@forget_password');
    Route::get('customers/register', 'Frontend\CustomersController@register');
    Route::post('customer/register', 'Frontend\CustomersController@save');
    Route::post('customer/quick-checkout-register', 'Frontend\CustomersController@quick_checkout_register');
    Route::get('wholesalers/register', 'Frontend\CustomersController@register_wholesale');
    Route::post('wholesalers/register', 'Frontend\CustomersController@save_wholesale');

    Route::get('place_order_checkout','Frontend\CheckoutController@place_order_checkout')
        ->name('customer.checkout');
    Route::post('place_order_checkout','Frontend\CheckoutController@place_order_checkout');

    Route::get('my_orders/{userUuid?}/{clearCart?}','Frontend\Orders@my_orders');
    // 客户的个人档案
    Route::get('my_profile/{userUuid?}','Frontend\CustomersController@my_profile');
    Route::post('my_profile/{userUuid?}','Frontend\CustomersController@my_profile');
    Route::post('update_password','Frontend\CustomersController@update_password');

    Route::get('view_order/{userUuid}/{orderUuid}','Frontend\Orders@view_order');

    // FC 用来管理某个订单的路由
    Route::get('approve_order/{userUuid}/{orderUuid}','Frontend\Orders@approve');
    Route::get('decline_order/{userUuid}/{orderUuid}','Frontend\Orders@decline');
    Route::post('decline_order/{userUuid}/{orderUuid}','Frontend\Orders@decline'); // 通过Ajax的方式拒绝订单的路由
});

Auth::routes();
//Route::get('admin_admin','');

Route::prefix('backend')->middleware('auth')->group(function(){
    // 联系的 Leads 列表
    Route::get('leads','Backend\Pages@leads');
    Route::get('leads/delete/{id}','Backend\Pages@lead_delete');

    // 目录相关
    Route::get('menus/index','Backend\Menus@index');
    Route::get('menus/add','Backend\Menus@add');
    Route::get('menus/edit/{id}','Backend\Menus@edit');
    Route::get('menus/delete/{id}','Backend\Menus@delete');
    Route::post('menus/save','Backend\Menus@persistent');

    // 静态页相关
    Route::get('pages/index','Backend\Pages@index');
    Route::get('pages/add','Backend\Pages@add');
    Route::get('pages/edit/{id}','Backend\Pages@edit');
    Route::get('pages/delete/{id}','Backend\Pages@delete');
    Route::post('pages/save','Backend\Pages@persistent');

    // 博客内容相关
    Route::get('blog/index','Backend\Blog@index');
    Route::get('blog/add','Backend\Blog@add');
    Route::get('blog/edit/{id}','Backend\Blog@edit');
    Route::get('blog/delete/{id}','Backend\Blog@delete');
    Route::post('blog/save','Backend\Blog@persistent');

    // 新闻相关
    Route::get('news/index','Backend\Press@index');
    Route::get('news/add','Backend\Press@add');
    Route::get('news/edit/{id}','Backend\Press@edit');
    Route::get('news/delete/{id}','Backend\Press@delete');
    Route::post('news/save','Backend\Press@persistent');

    // Widgets
    Route::get('widgets/sliders','Backend\Widgets@list_sliders');
    Route::get('widgets/blocks','Backend\Widgets@list_blocks');
    Route::get('blocks/add','Backend\Widgets@add_block');
    Route::get('blocks/edit/{id}','Backend\Widgets@edit_block');
    Route::get('blocks/delete/{id}','Backend\Widgets@delete_block');
    Route::post('blocks/save','Backend\Widgets@save_block');
    Route::get('widgets/galleries','Backend\Widgets@list_galleries');

    // 网站设置
    Route::post('configuration/save','Backend\Home@save_config');

    /**
     * 以下为电商相关内容的管理
     */
    // 网站的产品目录管理
    Route::get('categories', 'Backend\Categories@index')->name('categories');
    // 运费管理
    Route::get('shipment', 'Backend\Shipment@index')->name('shipment_manager');
    Route::get('shipment/add', 'Backend\Shipment@add');
    Route::post('shipment/save', 'Backend\Shipment@save');
    Route::get('shipment/edit/{id}', 'Backend\Shipment@edit');
    Route::get('shipment/delete/{uuid}', 'Backend\Shipment@delete');

    // 网站的产品管理
    Route::get('products', 'Backend\Products@index')->name('products');
    Route::get('products/add', 'Backend\Products@add');
    Route::get('products/edit/{id}', 'Backend\Products@edit');
    Route::get('products/delete/{uuid}', 'Backend\Products@delete');
    Route::get('products/related/{uuid}', 'Backend\Products@related');
    Route::post('products/save-related-products', 'Backend\Products@save_related_products');

    // 网站组合产品
    Route::get('group-products/add', 'Backend\GroupProducts@add');

    /**
     * 管理某个属性集的路由
     */
    Route::get('attribute-sets', 'Backend\AttributeSet@index')->name('attribute_set');
    Route::get('attribute-sets/add', 'Backend\AttributeSet@add');
    Route::post('attribute-sets/save', 'Backend\AttributeSet@save');
    Route::get('attribute-sets/edit/{id}', 'Backend\AttributeSet@edit');
    Route::get('attribute-sets/delete/{id}', 'Backend\AttributeSet@delete');
    Route::get('attribute-sets/listing/{id}/{productAttributeId?}', 'Backend\AttributeSet@listing');
    Route::get('attribute-sets/delete-product-attribute/{id}', 'Backend\AttributeSet@delete_product_attribute');

    /**
     * 客户管理
     */
    Route::get('customers','Backend\Customers@index')->name('customers');
    Route::get('customers/add','Backend\Customers@add');
    Route::get('users/add','Backend\Users@add');
    Route::get('customers/edit/{id}','Backend\Customers@edit');
    Route::get('customers/delete/{id}','Backend\Customers@delete');
    Route::get('users/delete/{id}','Backend\Users@delete');
    Route::get('users/edit/{id}','Backend\Users@edit');
    Route::post('customers/save','Backend\Customers@save');
    Route::post('users/save','Backend\Users@save');

    Route::get('system-users','Backend\Users@index')->name('system-users');
    Route::get('update-password','Backend\Users@update_password');
    Route::post('update-password','Backend\Users@update_password_handler');
    Route::get('wechat-config','Backend\AttributeSet@index');

    /**
     * 订单管理的路由
     */
    Route::get('orders', 'Backend\Orders@my_orders')->name('orders');
    Route::get('orders/view/{orderId}', 'Backend\Orders@view');
    Route::post('orders/ajax_search', 'Backend\Orders@ajax_search');
    Route::get('orders/ajax_issue_invoice/{id}', 'Backend\Orders@ajax_issue_invoice');  // 切换订单的状态到发票已开的状态

    /**
     * 合作经销商管理
     */
    Route::get('groups', 'Backend\Groups@index')->name('groups');
    Route::get('groups/add', 'Backend\Groups@add');
    Route::get('groups/edit/{id}', 'Backend\Groups@edit');
    Route::get('groups/delete/{id}', 'Backend\Groups@delete');
    Route::post('groups/save','Backend\Groups@save');

    /**
     * 品牌管理
     */
    Route::get('brands', 'Backend\Brands@index')->name('brands');
    Route::get('brands/add', 'Backend\Brands@add');
    Route::get('brands/edit/{id}', 'Backend\Brands@edit');
    Route::get('brands/delete/{id}', 'Backend\Brands@delete');
    Route::post('brands/save','Backend\Brands@save');
});

Route::get('/home', 'Backend\Home@index');
