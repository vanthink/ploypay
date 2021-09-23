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


use polypay\ecc\crypto\ecdh\EcDHInterface;
use polypay\ecc\primitives\GeneratorPoint;

/**
 * This is a contract for the PrivateKey portion of ECDSA.
 */
interface PrivateKeyInterface
{

    /**
     * @return PublicKeyInterface
     */
    public function getPublicKey(): PublicKeyInterface;

    /**
     * @return GeneratorPoint
     */
    public function getPoint(): GeneratorPoint;

    /**
     * @return \GMP
     */
    public function getSecret(): \GMP;

    /**
     * @param  PublicKeyInterface $recipient
     * @return EcDHInterface
     */
    public function createExchange(PublicKeyInterface $recipient): EcDHInterface;
}
