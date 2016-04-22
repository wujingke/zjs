<?php
namespace ZJS\Libary;

/**
 *
 */
class Utils
{

    //签名
    public static function sign()
    {
    }

    //
    public static function xml_to_array($xml)
    {
        $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            $arr = array();
            for ($i = 0; $i < $count; $i++) {
                $key= $matches[1][$i];
                $val = self::xml_to_array($matches[2][$i]);  // 递归
            if (array_key_exists($key, $arr)) {
                if (is_array($arr[$key])) {
                    if (!array_key_exists(0, $arr[$key])) {
                        $arr[$key] = array($arr[$key]);
                    }
                } else {
                    $arr[$key] = array($arr[$key]);
                }
                $arr[$key][] = $val;
            } else {
                $arr[$key] = $val;
            }
            }
            return $arr;
        } else {
            return $xml;
        }
    }

    public static function formatXML($xml='')
    {
        return str_replace(array('&lt;', '&gt;', '<string xmlns="http://edi.zjs.com.cn/">'), array('<', '>', ''), $xml);
    }
}
