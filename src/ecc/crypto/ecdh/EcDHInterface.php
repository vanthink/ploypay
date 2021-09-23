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

use polypay\ecc\crypto\key\PublicKeyInterface;
use polypay\ecc\crypto\key\PrivateKeyInterface;

interface EcDHInterface
{

   
    public function calculateSharedKey(): \GMP;

    public function createMultiPartyKey(): PublicKeyInterface;

    public function setSenderKey(PrivateKeyInterface $key);
   
    public function setRecipientKey(PublicKeyInterface $key);
}
