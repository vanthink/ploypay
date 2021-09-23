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


use polypay\ecc\exception\PublicKeyException;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\primitives\CurveFpInterface;
use polypay\ecc\primitives\GeneratorPoint;
use polypay\ecc\primitives\PointInterface;

/**
 * This class serves as public- private key exchange for signature verification
 */
class PublicKey implements PublicKeyInterface
{
    /**
     *
     * @var CurveFpInterface
     */
    private $curve;

    /**
     *
     * @var GeneratorPoint
     */
    private $generator;

    /**
     *
     * @var PointInterface
     */
    private $point;

    /**
     *
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * Initialize a new PublicKey instance.
     *
     * @param  GmpMathInterface  $adapter
     * @param  GeneratorPoint    $generator
     * @param  PointInterface    $point
     */
    public function __construct(GmpMathInterface $adapter, GeneratorPoint $generator, PointInterface $point)
    {
        $this->curve = $generator->getCurve();
        $this->generator = $generator;
        $this->point = $point;
        $this->adapter = $adapter;

        // step 1: not point at infinity.
        if ($point->isInfinity()) {
            throw new PublicKeyException($generator, $point, "Cannot use point at infinity for public key");
        }

        // step 2 full & partial public key validation routine
        if ($adapter->cmp($point->getX(), gmp_init(0, 10)) < 0 || $adapter->cmp($this->curve->getPrime(), $point->getX()) < 0
            || $adapter->cmp($point->getY(), gmp_init(0, 10)) < 0 || $adapter->cmp($this->curve->getPrime(), $point->getY()) < 0
        ) {
            throw new PublicKeyException($generator, $point, "Point has x and y out of range.");
        }

        // Sanity check. Point (x,y) values are qualified against it's
        // generator and curve. Here we ensure the Point and Generator
        // are the same.
        if (!$generator->getCurve()->equals($point->getCurve())) {
            throw new PublicKeyException($generator, $point, "Curve for given point not in common with GeneratorPoint");
        }
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PublicKeyInterface::getCurve()
     */
    public function getCurve(): CurveFpInterface
    {
        return $this->curve;
    }

    /**
     * {$inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PublicKeyInterface::getGenerator()
     */
    public function getGenerator(): GeneratorPoint
    {
        return $this->generator;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PublicKeyInterface::getPoint()
     */
    public function getPoint(): PointInterface
    {
        return $this->point;
    }
}
