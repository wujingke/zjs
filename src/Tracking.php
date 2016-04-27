<?php
namespace ZJS;

use ZJS\Libary\XML;
use ZJS\Libary\Http;
use ZJS\Libary\Utils;

/**
 *订单相关
 */
class Tracking
{
    private $user; //用户名

    private $key;//秘钥

    private $const; //常量

    private $url;//查询地址
    private $attributes;


    private $xml='';

    public function __construct($user='', $key='', $const='', $url="")
    {
        $this->attributes['logisticProviderID']=$user;
        $this->key=$key;
        $this->const=$const;
        $this->url=$url;
    }

    public function setMailNos(array $mailNo=array(), $key='mailNo')
    {
        if (count($mailNo) >100) {
            throw new Exception("运单号不能超过100个");
        }

        foreach ($mailNo as $value) {
            $this->attributes['orders'][]['order']=array($key=>$value);
        }
    }
    public function send()
    {
        $this->toBuildXML();
        $data = $this->sign();
        $http = new Http();
        $return =$http->post($this->url.'/Get', $data);

        $xml = Utils::formatXML($return['data']);

        if (false !== strpos($xml, '未授权')) {
            throw new \Exception(sprintf('%s', "IP未授权，请联系宅急送客服！"));
        }
        $array=Utils::xml_to_array($xml);
        if (isset($array['BatchQueryResponse']['orders'])) {
            return $array['BatchQueryResponse']['orders'];
        } else {
            return false;
        }
    }

    private function toBuildXML($root='BatchQueryRequest')
    {
        $xml = XML::array2xml($this->attributes, $root);

        $this->xml= $xml;
    }

    private function sign()
    {
        $rdm1=mt_rand(1000, 9999);
        $rdm2=mt_rand(1000, 9999);
        $str = $rdm1.$this->attributes['logisticProviderID'].$this->xml.$this->key.$this->const.$rdm2;

        $verifyData=$rdm1.substr(md5($str), 7, 21).$rdm2;//生成密钥32位

        return "clientFlag=".$this->attributes['logisticProviderID']."&xml=".$this->xml."&verifyData=".$verifyData;
    }
}
