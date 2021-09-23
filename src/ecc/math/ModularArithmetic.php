<?php

namespace polypay\ecc\math;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

class ModularArithmetic
{

    private $adapter;

    private $modulus;

    public function __construct(GmpMathInterface $adapter, \GMP $modulus)
    {
        $this->adapter = $adapter;
        $this->modulus = $modulus;
    }

    public function add(\GMP $augend, \GMP $addend): \GMP
    {
        return $this->adapter->mod($this->adapter->add($augend, $addend), $this->modulus);
    }

    public function sub(\GMP $minuend, \GMP $subtrahend): \GMP
    {
        return $this->adapter->mod($this->adapter->sub($minuend, $subtrahend), $this->modulus);
    }

    public function mul(\GMP $multiplier, \GMP $muliplicand): \GMP
    {
        return $this->adapter->mod($this->adapter->mul($multiplier, $muliplicand), $this->modulus);
    }

    public function div(\GMP $dividend, \GMP $divisor): \GMP
    {
        return $this->mul($dividend, $this->adapter->inverseMod($divisor, $this->modulus));
    }

    public function pow(\GMP $base, \GMP $exponent): \GMP
    {
        return $this->adapter->powmod($base, $exponent, $this->modulus);
    }
}
