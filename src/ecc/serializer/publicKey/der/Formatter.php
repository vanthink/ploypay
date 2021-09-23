<?php
declare(strict_types=1);

namespace polypay\ecc\serializer\publicKey\der;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\ObjectIdentifier;
use polypay\asn\asn1\universal\BitString;
use polypay\ecc\primitives\PointInterface;
use polypay\ecc\crypto\key\PublicKeyInterface;
use polypay\ecc\curves\NamedCurveFp;
use polypay\ecc\serializer\util\CurveOidMapper;
use polypay\ecc\serializer\publickey\DerPublicKeySerializer;
use polypay\ecc\serializer\point\PointSerializerInterface;
use polypay\ecc\serializer\point\UncompressedPointSerializer;

class Formatter
{
    private $pointSerializer;

    public function __construct(PointSerializerInterface $pointSerializer = null)
    {
        $this->pointSerializer = $pointSerializer ?: new UncompressedPointSerializer();
    }

    public function format(PublicKeyInterface $key): string
    {
        if (! ($key->getCurve() instanceof NamedCurveFp)) {
            throw new \RuntimeException('Not implemented for unnamed curves');
        }

        $sequence = new Sequence(
            new Sequence(
                new ObjectIdentifier(DerPublicKeySerializer::X509_ECDSA_OID),
                CurveOidMapper::getCurveOid($key->getCurve())
            ),
            new BitString($this->encodePoint($key->getPoint()))
        );

        return $sequence->getBinary();
    }

    public function encodePoint(PointInterface $point): string
    {
        return $this->pointSerializer->serialize($point);
    }
}
