<?php
namespace polypay\asn\x509\san;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------



use polypay\asn\asn1\universal\GeneralString;

class DNSName extends GeneralString
{
    const IDENTIFIER = 0x82; // not sure yet why this is the identifier used in SAN extensions

    public function __construct($dnsNameString)
    {
        parent::__construct($dnsNameString);
    }

    public function getType()
    {
        return self::IDENTIFIER;
    }
}
