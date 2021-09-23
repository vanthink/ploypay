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

use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\primitives\CurveFpInterface;
use polypay\ecc\primitives\PointInterface;
use polypay\ecc\serializer\Util\CurveOidMapper;

class CompressedPointSerializer implements PointSerializerInterface
{
   
    private $adapter;
    private $theory;

    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->theory = $adapter->getNumberTheory();
    }

    public function getPrefix(PointInterface $point): string
    {
        if ($this->adapter->equals($this->adapter->mod($point->getY(), gmp_init(2, 10)), gmp_init(0))) {
            return '02';
        } else {
            return '03';
        }
    }

    public function serialize(PointInterface $point): string
    {
        $length = CurveOidMapper::getByteSize($point->getCurve()) * 2;

        $hexString = $this->getPrefix($point);
        $hexString .= str_pad(gmp_strval($point->getX(), 16), $length, '0', STR_PAD_LEFT);

        return $hexString;
    }

    public function unserialize(CurveFpInterface $curve, string $data): PointInterface
    {
        $prefix = substr($data, 0, 2);
        if ($prefix !== '03' && $prefix !== '02') {
            throw new \InvalidArgumentException('Invalid data: only compressed keys are supported.');
        }

        $x = gmp_init(substr($data, 2), 16);
        $y = $curve->recoverYfromX($prefix === '03', $x);

        return $curve->getPoint($x, $y);
    }
}
