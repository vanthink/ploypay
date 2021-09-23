<?php
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------


namespace polypay\asn\x509;

use polypay\asn\asn1\exception\ParserException;
use polypay\asn\asn1\OID;
use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\Parsable;
use polypay\asn\asn1\Identifier;
use polypay\asn\asn1\universal\OctetString;
use polypay\asn\asn1\universal\Set;
use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\ObjectIdentifier;
use polypay\asn\x509\san\SubjectAlternativeNames;

class CertificateExtensions extends Set implements Parsable
{
    private $innerSequence;
    private $extensions = [];

    public function __construct()
    {
        $this->innerSequence = new Sequence();
        parent::__construct($this->innerSequence);
    }

    public function addSubjectAlternativeNames(SubjectAlternativeNames $sans)
    {
        $this->addExtension(OID::CERT_EXT_SUBJECT_ALT_NAME, $sans);
    }

    private function addExtension($oidString, ASNObject $extension)
    {
        $sequence = new Sequence();
        $sequence->addChild(new ObjectIdentifier($oidString));
        $sequence->addChild($extension);

        $this->innerSequence->addChild($sequence);
        $this->extensions[] = $extension;
    }

    public function getContent()
    {
        return $this->extensions;
    }

    public static function fromBinary(&$binaryData, &$offsetIndex = 0)
    {
        self::parseIdentifier($binaryData[$offsetIndex], Identifier::SET, $offsetIndex++);
        self::parseContentLength($binaryData, $offsetIndex);

        $tmpOffset = $offsetIndex;
        $extensions = Sequence::fromBinary($binaryData, $offsetIndex);
        $tmpOffset += 1 + $extensions->getNumberOfLengthOctets();

        $parsedObject = new self();
        foreach ($extensions as $extension) {
            if ($extension->getType() != Identifier::SEQUENCE) {
                //FIXME wrong offset index
                throw new ParserException('Could not parse Certificate Extensions: Expected ASN.1 Sequence but got '.$extension->getTypeName(), $offsetIndex);
            }

            $tmpOffset += 1 + $extension->getNumberOfLengthOctets();
            $children = $extension->getChildren();
            if (count($children) < 2) {
                throw new ParserException('Could not parse Certificate Extensions: Needs at least two child elements per extension sequence (object identifier and octet string)', $tmpOffset);
            }
            /** @var \asn\asn1\ASNObject $objectIdentifier */
            $objectIdentifier = $children[0];

            /** @var OctetString $octetString */
            $octetString = $children[1];

            if ($objectIdentifier->getType() != Identifier::OBJECT_IDENTIFIER) {
                throw new ParserException('Could not parse Certificate Extensions: Expected ASN.1 Object Identifier but got '.$extension->getTypeName(), $tmpOffset);
            }

            $tmpOffset += $objectIdentifier->getObjectLength();

            if ($objectIdentifier->getContent() == OID::CERT_EXT_SUBJECT_ALT_NAME) {
                $sans = SubjectAlternativeNames::fromBinary($binaryData, $tmpOffset);
                $parsedObject->addSubjectAlternativeNames($sans);
            } else {
                // can now only parse SANs. There might be more in the future
                $tmpOffset += $octetString->getObjectLength();
            }
        }

        $parsedObject->getBinary(); // Determine the number of content octets and object sizes once (just to let the equality unit tests pass :/ )
        return $parsedObject;
    }
}
