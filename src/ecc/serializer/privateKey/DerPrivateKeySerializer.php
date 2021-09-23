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

use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\Integer;
use polypay\asn\asn1\universal\BitString;
use polypay\asn\asn1\universal\OctetString;
use polypay\asn\asn1\ExplicitlyTaggedObject;

use polypay\ecc\crypto\key\PrivateKeyInterface;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\math\MathAdapterFactory;
use polypay\ecc\serializer\util\CurveOidMapper;
use polypay\ecc\serializer\publicKey\DerPublicKeySerializer;

class DerPrivateKeySerializer implements PrivateKeySerializerInterface
{

    const VERSION = 1;

    private $adapter;
    private $pubKeySerializer;

    public function __construct(GmpMathInterface $adapter = null, DerPublicKeySerializer $pubKeySerializer = null)
    {
        $this->adapter = $adapter ?: MathAdapterFactory::getAdapter();
        $this->pubKeySerializer = $pubKeySerializer ?: new DerPublicKeySerializer($this->adapter);
    }

    public function serialize(PrivateKeyInterface $key): string
    {
        // var_dump($this->formatKey($key));
        $privateKeyInfo = new Sequence(
            new Integer(self::VERSION),
            new OctetString($this->formatKey($key)), //私钥16进制
            new ExplicitlyTaggedObject(0, CurveOidMapper::getCurveOid($key->getPoint()->getCurve())),
            new ExplicitlyTaggedObject(1, $this->encodePubKey($key)) //this->encodePubKey($key), 公钥16进制
        );
        // var_dump($privateKeyInfo);
        return $privateKeyInfo->getBinary();
    }

    private function encodePubKey(PrivateKeyInterface $key): BitString
    {
        return new BitString(
            $this->pubKeySerializer->getUncompressedKey($key->getPublicKey())
        );
    }

    private function formatKey(PrivateKeyInterface $key): string
    {
        return gmp_strval($key->getSecret(), 16);
    }

    public function parse(string $data): PrivateKeyInterface
    {
        $asnObject = ASNObject::fromBinary($data);

        if (! ($asnObject instanceof Sequence) || $asnObject->getNumberofChildren() !== 4) {
            throw new \RuntimeException('Invalid data.');
        }

        $children = $asnObject->getChildren();

        $version = $children[0];

        if ($version->getContent() != 1) {
            throw new \RuntimeException('Invalid data: only version 1 (RFC5915) keys are supported.');
        }

        $key = gmp_init($children[1]->getContent(), 16);
        $oid = $children[2]->getContent()[0];
        $generator = CurveOidMapper::getGeneratorFromOid($oid);

        return $generator->getPrivateKeyFrom($key);
    }
}
