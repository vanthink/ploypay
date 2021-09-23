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

use polypay\ecc\primitives\GeneratorPoint;


class DebugDecorator implements GmpMathInterface
{
  
    private $adapter;

    private $writer;

    public function __construct(GmpMathInterface $adapter, callable $callback = null)
    {
        $this->adapter = $adapter;
        $this->writer = $callback ?: function ($message) {
            echo $message;
        };
    }

   
    private function write($message)
    {
        call_user_func($this->writer, $message);
    }

   
    private function call($func, $args)
    {
        $strArgs = array_map(
            function ($arg) {
                if ($arg instanceof \GMP) {
                    return var_export($this->adapter->toString($arg), true);
                } else {
                    return var_export($arg, true);
                }
            },
            $args
        );

        if (strpos($func, '::')) {
            list(, $func) = explode('::', $func);
        }

        $this->write($func.'('.implode(', ', $strArgs).')');

        $res = call_user_func_array([ $this->adapter, $func ], $args);

        if ($res instanceof \GMP) {
            $this->write(' => ' . var_export($this->adapter->toString($res), true) . PHP_EOL);
        } else {
            $this->write(' => ' . var_export($res, true) . PHP_EOL);
        }

        return $res;
    }

    public function cmp(\GMP $first, \GMP $other): int
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

    public function equals(\GMP $first, \GMP $other): bool
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
                $this,
                'call',
            ),
            $func,
            $args
        );
    }

    public function mod(\GMP $number, \GMP $modulus): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

    public function add(\GMP $augend, \GMP $addend): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

    public function sub(\GMP $minuend, \GMP $subtrahend): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

 
    public function mul(\GMP $multiplier, \GMP $multiplicand): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function div(\GMP $dividend, \GMP $divisor): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function pow(\GMP $base, int $exponent): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function bitwiseAnd(\GMP $first, \GMP $other): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function toString(\GMP $value): string
    {
        return $this->adapter->toString($value);
    }

  
    public function hexDec(string $hexString): string
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

  
    public function decHex(string $decString): string
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function powmod(\GMP $base, \GMP $exponent, \GMP $modulus): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function isPrime(\GMP $n): bool
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function nextPrime(\GMP $currentPrime): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function inverseMod(\GMP $a, \GMP $m): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function jacobi(\GMP $a, \GMP $p): int
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function intToFixedSizeString(\GMP $x, int $byteSize): string
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
                $this,
                'call',
            ),
            $func,
            $args
        );
    }

   
    public function intToString(\GMP $x): string
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function stringToInt(string $s): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function digestInteger(\GMP $m): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

   
    public function gcd2(\GMP $a, \GMP $m): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

    
    public function rightShift(\GMP $number, int $positions): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

    public function leftShift(\GMP $number, int $positions): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call',
            ),
            $func,
            $args
        );
    }

 
    public function bitwiseXor(\GMP $first, \GMP $other): \GMP
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call'
            ),
            $func,
            $args
        );
    }

  
    public function baseConvert(string $value, int $fromBase, int $toBase): string
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call'
            ),
            $func,
            $args
        );
    }

  
    public function getEcMath(GeneratorPoint $generatorPoint, $input)
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call'
            ),
            $func,
            $args
        );
    }

    public function getModularArithmetic(\GMP $modulus): ModularArithmetic
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
                $this,
                'call'
            ),
            $func,
            $args
        );
    }

    public function getNumberTheory(): NumberTheory
    {
        $func = __METHOD__;
        $args = func_get_args();

        return call_user_func(
            array(
            $this,
            'call'
            ),
            $func,
            $args
        );
    }
}
