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
use polypay\ecc\serializer\util\CurveOidMapper;
use polypay\ecc\util\BinaryString;

class UncompressedPointSerializer implements PointSerializerInterface
{
   
    public function serialize(PointInterface $point): string
    {
        $length = CurveOidMapper::getByteSize($point->getCurve()) * 2;

        $hexString = '04';
        $hexString .= str_pad(gmp_strval($point->getX(), 16), $length, '0', STR_PAD_LEFT);
        $hexString .= str_pad(gmp_strval($point->getY(), 16), $length, '0', STR_PAD_LEFT);

        return $hexString;
    }

    public function unserialize(CurveFpInterface $curve, string $data): PointInterface
    {
        if (BinaryString::substring($data, 0, 2) != '04') {
            throw new \InvalidArgumentException('Invalid data: only uncompressed keys are supported.');
        }

        $data = BinaryString::substring($data, 2);
        $dataLength = BinaryString::length($data);

        $x = gmp_init(BinaryString::substring($data, 0, $dataLength / 2), 16);
        $y = gmp_init(BinaryString::substring($data, $dataLength / 2), 16);

        return $curve->getPoint($x, $y);
    }
}
