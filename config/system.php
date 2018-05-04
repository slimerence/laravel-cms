<?php
/**
 * System Configuration
 * User: Justin Wang
 * Date: 9/3/18
 * Time: 12:05 AM
 */
return [
    'frontend_theme'=>'default',
    'MAX_UPLOAD_FILE_SIZE'=>1000000,
    'PAGE_SIZE'=>20,
    'CATALOG_TRIGGER_MENU_WIDTH'=>161,
    'CURRENCY'=>'$',
    'layout'=>[
        'container'=>[
            'desktop'=>[
                'styles'=>[
                    'padding'=>'',
                    'max-width'=>''
                ]
            ],
            'mobile'=>[
                'styles'=>[
                    'padding'=>'',
                    'max-width'=>''
                ]
            ]
        ]
    ],
    // 在订单超过这个金额的时候, 免运费
    'ORDER_MIN_TOTAL_FOR_FREE_DELIVERY' => 200,
    // 国内订单运费
    'DOMESTIC_DELIVERY_FEE'=>10,
    'OVERSEA_DELIVERY_FEE=55'=>55,
];