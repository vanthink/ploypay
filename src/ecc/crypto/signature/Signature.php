<?php
declare(strict_types=1);

namespace polypay\ecc\crypto\signature;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

class Signature implements SignatureInterface
{
    /**
     * @var \GMP
     */
    private $r;

    /**
     *
     * @var \GMP
     */
    private $s;

    /**
     * Initialize a new instance with values
     *
     * @param \GMP $r
     * @param \GMP $s
     */
    public function __construct(\GMP $r, \GMP $s)
    {
        $this->r = $r;
        $this->s = $s;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Signature\SignatureInterface::getR()
     */
    public function getR(): \GMP
    {
        return $this->r;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Signature\SignatureInterface::getS()
     */
    public function getS(): \GMP
    {
        return $this->s;
    }
}
