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

use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\Identifier;
use polypay\asn\asn1\universal\Sequence;
use polypay\ecc\crypto\key\PublicKeyInterface;
use polypay\ecc\math\GmpMathInterface;
use polypay\ecc\serializer\util\CurveOidMapper;
use polypay\ecc\primitives\GeneratorPoint;
use polypay\ecc\serializer\publickey\DerPublicKeySerializer;
use polypay\ecc\serializer\point\PointSerializerInterface;
use polypay\ecc\serializer\point\UncompressedPointSerializer;
use polypay\ecc\srypto\key\PublicKey;

class Parser
{

    private $adapter;

    private $pointSerializer;

    public function __construct(GmpMathInterface $adapter, PointSerializerInterface $pointSerializer = null)
    {
        $this->adapter = $adapter;
        $this->pointSerializer = $pointSerializer ?: new UncompressedPointSerializer();
    }

    
    public function parse(string $binaryData): PublicKeyInterface
    {
        $asnObject = ASNObject::fromBinary($binaryData);
        if ($asnObject->getType() !== Identifier::SEQUENCE) {
            throw new \RuntimeException('Invalid data.');
        }

        /** @var Sequence $asnObject */
        if ($asnObject->getNumberofChildren() != 2) {
            throw new \RuntimeException('Invalid data.');
        }

        $children = $asnObject->getChildren();
        if (count($children) != 2) {
            throw new \RuntimeException('Invalid data.');
        }

        if (count($children) != 2) {
            throw new \RuntimeException('Invalid data.');
        }

        if ($children[0]->getType() !== Identifier::SEQUENCE) {
            throw new \RuntimeException('Invalid data.');
        }

        if (count($children[0]->getChildren()) != 2) {
            throw new \RuntimeException('Invalid data.');
        }

        if ($children[0]->getChildren()[0]->getType() !== Identifier::OBJECT_IDENTIFIER) {
            throw new \RuntimeException('Invalid data.');
        }

        if ($children[0]->getChildren()[1]->getType() !== Identifier::OBJECT_IDENTIFIER) {
            throw new \RuntimeException('Invalid data.');
        }

        if ($children[1]->getType() !== Identifier::BITSTRING) {
            throw new \RuntimeException('Invalid data.');
        }

        $oid = $children[0]->getChildren()[0];
        $curveOid = $children[0]->getChildren()[1];
        $encodedKey = $children[1];
        if ($oid->getContent() !== DerPublicKeySerializer::X509_ECDSA_OID) {
            throw new \RuntimeException('Invalid data: non X509 data.');
        }

        $generator = CurveOidMapper::getGeneratorFromOid($curveOid);

        return $this->parseKey($generator, $encodedKey->getContent());
    }

    public function parseKey(GeneratorPoint $generator, string $data): PublicKeyInterface
    {
        $point = $this->pointSerializer->unserialize($generator->getCurve(), $data);

        return new PublicKey($this->adapter, $generator, $point);
    }
}
