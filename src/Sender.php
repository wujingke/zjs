<?php
namespace ZJS;

use ZJS\Libary\MagicAttributes;

//发货人
class Sender extends MagicAttributes
{
    /**
     * 有效的参数.
     *
     * @var array
     */
    protected $valids = array('name',  'prov', 'city','district','address');

    public function __construct()
    {
        $this->attributes['name']='';
        $this->attributes['phone']='';
        $this->attributes['postCode']='';
        $this->attributes['mobile']='';
        $this->attributes['prov']='';
        $this->attributes['city']='';
        $this->attributes['district']='';
        $this->attributes['address']='';
    }

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
