<?php
namespace polypay\ecc\sm34\handler;
// +----------------------------------------------------------------------
// | Title: 小j处理类
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\sm34\libs\WordConversion;
use polypay\ecc\sm34\handler\Substitution;
use polypay\ecc\sm34\handler\JHandler;

class SmallJHandler extends JHandler
{
    /** @var int j的最大可用值 */
    const SMALLEST_J = 0;
    /** @var int j的最小可用值 */
    const BIGGEST_J = 15;
    /** @var string T常量 */
    const T = '79cc4519';
    
   
    public function __construct()
    {
        parent::__construct(self::T, self::SMALLEST_J, self::BIGGEST_J);
    }
    
    
    public function FF($X, $Y, $Z)
    {
        return self::boolFunction($X, $Y, $Z);
    }
    
   
    private static function boolFunction($X, $Y, $Z)
    {
        return WordConversion::xorConversion(
            array(
                $X,
                $Y,
                $Z
            )
        );
    }
    
    public function GG($X, $Y, $Z)
    {
        return self::boolFunction($X, $Y, $Z);
    }
}