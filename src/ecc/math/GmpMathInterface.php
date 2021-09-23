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

interface GmpMathInterface
{
   
    public function cmp(\GMP $first, \GMP $other): int;

    public function equals(\GMP $first, \GMP $other): bool;
    
    public function mod(\GMP $number, \GMP $modulus): \GMP;

    public function add(\GMP $augend, \GMP $addend): \GMP;

    public function sub(\GMP $minuend, \GMP $subtrahend): \GMP;

    public function mul(\GMP $multiplier, \GMP $multiplicand): \GMP;

    public function div(\GMP $dividend, \GMP $divisor): \GMP;

    public function pow(\GMP $base, int $exponent): \GMP;

    public function bitwiseAnd(\GMP $first, \GMP $other): \GMP;

    public function bitwiseXor(\GMP $first, \GMP $other): \GMP;

    public function rightShift(\GMP $number, int $positions): \GMP;

    public function leftShift(\GMP $number, int $positions): \GMP;

    public function toString(\GMP $value): string;

    public function hexDec(string $hexString): string;

    public function decHex(string $decString): string;

    public function powmod(\GMP $base, \GMP $exponent, \GMP $modulus): \GMP;

    public function isPrime(\GMP $n): bool;

    public function nextPrime(\GMP $currentPrime): \GMP;

    public function inverseMod(\GMP $a, \GMP $m): \GMP;

    public function jacobi(\GMP $a, \GMP $p): int;

    public function intToString(\GMP $x): string;

    public function intToFixedSizeString(\GMP $x, int $byteSize): string;

    public function stringToInt(string $s): \GMP;

    public function digestInteger(\GMP $m): \GMP;

    public function gcd2(\GMP $a, \GMP $m): \GMP;

    public function baseConvert(string $value, int $fromBase, int $toBase): string;

    public function getNumberTheory(): NumberTheory;

    public function getModularArithmetic(\GMP $modulus): ModularArithmetic;
}
