<?php
namespace polypay\ecc\sm34\types;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

class BitString implements \ArrayAccess
{
    /** @var string 一个比特串类型的变量 */
    protected $bit_string = '';
    
    public function __construct($string, $is_bit_string = null)
    {
        if (is_object($string)) {
            $string = $string->getString();
        }
        
        if ($is_bit_string === false) {
            // 指定不是比特串的，直接转换
            $this->bit_string = "{$this->str2bin($string)}";
        } else {
            // 默认走个验证试试
            $this->bit_string = $this->is_bit_string($string)
                ? $string
                : "{$this->str2bin($string)}";
        }
    }
    
    private function str2bin($str)
    {
        if (!is_string($str) && !is_int($str)) {
            throw new ErrorException('输入的类型错误');
        }
        if (is_int($str)) {
            return decbin($str);
        }
        $fileType = mb_detect_encoding($str , array('UTF-8','GBK','LATIN1','BIG5')) ;
        if( $fileType != 'UTF-8'){
            $str = mb_convert_encoding($str ,'utf-8' , $fileType);
        }
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        foreach ($arr as &$v) {
            $fileType = mb_detect_encoding($v , array('UTF-8','GBK','LATIN1','BIG5')) ;
            /* if( $fileType != 'GBK'){
                $v = mb_convert_encoding($v ,'GBK' , $fileType); //这里也要utf8啊，
            } */
            if( $fileType != 'UTF-8'){
                $str = mb_convert_encoding($str ,'utf-8' , $fileType);
            }
            $temp = unpack('H*', $v);
            $v = base_convert($temp[1], 16, 2);

            while (strlen($v) < 8) {
                $v = '0' . $v;
            }
            unset($temp);
        }
        return join('', $arr);
    }
    
   
    public function is_bit_string($string)
    {
        if (is_object($string)) {
            $string = $string->getString();
        }
        // 检查是否为字符串
        if (!is_string($string)) {
            return false;
        }
        
        // 检查是否为只有0和1组成的字符串
        $array = array_filter(str_split($string));
        foreach ($array as $value) {
            if (!in_array(
                $value,
                array(
                    0,
                    '0',
                    1,
                    '1'
                ),
                true
            )) {
                return false;
            }
        }
        
        return true;
    }
    
    public function __toString()
    {
        return $this->getString();
    }
    
    
    public function getString()
    {
        return $this->bit_string;
    }
    
    public function offsetGet($offset)
    {
        return $this->bit_string[$offset];
    }
   
    public function offsetExists($offset)
    {
        return isset($this->bit_string[$offset]);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->bit_string[$offset] = $value;
        return $this;
    }
    
    public function offsetUnset($offset)
    {
        unset($this->bit_string[$offset]);
    }
}