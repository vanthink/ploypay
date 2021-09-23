<?php
declare(strict_types=1);

// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

namespace polypay\ecc\primitives;

use polypay\ecc\exception\PointRecoveryException;
use polypay\ecc\exception\SquareRootException;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\math\ModularArithmetic;
use polypay\ecc\random\RandomNumberGeneratorInterface;

class CurveFp implements CurveFpInterface
{

    protected $parameters;

    protected $adapter = null;

    protected $modAdapter = null;

    public function __construct(CurveParameters $parameters, GmpMathInterface $adapter)
    {
        $this->parameters = $parameters;
        $this->adapter = $adapter;
        $this->modAdapter = new ModularArithmetic($this->adapter, $this->parameters->getPrime());
    }

    public function getModAdapter(): ModularArithmetic
    {
        return $this->modAdapter;
    }

    public function getPoint(\GMP $x, \GMP $y, \GMP $order = null): PointInterface
    {
        return new Point($this->adapter, $this, $x, $y, $order);
    }
    
    public function getInfinity(): PointInterface
    {
        return new Point($this->adapter, $this, gmp_init(0, 10), gmp_init(0, 10), null, true);
    }

    public function getGenerator(\GMP $x, \GMP $y, \GMP $order, RandomNumberGeneratorInterface $randomGenerator = null): GeneratorPoint
    {
        return new GeneratorPoint($this->adapter, $this, $x, $y, $order, $randomGenerator);
    }

    public function recoverYfromX(bool $wasOdd, \GMP $xCoord): \GMP
    {
        $math = $this->adapter;
        $prime = $this->getPrime();

        try {
            $root = $this->adapter->getNumberTheory()->squareRootModP(
                $math->add(
                    $math->add(
                        $this->modAdapter->pow($xCoord, gmp_init(3, 10)),
                        $math->mul($this->getA(), $xCoord)
                    ),
                    $this->getB()
                ),
                $prime
            );
        } catch (SquareRootException $e) {
            throw new PointRecoveryException("Failed to recover y coordinate for point", 0, $e);
        }

        if ($math->equals($math->mod($root, gmp_init(2, 10)), gmp_init(1)) === $wasOdd) {
            return $root;
        } else {
            return $math->sub($prime, $root);
        }
    }
    
    public function contains(\GMP $x, \GMP $y): bool
    {
        $math = $this->adapter;

        $eq_zero = $math->equals(
            $this->modAdapter->sub(
                $math->pow($y, 2),
                $math->add(
                    $math->add(
                        $math->pow($x, 3),
                        $math->mul($this->getA(), $x)
                    ),
                    $this->getB()
                )
            ),
            gmp_init(0, 10)
        );

        return $eq_zero;
    }

    
    public function getA(): \GMP
    {
        return $this->parameters->getA();
    }

    public function getB(): \GMP
    {
        return $this->parameters->getB();
    }

    public function getPrime(): \GMP
    {
        return $this->parameters->getPrime();
    }

    public function getSize(): int
    {
        return $this->parameters->getSize();
    }

    public function cmp(CurveFpInterface $other): int
    {
        $math = $this->adapter;

        $equal  = $math->equals($this->getA(), $other->getA());
        $equal &= $math->equals($this->getB(), $other->getB());
        $equal &= $math->equals($this->getPrime(), $other->getPrime());

        return ($equal) ? 0 : 1;
    }

    public function equals(CurveFpInterface $other): bool
    {
        return $this->cmp($other) == 0;
    }

    public function __toString(): string
    {
        return 'curve(' . $this->adapter->toString($this->getA()) . ', ' . $this->adapter->toString($this->getB()) . ', ' . $this->adapter->toString($this->getPrime()) . ')';
    }

    public function __debugInfo()
    {
        return [
            'a' => $this->adapter->toString($this->getA()),
            'b' => $this->adapter->toString($this->getB()),
            'prime' => $this->adapter->toString($this->getPrime())
        ];
    }
}
