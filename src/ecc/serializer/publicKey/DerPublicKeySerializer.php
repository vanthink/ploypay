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
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\math\MathAdapterFactory;
use polypay\ecc\serializer\point\PointSerializerInterface;
use polypay\ecc\serializer\point\UncompressedPointSerializer;
use polypay\ecc\serializer\publicKey\der\Formatter;
use polypay\ecc\serializer\publicKey\der\Parser;

class DerPublicKeySerializer implements PublicKeySerializerInterface
{

    const X509_ECDSA_OID = '1.2.840.10045.2.1';

    private $adapter;

    private $formatter;

    private $parser;

    public function __construct(GmpMathInterface $adapter = null, PointSerializerInterface $pointSerializer = null)
    {
        $this->adapter = $adapter ?: MathAdapterFactory::getAdapter();

        $this->formatter = new Formatter();
        $this->parser = new Parser($this->adapter, $pointSerializer ?: new UncompressedPointSerializer());
    }

    public function serialize(PublicKeyInterface $key): string
    {
        return $this->formatter->format($key);
    }

    public function getUncompressedKey(PublicKeyInterface $key): string
    {
        return $this->formatter->encodePoint($key->getPoint());
    }

    public function parse(string $string): PublicKeyInterface
    {
        return $this->parser->parse($string);
    }
}
