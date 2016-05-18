<?php
namespace ZJS;

use ZJS\Libary\XML;
use ZJS\Libary\Http;
use ZJS\Libary\Utils;

/**
 *订单相关
 */
class Order
{

    private $user; //用户名

    private $key;//秘钥

    private $const; //常量

    private $url;//下单地址
    private $attributes;


    private $xml='';

    public function __construct($user='', $key='', $const='', $url="")
    {
        $this->attributes['logisticProviderID']=$user;
        $this->key=$key;
        $this->const=$const;
        $this->url=$url;

        $this->attributes['orderNo']='';
        $this->attributes['subOrderNo']='';
        $this->attributes['tradeNo']='';
        $this->attributes['mailNo']='';
        $this->attributes['type'] =1;
        $this->attributes['flag']=0;
        $this->attributes['sender']=array();
        $this->attributes['receiver']=array();
        $sendStartTime='';
        $sendEndTime='';
        $this->attributes['sendStartTime'] = $sendStartTime;
        $this->attributes['sendEndTime'] = $sendEndTime;
        $this->attributes['codAmount']='0';
        $this->attributes['items']=array();
        $this->attributes['itemsName'] = '';
        $this->attributes['itemsNumber'] = '';

        $this->attributes['itemsWeight'] = '';
        $this->attributes['itemsVolume'] = '';
        $this->attributes['itemsValue'] = '';
        $this->attributes['insuranceValue']='';
        $this->attributes['loadRequire']='';
        $this->attributes['remark']='';
        $this->attributes['dataFlag']='';
    }
    public function setSender(Sender $sender)
    {
        $sender->valids();
        $this->attributes['sender']= $sender->toArray();
    }
    public function setReceiver(Receiver $receiver)
    {
        $receiver->valids();
        $this->attributes['receiver']= $receiver->toArray();
    }
    public function setOrderId($orderId=0)
    {
        $this->attributes['orderNo']=$orderId;
    }
    public function setMailNo($mailNo=0)
    {
        $this->attributes['mailNo']=$mailNo;
    }
    public function setRemark($remark)
    {
        $this->attributes['remark']=$remark;
    }

    /**
     * 设置单个商品信息
     *
     * @param string $name 商品名称
     * @param int    $number 商品数量
     * @param double $value 商品价格
     * @param double $weight   商品重量
     * @param string $volume    商品长宽高 单位CM
     *
     * @return string
     */
    public function setItem($name, $number, $value="0.00", $weight="0.00", $volume='21.0×29.7')
    {
        $array['itemName'] = $name;
        $array['itemNumber'] = $number;
        $array['itemValue'] = $value;
        $array['itemWeight'] = $weight;
        $array['itemVolume'] = $volume;


        $this->attributes['items'][]=$array;
    }


    /**
     * 设置总商品信息
     *
     * @param string $name 商品名称
     * @param int    $number 商品数量
     * @param double $value 商品价格
     * @param double $weight   商品重量
     * @param string $volume    商品长宽高 单位CM
     *
     * @return string
     */
    public function setItems($name, $number, $value="0.00", $weight="0.05", $volume='21.0×29.7')
    {
        $this->attributes['itemsName'] = $name;
        $this->attributes['itemsNumber'] = $number;

        $this->attributes['itemsWeight'] = $weight;
        $this->attributes['itemsVolume'] = $volume;
        $this->attributes['itemsValue'] = $value;
    }
    public function push()
    {
        $this->toBuildXML();
        $data = $this->sign();
        
        $http = new Http();
        $return =$http->post($this->url.'/OrderXML', $data);

        $xml = Utils::formatXML($return['data']);

        if (false !== strpos($xml, '未授权')) {
            throw new \Exception(sprintf('%s', "IP未授权，请联系宅急送客服！"));
        }
        return  Utils::xml_to_array($xml);
    }

    public function cancel()
    {
        $xml ='<?xml version="1.0" encoding="utf-8" ?><UpdateInfo><logisticProviderID>{logisticProviderID}</logisticProviderID><orderNo>{orderNo}</orderNo><infoType>INSTRUCTION</infoType><infoContent>WITHDRAW</infoContent></UpdateInfo>';

        $this->xml = str_replace(array('{logisticProviderID}', '{orderNo}'), array($this->attributes['logisticProviderID'], $this->attributes['orderNo']), $xml);
        $data = $this->sign();
        $http = new Http();
        $return =$http->post($this->url.'/OrderXML', $data);

        $xml = Utils::formatXML($return['data']);

        if (false !== strpos($xml, '未授权')) {
            throw new \Exception(sprintf('%s', "IP未授权，请联系宅急送客服！"));
        }

        return  Utils::xml_to_array($xml);
    }


    private function toBuildXML($root='RequestOrder')
    {
        $xml ='<?xml version="1.0" encoding="utf-8" ?>';
        $xml .= XML::build($this->attributes, $root);

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

    /**
     * 魔术读取.
     *
     * @param string $property
     */
    public function __get($property)
    {
        return !isset($this->attributes[$property]) ? null : $this->attributes[$property];
    }

    /**
     * 魔术写入.
     *
     * @param string $property
     * @param mixed  $value
     */
    public function __set($property, $value)
    {
        return $this->attributes[$property]= $value;
    }
}
