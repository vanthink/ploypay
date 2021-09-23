<?php
namespace polypay\asn\x509\csr;
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
use polypay\asn\x509\CertificateExtensions;
use polypay\asn\asn1\OID;
use polypay\asn\asn1\Parsable;
use polypay\asn\asn1\Construct;
use polypay\asn\asn1\Identifier;
use polypay\asn\asn1\universal\Set;
use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\ObjectIdentifier;

class Attributes extends Construct implements Parsable
{
    public function getType()
    {
        return 0xA0;
    }

    public function addAttribute($objectIdentifier, Set $attribute)
    {
        if (is_string($objectIdentifier)) {
            $objectIdentifier = new ObjectIdentifier($objectIdentifier);
        }
        $attributeSequence = new Sequence($objectIdentifier, $attribute);
        $attributeSequence->getNumberOfLengthOctets();  // length and number of length octets is calculated
        $this->addChild($attributeSequence);
    }

    public static function fromBinary(&$binaryData, &$offsetIndex = 0)
    {
        self::parseIdentifier($binaryData[$offsetIndex], 0xA0, $offsetIndex++);
        $contentLength = self::parseContentLength($binaryData, $offsetIndex);
        $octetsToRead = $contentLength;

        $parsedObject = new self();
        while ($octetsToRead > 0) {
            $initialOffset = $offsetIndex; // used to calculate how much bits have been read
            self::parseIdentifier($binaryData[$offsetIndex], Identifier::SEQUENCE, $offsetIndex++);
            self::parseContentLength($binaryData, $offsetIndex);

            $objectIdentifier = ObjectIdentifier::fromBinary($binaryData, $offsetIndex);
            $oidString = $objectIdentifier->getContent();
            if ($oidString == OID::PKCS9_EXTENSION_REQUEST) {
                $attribute = CertificateExtensions::fromBinary($binaryData, $offsetIndex);
            } else {
                $attribute = ASNObject::fromBinary($binaryData, $offsetIndex);
            }

            $parsedObject->addAttribute($objectIdentifier, $attribute);
            $octetsToRead -= ($offsetIndex - $initialOffset);
        }

        $parsedObject->setContentLength($contentLength);

        return $parsedObject;
    }
}
