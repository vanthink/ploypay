<?php
namespace polypay\asn\asn1\universal;

// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------



use polypay\asn\asn1\Construct;
use polypay\asn\asn1\Parsable;
use polypay\asn\asn1\Identifier;

class Sequence extends Construct implements Parsable
{
    public function getType()
    {
        return Identifier::SEQUENCE;
    }
}
