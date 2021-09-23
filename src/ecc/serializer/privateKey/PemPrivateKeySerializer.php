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

class PemPrivateKeySerializer implements PrivateKeySerializerInterface
{

    private $derSerializer;

    public function __construct(DerPrivateKeySerializer $derSerializer)
    {
        $this->derSerializer = $derSerializer;
    }

    public function serialize(PrivateKeyInterface $key): string
    {
        $privateKeyInfo = $this->derSerializer->serialize($key);

        $content  = '-----BEGIN EC PRIVATE KEY-----'.PHP_EOL;
        $content .= trim(chunk_split(base64_encode($privateKeyInfo), 64, PHP_EOL)).PHP_EOL;
        $content .= '-----END EC PRIVATE KEY-----';

        return $content;
    }

    public function parse(string $formattedKey): PrivateKeyInterface
    {
        $formattedKey = str_replace('-----BEGIN EC PRIVATE KEY-----', '', $formattedKey);
        $formattedKey = str_replace('-----END EC PRIVATE KEY-----', '', $formattedKey);

        $data = base64_decode($formattedKey);

        return $this->derSerializer->parse($data);
    }
}
