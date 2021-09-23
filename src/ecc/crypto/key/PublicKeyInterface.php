<?php
declare(strict_types=1);

namespace polypay\ecc\crypto\key;

// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\primitives\CurveFpInterface;
use polypay\ecc\primitives\PointInterface;
use polypay\ecc\primitives\GeneratorPoint;

interface PublicKeyInterface
{

    /**
     * @return CurveFpInterface
     */
    public function getCurve(): CurveFpInterface;

    /**
     * @return PointInterface
     */
    public function getPoint(): PointInterface;

    /**
     * @return GeneratorPoint
     */
    public function getGenerator(): GeneratorPoint;
}
