<?php
namespace polypay\ecc\sm34;
// +----------------------------------------------------------------------
// | Title: 国密sm3,sm4 ECB加密源码
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\sm34\handler\ExtendedCompression;
use polypay\ecc\sm34\libs\WordConversion;
use polypay\ecc\sm34\types\BitString;

class SM3 implements \ArrayAccess
{
    /** @var string 初始值常数 */
    const IV = '7380166f4914b2b9172442d7da8a0600a96f30bc163138aae38dee4db0fb0e4e';
    
    /** @var string 消息(加密前的结果) */
    private $message = '';
    /** @var string 杂凑值(加密后的结果) */
    private $hash_value = '';
    
    /**
     * 实例化时直接调用将参数传给主方法
     * Sm3 constructor.
     *
     * @param $message string|mixed 传入的消息
     *
     * @throws \ErrorException
     */
    public function __construct($message)
    {
        // 输入验证
        if (is_int($message)) {
            $message = (string)$message;
        }
        if (empty($message)) {
            $message = '';
        }
        if (!is_string($message)) {
            throw new ErrorException('参数类型必须为string，请检查后重新输入', 90001);
        }
        
        /** @var string message 消息 */
        $this->message = $message;
        /** @var string hash_value  杂凑值 */
        $this->hash_value = $this->sm3();
    }
 
    private function sm3()
    {
        /** @var string $m 转化后的消息（二进制码） */
        // $json =  json_encode($this->message);
        $m = new BitString($this->message, false);
        //var_dump($m);die(); //11010010  10111011
        // 一、填充
        $l = strlen($m);
        
        // 满足l + 1 + k ≡ 448mod512 的最小的非负整数
        $k = $l % 512;
        $k = $k + 64 >= 512
            ? 512 - ($k % 448) - 1
            : 512 - 64 - $k - 1;
        
        $bin_l = new BitString($l);
        // 填充后的消息
        $m_fill = new BitString(
            $m # 原始消息m
            . '1' # 拼个1
            . str_pad('', $k, '0') # 拼上k个比特的0
            . (
            strlen($bin_l) >= 64
                ? substr($bin_l, 0, 64)
                : str_pad($bin_l, 64, '0', STR_PAD_LEFT)
            ) # 64比特，l的二进制表示
        );
        // 二、迭代压缩
        // 迭代过程
        $B = str_split($m_fill, 512);
        /** @var int $n m'可分为的组数 */
        $n = ($l + $k + 65) / 512;
        if (count($B) !== $n) {
            throw new ErrorException();
        }
        
        $V = array(
            WordConversion::hex2bin(self::IV),
        );
        $extended = new ExtendedCompression();
        foreach ($B as $key => $Bi) {
            // print_r($Bi."\n"."====== $key ========\n".strlen($Bi)."\n");
            $V[$key + 1] = $extended->CF($V[$key], $Bi)->getBitString();
        }
        
        krsort($V);
        reset($V);
        $binary = current($V);
        $rt = WordConversion::bin2hex($binary);
        return $rt;
    }
    
   
    public function __toString()
    {
        return $this->hash_value;
    }
    
   
    public function offsetExists($offset)
    {
        return isset($this->hash_value[$offset]);
    }
    
   
    public function offsetGet($offset)
    {
        return $this->hash_value[$offset];
    }
    
    
    public function offsetSet($offset, $value)
    {
        $this->hash_value[$offset] = $value;
        return $this;
    }
    
    public function offsetUnset($offset)
    {
        unset($this->hash_value[$offset]);
    }
}