<?php
declare(strict_types=1);

namespace polypay\ecc\Serializer\Point;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\primitives\PointInterface;
use polypay\ecc\primitives\CurveFpInterface;

interface PointSerializerInterface
{
   
    public function serialize(PointInterface $point): string;

    public function unserialize(CurveFpInterface $curve, string $string): PointInterface;
}
