<?php
declare(strict_types=1);

namespace polypay\ecc\serializer\publickey;
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

interface PublicKeySerializerInterface
{

    public function serialize(PublicKeyInterface $key): string;

    public function parse(string $formattedKey): PublicKeyInterface;
}
