<?php
declare(strict_types=1);

namespace polypay\ecc\primitives;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

class CurveParameters
{
  
    protected $a;

    protected $b;

    protected $prime;

    protected $size;

    public function __construct(int $size, \GMP $prime, \GMP $a, \GMP $b)
    {
        $this->size = $size;
        $this->prime = $prime;
        $this->a = $a;
        $this->b = $b;
    }

    public function getA(): \GMP
    {
        return $this->a;
    }

    public function getB(): \GMP
    {
        return $this->b;
    }

    public function getPrime(): \GMP
    {
        return $this->prime;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
