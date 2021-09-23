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

class PrintableString extends AbstractString
{
    /**
     * Creates a new ASN.1 PrintableString.
     *
     * The ITU-T X.680 Table 8 permits the following characters:
     * Latin capital letters A,B, ... Z
     * Latin small letters   a,b, ... z
     * Digits                0,1, ... 9
     * SPACE                 (space)
     * APOSTROPHE            '
     * LEFT PARENTHESIS      (
     * RIGHT PARENTHESIS     )
     * PLUS SIGN             +
     * COMMA                 ,
     * HYPHEN-MINUS          -
     * FULL STOP             .
     * SOLIDUS               /
     * COLON                 :
     * EQUALS SIGN           =
     * QUESTION MARK         ?
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->value = $string;
        $this->allowNumbers();
        $this->allowAllLetters();
        $this->allowSpaces();
        $this->allowCharacters("'", '(', ')', '+', '-', '.', ',', '/', ':', '=', '?');
    }

    public function getType()
    {
        return Identifier::PRINTABLE_STRING;
    }
}
