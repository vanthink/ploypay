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

use polypay\ecc\Exception\PointException;
use polypay\ecc\Exception\PointNotOnCurveException;
use polypay\ecc\Math\GmpMathInterface;
use polypay\ecc\Math\ModularArithmetic;

class Point implements PointInterface
{
    private $curve;
    private $adapter;
    private $modAdapter;
    private $x;
    private $y;
    private $order;
    private $infinity = false;

    public function __construct(GmpMathInterface $adapter, CurveFpInterface $curve, \GMP $x, \GMP $y, \GMP $order = null, bool $infinity = false)
    {
        $this->adapter    = $adapter;
        $this->modAdapter = $curve->getModAdapter();
        $this->curve      = $curve;
        $this->x          = $x;
        $this->y          = $y;
        $this->order      = $order !== null ? $order : gmp_init(0, 10);
        $this->infinity   = (bool) $infinity;
        if (! $infinity && ! $curve->contains($x, $y)) {
            throw new PointNotOnCurveException($x, $y, $curve);
        }

        if (!is_null($order)) {
            $mul = $this->mul($order);
            if (!$mul->isInfinity()) {
                throw new PointException("SELF * ORDER MUST EQUAL INFINITY. (" . (string)$mul . " found instead)");
            }
        }
    }

    public function getAdapter(): GmpMathInterface
    {
        return $this->adapter;
    }

    public function isInfinity(): bool
    {
        return (bool) $this->infinity;
    }

    public function getCurve(): CurveFpInterface
    {
        return $this->curve;
    }

    public function getOrder(): \GMP
    {
        return $this->order;
    }

    public function getX(): \GMP
    {
        return $this->x;
    }

    public function getY(): \GMP
    {
        return $this->y;
    }

    public function add(PointInterface $addend): PointInterface
    {
        if (! $this->curve->equals($addend->getCurve())) {
            throw new \RuntimeException("The Elliptic Curves do not match.");
        }

        if ($addend->isInfinity()) {
            return clone $this;
        }

        if ($this->isInfinity()) {
            return clone $addend;
        }

        $math = $this->adapter;
        $modMath = $this->modAdapter;

        if ($math->equals($addend->getX(), $this->x)) {
            if ($math->equals($addend->getY(), $this->y)) {
                return $this->getDouble();
            } else {
                return $this->curve->getInfinity();
            }
        }

        $slope = $modMath->div(
            $math->sub($addend->getY(), $this->y),
            $math->sub($addend->getX(), $this->x)
        );

        $xR = $modMath->sub(
            $math->sub($math->pow($slope, 2), $this->x),
            $addend->getX()
        );

        $yR = $modMath->sub(
            $math->mul($slope, $math->sub($this->x, $xR)),
            $this->y
        );

        return $this->curve->getPoint($xR, $yR, $this->order);
    }

    public function cmp(PointInterface $other): int
    {
        if ($other->isInfinity() && $this->isInfinity()) {
            return 0;
        }

        if ($other->isInfinity() || $this->isInfinity()) {
            return 1;
        }

        $math = $this->adapter;
        $equal = ($math->equals($this->x, $other->getX()));
        $equal &= ($math->equals($this->y, $other->getY()));
        $equal &= $this->isInfinity() == $other->isInfinity();
        $equal &= $this->curve->equals($other->getCurve());

        if ($equal) {
            return 0;
        }

        return 1;
    }

    public function equals(PointInterface $other): bool
    {
        return $this->cmp($other) == 0;
    }

    public function mul(\GMP $n): PointInterface
    {
        if ($this->isInfinity()) {
            return $this->curve->getInfinity();
        }

        $zero = gmp_init(0, 10);
        if ($this->adapter->cmp($this->order, $zero) > 0) {
            $n = $this->adapter->mod($n, $this->order);
        }

        if ($this->adapter->equals($n, $zero)) {
            return $this->curve->getInfinity();
        }

        /** @var Point[] $r */
        $r = [
            $this->curve->getInfinity(),
            clone $this
        ];

        $k = $this->curve->getSize();
        $n = str_pad($this->adapter->baseConvert($this->adapter->toString($n), 10, 2), $k, '0', STR_PAD_LEFT);

        for ($i = 0; $i < $k; $i++) {
            $j = $n[$i];

            $this->cswap($r[0], $r[1], $j ^ 1, $k);

            $r[0] = $r[0]->add($r[1]);
            $r[1] = $r[1]->getDouble();

            $this->cswap($r[0], $r[1], $j ^ 1, $k);
        }

        $r[0]->validate();

        return $r[0];
    }

    private function cswap(self $a, self $b, int $cond, int $curveSize)
    {
        $this->cswapValue($a->x, $b->x, $cond, $curveSize);
        $this->cswapValue($a->y, $b->y, $cond, $curveSize);
        $this->cswapValue($a->order, $b->order, $cond, $curveSize);
        $this->cswapValue($a->infinity, $b->infinity, $cond, 8);
    }

    public function cswapValue(& $a, & $b, int $cond, int $maskBitSize)
    {
        $isGMP = is_object($a) && $a instanceof \GMP;

        $sa = $isGMP ? $a : gmp_init(intval($a), 10);
        $sb = $isGMP ? $b : gmp_init(intval($b), 10);

        $mask = str_pad('', $maskBitSize, (string) (1 - intval($cond)), STR_PAD_LEFT);
        $mask = gmp_init($mask, 2);

        $taA = $this->adapter->bitwiseAnd($sa, $mask);
        $taB = $this->adapter->bitwiseAnd($sb, $mask);

        $sa = $this->adapter->bitwiseXor($this->adapter->bitwiseXor($sa, $sb), $taB);
        $sb = $this->adapter->bitwiseXor($this->adapter->bitwiseXor($sa, $sb), $taA);
        $sa = $this->adapter->bitwiseXor($this->adapter->bitwiseXor($sa, $sb), $taB);

        $a = $isGMP ? $sa : (bool) gmp_strval($sa, 10);
        $b = $isGMP ? $sb : (bool) gmp_strval($sb, 10);
    }

    private function validate()
    {
        if (! $this->infinity && ! $this->curve->contains($this->x, $this->y)) {
            throw new \RuntimeException('Invalid point');
        }
    }

    public function getDouble(): PointInterface
    {
        if ($this->isInfinity()) {
            return $this->curve->getInfinity();
        }

        $math = $this->adapter;
        $modMath = $this->modAdapter;

        $a = $this->curve->getA();
        $threeX2 = $math->mul(gmp_init(3, 10), $math->pow($this->x, 2));

        $tangent = $modMath->div(
            $math->add($threeX2, $a),
            $math->mul(gmp_init(2, 10), $this->y)
        );

        $x3 = $modMath->sub(
            $math->pow($tangent, 2),
            $math->mul(gmp_init(2, 10), $this->x)
        );

        $y3 = $modMath->sub(
            $math->mul($tangent, $math->sub($this->x, $x3)),
            $this->y
        );

        return $this->curve->getPoint($x3, $y3, $this->order);
    }

    public function __toString(): string
    {
        if ($this->infinity) {
            return '[ (infinity) on ' . (string) $this->curve . ' ]';
        }

        return "[ (" . $this->adapter->toString($this->x) . "," . $this->adapter->toString($this->y) . ') on ' . (string) $this->curve . ' ]';
    }

    public function __debugInfo(): array
    {
        $info = [
            'x' => $this->adapter->toString($this->x),
            'y' => $this->adapter->toString($this->y),
            'n' => $this->adapter->toString($this->order),
            'curve' => $this->curve
        ];

        if ($this->infinity) {
            $info['x'] = 'inf (' . $info['x'] . ')';
            $info['y'] = 'inf (' . $info['y'] . ')';
            $info['z'] = 'inf (' . $info['z'] . ')';
        }

        return $info;
    }
}
