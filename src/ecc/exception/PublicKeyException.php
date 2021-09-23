<?php
declare(strict_types=1);

namespace polypay\ecc\exception;
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
use polypay\ecc\primitives\PointInterface;
use Throwable;

class PublicKeyException extends \RuntimeException
{
    /**
     * @var GeneratorPoint
     */
    private $G;

    /**
     * @var PointInterface
     */
    private $point;

    public function __construct(GeneratorPoint $G, PointInterface $point, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->G = $G;
        $this->point = $point;
        parent::__construct($message, $code, $previous);
    }

    public function getGenerator(): GeneratorPoint
    {
        return $this->G;
    }

    public function getPoint(): PointInterface
    {
        return $this->point;
    }
}
