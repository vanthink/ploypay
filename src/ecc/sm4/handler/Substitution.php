<?php
namespace polypay\ecc\sm34\handler;
// +----------------------------------------------------------------------
// | Title: 置换函数
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------


use polypay\ecc\sm34\types\Word;
use polypay\ecc\sm34\libs\WordConversion;

class Substitution
{
    /** @var \SM3\types\Word 待置换的字 */
    private $X;
    
    /** @var array P0函数中两次左移的位数 */
    private $P0_shiftLeft_times = array(9, 17);
    /** @var array P1函数中两次左移的位数 */
    private $P1_shiftLeft_times = array(15, 23);
    
    public function __construct($X)
    {
        $this->X = $X;
    }
    
    
    public function P0()
    {
        return $this->substitutionFunction(0);
    }
    
    
    private function substitutionFunction($type)
    {
        if (!in_array($type, array(0, '0', 1, '1'))) {
            return new Word('');
        }
        
        $times_name = $type == 1
            ? $this->P1_shiftLeft_times
            : $this->P0_shiftLeft_times;
        
        $X_shiftLeft_1 = WordConversion::shiftLeftConversion($this->X, $times_name[0]);
        $X_shiftLeft_2 = WordConversion::shiftLeftConversion($this->X, $times_name[1]);
        
        return WordConversion::xorConversion(
            array(
                $this->X,
                $X_shiftLeft_1,
                $X_shiftLeft_2
            )
        );
    }
    
    public function P1()
    {
        return $this->substitutionFunction(1);
    }
}