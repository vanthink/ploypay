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

class UnknownObject extends ASNObject
{
    /** @var string */
    private $value;

    private $identifier;

    /**
     * @param string|int $identifier Either the first identifier octet as int or all identifier bytes as a string
     * @param int        $contentLength
     */
    public function __construct($identifier, $contentLength)
    {
        if (is_int($identifier)) {
            $identifier = chr($identifier);
        }

        $this->identifier = $identifier;
        $this->value = "Unparsable Object ({$contentLength} bytes)";
        $this->setContentLength($contentLength);
    }

    public function getContent()
    {
        return $this->value;
    }

    public function getType()
    {
        return ord($this->identifier[0]);
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    protected function calculateContentLength()
    {
        return $this->getContentLength();
    }

    protected function getEncodedValue()
    {
        return '';
    }
}
