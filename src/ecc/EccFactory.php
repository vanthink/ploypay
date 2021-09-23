<?php
declare(strict_types=1);

namespace polypay\ecc;
// +----------------------------------------------------------------------
// | Title:  静态工厂类，提供使用NIST和SECG推荐曲线的工厂方法。 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\crypto\signature\Signer;
use polypay\ecc\curves\NistCurve;
use polypay\ecc\curves\SecgCurve;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\math\MathAdapterFactory;
use polypay\ecc\primitives\CurveFp;
use polypay\ecc\primitives\CurveFpInterface;
use polypay\ecc\primitives\CurveParameters;

class EccFactory
{
    //为运行环境选择并创建最合适的适配器
    public static function getAdapter(bool $debug = false): GmpMathInterface
    {
        return MathAdapterFactory::getAdapter($debug);
    }

    // 返回工厂以创建NIST推荐的曲线和生成器 
    public static function getNistCurves(GmpMathInterface $adapter = null): NistCurve
    {
        return new NistCurve($adapter ?: self::getAdapter());
    }

    // 返回工厂以返回SECG建议的曲线和生成器。 
    public static function getSecgCurves(GmpMathInterface $adapter = null): SecgCurve
    {
        return new SecgCurve($adapter ?: self::getAdapter());
    }

    //从任意参数创建新曲线
    public static function createCurve(int $bitSize, \GMP $prime, \GMP $a, \GMP $b, GmpMathInterface $adapter = null): CurveFpInterface
    {
        return new CurveFp(new CurveParameters($bitSize, $prime, $a, $b), $adapter ?: self::getAdapter());
    }

    
    public static function getSigner(GmpMathInterface $adapter = null): Signer
    {
        return new Signer($adapter ?: self::getAdapter());
    }
}
