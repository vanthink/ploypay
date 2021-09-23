<?php

declare(strict_types=1);

namespace polypay\ecc\serializer\signature;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\crypto\signature\SignatureInterface;

interface DerSignatureSerializerInterface
{

    public function serialize(SignatureInterface $signature): string;

    public function parse(string $binary): SignatureInterface;
}
