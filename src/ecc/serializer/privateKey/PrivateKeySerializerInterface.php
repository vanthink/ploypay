<?php
declare(strict_types=1);

namespace polypay\ecc\Serializer\PrivateKey;
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

interface PrivateKeySerializerInterface
{
    
    public function serialize(PrivateKeyInterface $key): string;

    public function parse(string $formattedKey): PrivateKeyInterface;
}
