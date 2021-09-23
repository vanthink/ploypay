<?php
declare(strict_types=1);

namespace polypay\ecc\primitives;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\crypto\key\PrivateKeyInterface;
use polypay\ecc\crypto\key\PublicKeyInterface;
use polypay\ecc\crypto\key\PrivateKey;
use polypay\ecc\crypto\key\PublicKey;

use polypay\ecc\math\GmpMathInterface;

use polypay\ecc\random\RandomGeneratorFactory;
use polypay\ecc\random\RandomNumberGeneratorInterface;

class GeneratorPoint extends Point
{
  
    private $generator;

    public function __construct(
        GmpMathInterface $adapter,
        CurveFpInterface $curve,
        \GMP $x,
        \GMP $y,
        \GMP $order,
        RandomNumberGeneratorInterface $generator = null
    ) {
        $this->generator = $generator ?: RandomGeneratorFactory::getRandomGenerator();
        parent::__construct($adapter, $curve, $x, $y, $order);
    }

   
    public function isValid(\GMP $x, \GMP $y): bool
    {
       
        $math = $this->getAdapter();

        $n = $this->getOrder();
        $zero = gmp_init(0, 10);
        $curve = $this->getCurve();

        if ($math->cmp($x, $zero) < 0 || $math->cmp($n, $x) <= 0 || $math->cmp($y, $zero) < 0 || $math->cmp($n, $y) <= 0) {
            return false;
        }

        if (! $curve->contains($x, $y)) {
            return false;
        }

        $point = $curve->getPoint($x, $y)->mul($n);

        if (! $point->isInfinity()) {
            return false;
        }

        return true;
    }

    public function createPrivateKey(): PrivateKeyInterface
    {
        $secret = $this->generator->generate($this->getOrder());
        return new PrivateKey($this->getAdapter(), $this, $secret);
    }

    public function getPublicKeyFrom(\GMP $x, \GMP $y): PublicKeyInterface
    {
        $pubPoint = $this->getCurve()->getPoint($x, $y, $this->getOrder());
        return new PublicKey($this->getAdapter(), $this, $pubPoint);
    }

    public function getPrivateKeyFrom(\GMP $secretMultiplier): PrivateKeyInterface
    {
        return new PrivateKey($this->getAdapter(), $this, $secretMultiplier);
    }
}
