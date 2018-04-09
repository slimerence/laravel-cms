<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'logo',
        'multi_language',
        'contact_phone',
        'contact_email',
        'contact_fax',
        'contact_address',
        'contact_person',
        'embed_map_code',
        'twitter',
        'google_plus',
        'instagram',
        'linked_in',
        'facebook',
        'latitude',
        'longitude',
        'menu_bar_color',
        'menu_bar_color_hover',
        'theme_main_color',
    ];

    public function isContactUsField($key){
        return in_array($key, self::GetContactUsFields());
    }

    public function isSocialNetworkField($key){
        return in_array($key, self::GetSocialNetworkFields());
    }

    protected $casts = [
        'multi_language' => 'boolean',
    ];

    public function getFillableArray(){
        return $this->fillable;
    }

    public static function GetContactUsFields(){
        return [
            'contact_phone',
            'contact_email',
            'contact_fax',
            'contact_address',
            'contact_person',
        ];
    }

    public static function GetSocialNetworkFields(){
        return [
            'twitter',
            'google_plus',
            'instagram',
            'linked_in',
            'facebook',
        ];
    }
}
