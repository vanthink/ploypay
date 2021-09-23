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

interface PointInterface
{

    public function isInfinity(): bool;

    public function add(PointInterface $addend): PointInterface;
    
    public function cmp(PointInterface $other): int;

    public function equals(PointInterface $other): bool;

    public function mul(\GMP $multiplier): PointInterface;

    public function getCurve(): CurveFpInterface;

    public function getDouble(): PointInterface;

    public function getOrder(): \GMP;

    public function getX(): \GMP;

    public function getY(): \GMP;

    public function __toString(): string;
}
