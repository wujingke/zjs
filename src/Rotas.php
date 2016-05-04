<?php
namespace ZJS;

use ZJS\Libary\XML;
use ZJS\Libary\Http;
use ZJS\Libary\Utils;

class Rotas
{

    private $user; //用户名

    private $key;//秘钥

    private $const; //常量

    private $url;//下单地址
    private $attributes;


    private $xml='';

    public function __construct($user='', $key='', $const='', $url="")
    {
        $this->attributes['clientFlag']=$user;
        $this->key=$key;
        $this->const=$const;
        $this->url=$url;
    }

    public function setOrderId($orderId=0)
    {
        $this->attributes['ei']['orderCode']=$orderId;
    }
    public function setSendAddress($address='')
    {
        $this->attributes['ei']['sAddress']=$address;
    }
    public function setReceiverAddress($address='')
    {
        $this->attributes['ei']['rAddress']=$address;
    }



    public function send()
    {
        $this->attributes['ei']['action']=0;
        $this->attributes['ei']['isEnbaled']='N';
        $this->toBuildXML();
        $data = $this->sign();
        $http = new Http();
        $return =$http->post($this->url.'/GetB2CInfos', $data);


        $xml = Utils::formatXML($return['data']);
        if (false !== strpos($xml, '未授权')) {
            throw new \Exception(sprintf('%s', "IP未授权，请联系宅急送客服！"));
        }

        return  Utils::xml_to_array($xml);
    }

    private function toBuildXML($root='RequestElectronicInfo')
    {
        $xml = XML::build($this->attributes, $root);

        $this->xml= $xml;
    }

    private function sign()
    {
        return  'logistics_interface='.$this->xml.'&data_digest='.md5($this->xml);
    }
}
