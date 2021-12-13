<?php
declare(strict_types=1);

namespace polypay\ecc\serializer\signature\Der;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\asn\asn1\universal\Integer;
use polypay\asn\asn1\universal\Sequence;
use polypay\ecc\crypto\signature\SignatureInterface;

class Formatter
{

    public function toAsn(SignatureInterface $signature): Sequence
    {
        return new Sequence(
            new Integer(gmp_strval($signature->getR(), 10)),
            new Integer(gmp_strval($signature->getS(), 10))
        );
    }

    public function serialize(SignatureInterface $signature): string
    {
        return $this->toAsn($signature)->getBinary();
    }
}
