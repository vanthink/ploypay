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

class NumericString extends AbstractString
{
    /**
     * Creates a new ASN.1 NumericString.
     *
     * The following characters are permitted:
     * Digits                0,1, ... 9
     * SPACE                 (space)
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->value = $string;
        $this->allowNumbers();
        $this->allowSpaces();
    }

    public function getType()
    {
        return Identifier::NUMERIC_STRING;
    }
}
