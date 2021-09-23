<?php
namespace polypay\asn\x509\san;
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
use polypay\asn\asn1\Parsable;
use polypay\asn\asn1\exception\ParserException;

class IPAddress extends ASNObject implements Parsable
{
    const IDENTIFIER = 0x87; // not sure yet why this is the identifier used in SAN extensions

    /** @var string */
    private $value;

    public function __construct($ipAddressString)
    {
        $this->value = $ipAddressString;
    }

    public function getType()
    {
        return self::IDENTIFIER;
    }

    public function getContent()
    {
        return $this->value;
    }

    protected function calculateContentLength()
    {
        return 4;
    }

    protected function getEncodedValue()
    {
        $ipParts = explode('.', $this->value);
        $binary  = chr($ipParts[0]);
        $binary .= chr($ipParts[1]);
        $binary .= chr($ipParts[2]);
        $binary .= chr($ipParts[3]);

        return $binary;
    }

    public static function fromBinary(&$binaryData, &$offsetIndex = 0)
    {
        self::parseIdentifier($binaryData[$offsetIndex], self::IDENTIFIER, $offsetIndex++);
        $contentLength = self::parseContentLength($binaryData, $offsetIndex);
        if ($contentLength != 4) {
            throw new ParserException("A FG\\X509\SAN\IPAddress should have a content length of 4. Extracted length was {$contentLength}", $offsetIndex);
        }

        $ipAddressString = ord($binaryData[$offsetIndex++]).'.';
        $ipAddressString .= ord($binaryData[$offsetIndex++]).'.';
        $ipAddressString .= ord($binaryData[$offsetIndex++]).'.';
        $ipAddressString .= ord($binaryData[$offsetIndex++]);

        $parsedObject = new self($ipAddressString);
        $parsedObject->getObjectLength();

        return $parsedObject;
    }
}
