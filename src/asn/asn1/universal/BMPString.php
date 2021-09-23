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

use polypay\asn\asn1\AbstractString;
use polypay\asn\asn1\Identifier;

class BMPString extends AbstractString
{
    /**
     * Creates a new ASN.1 BMP String.
     *
     * BMPString is a subtype of UniversalString that has its own
     * unique tag and contains only the characters in the
     * Basic Multilingual Plane (those corresponding to the first
     * 64K-2 cells, less cells whose encoding is used to address
     * characters outside the Basic Multilingual Plane) of ISO/IEC 10646-1.
     *
     * TODO The encodable characters of this type are not yet checked.
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->value = $string;
        $this->allowAll();
    }

    public function getType()
    {
        return Identifier::BMP_STRING;
    }
}
