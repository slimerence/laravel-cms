<?php
namespace App\Models\Utils\Payment\RoyalPay\Lib\Data;
/**
 *
 * 数据对象基础类，该类中定义数据类最基本的行为，包括：
 * 计算/设置/获取签名、输出json格式的参数、从json读取数据对象等
 * @author Leijid
 *
 */
use App\Models\Settings\PaymentMethod;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayConfig;
use App\Models\Utils\Payment\RoyalPay\Lib\RoyalPayException;
use App\Models\Utils\PaymentTool;

class RoyalPayDataBase
{
    protected $pathValues = array();

    protected $queryValues = array();

    protected $bodyValues = array();


    /**
     * 设置随机字符串，不长于30位。推荐随机数生成算法
     * @param string $value
     **/
    public function setNonceStr($value)
    {
        $this->queryValues['nonce_str'] = $value;
    }

    /**
     * 获取随机字符串，不长于30位。推荐随机数生成算法的值
     * @return 值
     **/
    public function getNonceStr()
    {
        return $this->queryValues['nonce_str'];
    }

    /**
     * 判断随机字符串，不长于32位。推荐随机数生成算法是否存在
     * @return true 或 false
     **/
    public function isNonceStrSet()
    {
        return array_key_exists('nonce_str', $this->queryValues);
    }

    /**
     * 设置时间戳
     * @param long $value
     **/
    public function setTime($value)
    {
        $this->queryValues['time'] = $value;
    }

    /**
     * 获取时间戳
     * @return 值
     **/
    public function getTime()
    {
        return $this->queryValues['time'];
    }

    /**
     * 判断时间戳是否存在
     * @return true 或 false
     **/
    public function isTimeSet()
    {
        return array_key_exists('time', $this->queryValues);
    }

    /**
     * 设置签名，详见签名生成算法
     * @param null $credentialCode
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用setSign方法赋值
     */
    public function setSign($credentialCode=null)
    {
        $sign = $this->makeSign($credentialCode);
        $this->queryValues['sign'] = $sign;
        return $sign;
    }

    /**
     * 获取签名，详见签名生成算法的值
     * @return 值
     **/
    public function getSign()
    {
        return $this->queryValues['sign'];
    }

    /**
     * 判断签名，详见签名生成算法是否存在
     * @return true 或 false
     **/
    public function isSignSet()
    {
        return array_key_exists('sign', $this->queryValues);
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function toQueryParams()
    {
        $buff = "";
        foreach ($this->queryValues as $k => $v) {
            if ($v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 格式化参数格式化成json参数
     */
    public function toBodyParams()
    {
        return json_encode($this->bodyValues);
    }

    /**
     * 格式化签名参数
     * @param string|null $credentialCode
     * @return string
     */
    public function toSignParams($credentialCode = null)
    {
        $buff = "";
        $method = PaymentMethod::GetByMethodId(PaymentTool::$TYPE_WECHAT);
        $buff .= $method->getApiToken() . '&' . $this->getTime() . '&' . $this->getNonceStr() . "&" . $method->getApiSecret();
        return $buff;
    }

    /**
     * 生成签名
     * @param string|null $credentialCode
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用setSign方法赋值
     */
    public function makeSign($credentialCode = null)
    {
        //签名步骤一：构造签名参数
        $string = $this->toSignParams();
        //签名步骤三：SHA256加密
        $string = hash('sha256', utf8_encode($string));
        //签名步骤四：所有字符转为小写
        $result = strtolower($string);
        return $result;
    }

    /**
     * 获取设置的path参数值
     */
    public function getPathValues()
    {
        return $this->pathValues;
    }

    /**
     * 获取设置的query参数值
     */
    public function getQueryValues()
    {
        return $this->queryValues;
    }

    /**
     * 获取设置的body参数值
     */
    public function getBodyValues()
    {
        return $this->bodyValues;
    }
}
