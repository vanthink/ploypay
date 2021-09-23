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


namespace polypay\asn\asn1\composite;

use polypay\asn\asn1\exception\NotImplementedException;
use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\universal\Set;

class RelativeDistinguishedName extends Set
{

    public function __construct($objIdentifierString, ASNObject $value)
    {
        parent::__construct(new AttributeTypeAndValue($objIdentifierString, $value));
    }

    public function getContent()
    {
        $firstObject = $this->children[0];
        return $firstObject->__toString();
    }

    public static function fromBinary(&$binaryData, &$offsetIndex = 0)
    {
        throw new NotImplementedException();
    }
}
