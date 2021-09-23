<?php
declare(strict_types=1);

namespace polypay\ecc\crypto\ecdh;

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
use polypay\ecc\exception\ExchangeException;
use polypay\ecc\math\GmpMathInterface;

class EcDH implements EcDHInterface
{

    private $adapter;

    private $secretKey = null;

    private $recipientKey;

    private $senderKey;

    public function __construct(GmpMathInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function calculateSharedKey(): \GMP
    {
        $this->calculateKey();

        return $this->secretKey->getPoint()->getX();
    }

    public function createMultiPartyKey(): PublicKeyInterface
    {
        $this->calculateKey();

        return $this->secretKey;
    }

    public function setRecipientKey(PublicKeyInterface $key = null)
    {
        $this->recipientKey = $key;
        return $this;
    }

    public function setSenderKey(PrivateKeyInterface $key)
    {
        $this->senderKey = $key;
        return $this;
    }

    private function calculateKey()
    {
        $this->checkExchangeState();

        if ($this->secretKey === null) {
            try {
                // Multiply our secret with recipients public key
                $point = $this->recipientKey->getPoint()->mul($this->senderKey->getSecret());

                // Ensure we completed a valid exchange, ensure we can create a
                // public key instance for the shared secret using our generator.
                $this->secretKey = $this->senderKey->getPoint()->getPublicKeyFrom($point->getX(), $point->getY());
            } catch (\Exception $e) {
                throw new ExchangeException("Invalid ECDH exchange", 0, $e);
            }
        }
    }

    private function checkExchangeState()
    {
        if ($this->secretKey !== null) {
            return;
        }

        if ($this->senderKey === null) {
            throw new ExchangeException('Sender key not set.');
        }

        if ($this->recipientKey === null) {
            throw new ExchangeException('Recipient key not set.');
        }

        // Check the point exists on our curve.
        $point = $this->recipientKey->getPoint();
        if (!$this->senderKey->getPoint()->getCurve()->contains($point->getX(), $point->getY())) {
            throw new ExchangeException("Invalid ECDH exchange - Point does not exist on our curve");
        }
    }
}
