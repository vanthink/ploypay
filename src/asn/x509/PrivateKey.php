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


namespace polypay\asn\x509;

use polypay\asn\asn1\OID;
use polypay\asn\asn1\universal\NullObject;
use polypay\asn\asn1\universal\Sequence;
use polypay\asn\asn1\universal\BitString;
use polypay\asn\asn1\universal\ObjectIdentifier;

class PrivateKey extends Sequence
{
    /**
     * @param string $hexKey
     * @param \asn\asn1\ASNObject|string $algorithmIdentifierString
     */
    public function __construct($hexKey, $algorithmIdentifierString = OID::RSA_ENCRYPTION)
    {
        parent::__construct(
            new Sequence(
                new ObjectIdentifier($algorithmIdentifierString),
                new NullObject()
            ),
            new BitString($hexKey)
        );
    }
}
