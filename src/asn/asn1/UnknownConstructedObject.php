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


namespace polypay\asn\asn1;

class UnknownConstructedObject extends Construct
{
    private $identifier;
    private $contentLength;

    /**
     * @param string $binaryData
     * @param int $offsetIndex
     *
     * @throws \asn\asn1\Exception\ParserException
     */
    public function __construct($binaryData, &$offsetIndex)
    {
        $this->identifier = self::parseBinaryIdentifier($binaryData, $offsetIndex);
        $this->contentLength = self::parseContentLength($binaryData, $offsetIndex);

        $children = [];
        $octetsToRead = $this->contentLength;
        while ($octetsToRead > 0) {
            $newChild = ASNObject::fromBinary($binaryData, $offsetIndex);
            $octetsToRead -= $newChild->getObjectLength();
            $children[] = $newChild;
        }

        parent::__construct(...$children);
    }

    public function getType()
    {
        return ord($this->identifier);
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    protected function calculateContentLength()
    {
        return $this->contentLength;
    }

    protected function getEncodedValue()
    {
        return '';
    }
}
