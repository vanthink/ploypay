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


namespace polypay\asn\asn1\universal;

use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\Parsable;
use polypay\asn\asn1\Identifier;
use polypay\asn\asn1\exception\ParserException;

class NullObject extends ASNObject implements Parsable
{
    public function getType()
    {
        return Identifier::NULL;
    }

    protected function calculateContentLength()
    {
        return 0;
    }

    protected function getEncodedValue()
    {
        return null;
    }

    public function getContent()
    {
        return 'NULL';
    }

    public static function fromBinary(&$binaryData, &$offsetIndex = 0)
    {
        self::parseIdentifier($binaryData[$offsetIndex], Identifier::NULL, $offsetIndex++);
        $contentLength = self::parseContentLength($binaryData, $offsetIndex);

        if ($contentLength != 0) {
            throw new ParserException("An ASN.1 Null should not have a length other than zero. Extracted length was {$contentLength}", $offsetIndex);
        }

        $parsedObject = new self();
        $parsedObject->setContentLength(0);

        return $parsedObject;
    }
}
