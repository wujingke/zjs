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
    protected $valids = array('name', 'phone', 'prov', 'city','district','address');

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
