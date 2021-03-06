<?php
namespace ZJS;

use ZJS\Libary\MagicAttributes;

//接收人
class Receiver extends MagicAttributes
{
    public function __construct()
    {
        $this->attributes['name']='';
        $this->attributes['postCode']='';
        $this->attributes['phone']='';
        $this->attributes['mobile']='';
        $this->attributes['prov']='';
        $this->attributes['city']='';
        $this->attributes['district']='';
        $this->attributes['address']='';
    }
    /**
     * 有效的参数.
     *
     * @var array
     */
    protected $valids = array('name', 'mobile', 'prov', 'city','district','address');
    /**
     * 检测参数值是否有效.
     */
    protected function checkParams()
    {
        foreach ($this->valids as $paramName) {
            if (empty($this->attributes[$paramName])) {
                throw new \Exception(sprintf('"%s" is required', $paramName));
            }
        }
    }
    public function valids()
    {
        $this->checkParams();
    }
}
