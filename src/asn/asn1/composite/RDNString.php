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

use polypay\asn\asn1\universal\PrintableString;
use polypay\asn\asn1\universal\IA5String;
use polypay\asn\asn1\universal\UTF8String;

class RDNString extends RelativeDistinguishedName
{

    public function __construct($objectIdentifierString, $value)
    {
        if (PrintableString::isValid($value)) {
            $value = new PrintableString($value);
        } else {
            if (IA5String::isValid($value)) {
                $value = new IA5String($value);
            } else {
                $value = new UTF8String($value);
            }
        }

        parent::__construct($objectIdentifierString, $value);
    }
}
