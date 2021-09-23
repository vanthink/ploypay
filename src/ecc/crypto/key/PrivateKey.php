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


use polypay\ecc\crypto\ecdh\EcDH;
use polypay\ecc\crypto\ecdh\EcDHInterface;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\primitives\CurveFpInterface;
use polypay\ecc\primitives\GeneratorPoint;
use polypay\ecc\primitives\PointInterface;

/**
 * This class serves as public - private key exchange for signature verification.
 */
class PrivateKey implements PrivateKeyInterface
{
    /**
     * @var GeneratorPoint
     */
    private $generator;

    /**
     * @var \GMP
     */
    private $secretMultiplier;

    /**
     * @var GmpMathInterface
     */
    private $adapter;

    /**
     * @param GmpMathInterface $adapter
     * @param GeneratorPoint $generator
     * @param \GMP $secretMultiplier
     */
    public function __construct(GmpMathInterface $adapter, GeneratorPoint $generator, \GMP $secretMultiplier)
    {
        $this->adapter = $adapter;
        $this->generator = $generator;
        $this->secretMultiplier = $secretMultiplier;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PrivateKeyInterface::getPublicKey()
     */
    public function getPublicKey(): PublicKeyInterface
    {
        return new PublicKey($this->adapter, $this->generator, $this->generator->mul($this->secretMultiplier));
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PrivateKeyInterface::getPoint()
     */
    public function getPoint(): GeneratorPoint
    {
        return $this->generator;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PrivateKeyInterface::getCurve()
     */
    public function getCurve(): CurveFpInterface
    {
        return $this->generator->getCurve();
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PrivateKeyInterface::getSecret()
     */
    public function getSecret(): \GMP
    {
        return $this->secretMultiplier;
    }

    /**
     * {@inheritDoc}
     * @see \polypay\ecc\Crypto\Key\PrivateKeyInterface::createExchange()
     */
    public function createExchange(PublicKeyInterface $recipient = null): EcDHInterface
    {
        $ecdh = new EcDH($this->adapter);
        $ecdh
            ->setSenderKey($this)
            ->setRecipientKey($recipient);

        return $ecdh;
    }
}
