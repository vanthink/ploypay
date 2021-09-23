<?php
declare(strict_types=1);

namespace polypay\ecc\primitives;

use polypay\ecc\math\ModularArithmetic;
use polypay\ecc\random\RandomNumberGeneratorInterface;

// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

interface CurveFpInterface
{

    
    public function getModAdapter(): ModularArithmetic;

    public function getPoint(\GMP $x, \GMP $y, \GMP $order = null): PointInterface;

    public function recoverYfromX(bool $wasOdd, \GMP $x): \GMP;

    public function getInfinity(): PointInterface;

    public function getGenerator(\GMP $x, \GMP $y, \GMP $order, RandomNumberGeneratorInterface $randomGenerator = null): GeneratorPoint;

    public function contains(\GMP $x, \GMP $y): bool;

    public function getA(): \GMP;

    public function getB(): \GMP;

    public function getPrime(): \GMP;

    public function getSize(): int;

    public function cmp(CurveFpInterface $other): int;

    public function equals(CurveFpInterface $other): bool;

    public function __toString(): string;
}
