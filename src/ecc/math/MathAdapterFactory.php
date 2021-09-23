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

class MathAdapterFactory
{

    private static $forcedAdapter = null;

    public static function forceAdapter(GmpMathInterface $adapter = null)
    {
        self::$forcedAdapter = $adapter;
    }

    public static function getAdapter(bool $debug = false): GmpMathInterface
    {
        if (self::$forcedAdapter !== null) {
            return self::$forcedAdapter;
        }

        $adapter = new GmpMath();

        return self::wrapAdapter($adapter, $debug);
    }

    private static function wrapAdapter(GmpMathInterface $adapter, bool $debug): GmpMathInterface
    {
        if ($debug === true) {
            return new DebugDecorator($adapter);
        }

        return $adapter;
    }
}
