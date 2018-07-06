<?php

namespace App;

use App\Models\Group;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserGroup;
use Stripe\Stripe;
use Stripe\Customer;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const ERROR_CODE_EMAIL_UNIQUE       = 70;       // Email字段为unique
    const ERROR_CODE_EMAIL_REQUIRED     = 71;       // Email字段为必须
    const ERROR_CODE_CREATE_NEW_FAILED  = 72;       // 创建新用户记录失败

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role','phone','fax',
        'address','city','postcode','state','country','uuid','group_id','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function GetByUuid($uuid){
        return self::where('uuid',$uuid)->first();
    }

    public static function GetByEmail($email){
        return self::where('email',$email)->first();
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups(){
        return $this->hasMany(UserGroup::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function addressText(){
        return $this->address.', '.$this->city.' '.$this->postcode.
        ', '.$this->state. ', '.$this->country;
    }

    /**
     * 获取客户关联的 stripe customer 信息
     * @return null|\Stripe\StripeObject
     */
    public function getStripeCustomer(){
        if(is_null($this->stripe_id)){
            return null;
        }else{
            return Customer::retrieve($this->stripe_id);
        }
    }

    /**
     * 创建一个和当前 user 相关联的 stripe customer 对象
     * @param $source
     * @return \Stripe\ApiResource
     */
    public function createStripeCustomer($source){
        $customer = Customer::create([
            'source'=>$source,
            'description'=>$this->name,
            'email'=>$this->email
        ]);
        if($customer){
            $this->stripe_id = $customer->id;
        }
        return $customer;
    }
}
