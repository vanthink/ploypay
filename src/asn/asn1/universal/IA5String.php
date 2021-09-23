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

/**
 * The International Alphabet No.5 (IA5) references the encoding of the ASCII characters.
 *
 * Each character in the data is encoded as 1 byte.
 */
class IA5String extends AbstractString
{
    public function __construct($string)
    {
        parent::__construct($string);
        for ($i = 1; $i < 128; $i++) {
            $this->allowCharacter(chr($i));
        }
    }

    public function getType()
    {
        return Identifier::IA5_STRING;
    }
}
