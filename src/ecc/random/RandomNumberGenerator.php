<?php
declare(strict_types=1);

namespace polypay\ecc\random;
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
use polypay\ecc\util\NumberSize;

class RandomNumberGenerator implements RandomNumberGeneratorInterface
{
    private $adapter;

    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function generate(\GMP $max): \GMP
    {
        $numBits = NumberSize::bnNumBits($this->adapter, $max);
        $numBytes = (int) ceil($numBits / 8);
        // Generate an integer of size >= $numBits
        $bytes = random_bytes($numBytes);
        $value = $this->adapter->stringToInt($bytes);

        $mask = gmp_sub(gmp_pow(2, $numBits), 1);
        $integer = gmp_and($value, $mask);
        return $integer;
    }
}
