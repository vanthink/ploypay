<?php
namespace polypay\ecc\sm34\handler;
// +----------------------------------------------------------------------
// | Title: j处理抽象类
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\sm34\libs\WordConversion;
use polypay\ecc\sm34\types\Word;

abstract class JHandler
{
    /** @var string 常量T */
    protected $T = '';
    /** @var array j的长度区间 */
    protected $section_j = array();
    
    public function __construct($T, $smallest, $biggest)
    {
        $this->setT($T);
        $this->setSectionJ($smallest, $biggest);
    }
    
    public function setSectionJ($smallest, $biggest)
    {
        $this->section_j = array($smallest, $biggest);
    }
    
    
    abstract public function FF($X, $Y, $Z);
    
    
    abstract public function GG($X, $Y, $Z);
    
    
    public function getT()
    {
        return new Word($this->T);
    }
    
    public function setT($T)
    {
        $this->T = WordConversion::hex2bin($T);
    }
}