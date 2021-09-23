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

class PemPublicKeySerializer implements PublicKeySerializerInterface
{

    private $derSerializer;

    public function __construct(DerPublicKeySerializer $serializer)
    {
        $this->derSerializer = $serializer;
    }

    public function serialize(PublicKeyInterface $key): string
    {
        $publicKeyInfo = $this->derSerializer->serialize($key);

        $content  = '-----BEGIN PUBLIC KEY-----'.PHP_EOL;
        $content .= trim(chunk_split(base64_encode($publicKeyInfo), 64, PHP_EOL)).PHP_EOL;
        $content .= '-----END PUBLIC KEY-----';

        return $content;
    }

    public function parse(string $formattedKey): PublicKeyInterface
    {
        $formattedKey = str_replace('-----BEGIN PUBLIC KEY-----', '', $formattedKey);
        $formattedKey = str_replace('-----END PUBLIC KEY-----', '', $formattedKey);
        
        $data = base64_decode($formattedKey);

        return $this->derSerializer->parse($data);
    }
}
