<?php
declare(strict_types=1);

namespace polypay\ecc\Util;
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

class NumberSize
{

    public static function getCeiledByteSize(GmpMathInterface $adapter, \GMP $x): float
    {
        $log2 = self::bnNumBits($adapter, $x);
        return ceil($log2 / 8);
    }

    public static function getFlooredByteSize(GmpMathInterface $adapter, \GMP $x): float
    {
        $log2 = self::bnNumBits($adapter, $x);
        return floor($log2 / 8) + 1;
    }

    public static function bnNumBytes(GmpMathInterface $adapter, \GMP $x): int
    {
        return (int) floor((self::bnNumBits($adapter, $x) + 7) / 8);
    }

    public static function bnNumBits(GmpMathInterface $adapter, \GMP $x): int
    {
        $zero = gmp_init(0, 10);
        if ($adapter->equals($x, $zero)) {
            return 0;
        }

        $log2 = 0;
        while (false === $adapter->equals($x, $zero)) {
            $x = $adapter->rightShift($x, 1);
            $log2++;
        }

        return $log2 ;
    }
}
