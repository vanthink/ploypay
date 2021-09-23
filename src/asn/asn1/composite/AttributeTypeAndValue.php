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

use polypay\asn\asn1\ASNObject;
use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\ObjectIdentifier;

class AttributeTypeAndValue extends Sequence
{

    public function __construct($objIdentifier, ASNObject $value)
    {
        if ($objIdentifier instanceof ObjectIdentifier == false) {
            $objIdentifier = new ObjectIdentifier($objIdentifier);
        }
        parent::__construct($objIdentifier, $value);
    }

    public function __toString()
    {
        return $this->children[0].': '.$this->children[1];
    }
}
