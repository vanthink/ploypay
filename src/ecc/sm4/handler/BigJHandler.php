<?php
namespace polypay\ecc\sm34\handler;

// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\sm34\libs\WordConversion;

class BigJHandler extends JHandler
{
    /** @var int j的最大可用值 */
    const SMALLEST_J = 16;
    /** @var int j的最小可用值 */
    const BIGGEST_J = 63;
    /** @var string T常量 */
    const T = '7a879d8a';
    
    /**
     * 补充父类
     * SmallJHandler constructor.
     */
    public function __construct()
    {
        parent::__construct(self::T, self::SMALLEST_J, self::BIGGEST_J);
    }
    
    public function FF($X, $Y, $Z)
    {
        $X_and_Y = WordConversion::andConversion(array($X, $Y));
        $X_and_Z = WordConversion::andConversion(array($X, $Z));
        $Y_and_Z = WordConversion::andConversion(array($Y, $Z));
        
        return WordConversion::orConversion(
            array(
                $X_and_Y,
                $X_and_Z,
                $Y_and_Z
            )
        );
    }
    
  
    public function GG($X, $Y, $Z)
    {
        $X_and_Y = WordConversion::andConversion(array($X, $Y));
        
        $not_X = WordConversion::notConversion($X);
        $not_X_and_Z = WordConversion::andConversion(array($not_X, $Z));
        
        return WordConversion::orConversion(array($X_and_Y, $not_X_and_Z));
    }
}