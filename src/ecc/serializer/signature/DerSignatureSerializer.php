<?php
declare(strict_types=1);

namespace polypay\ecc\serializer\signature;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\crypto\signature\SignatureInterface;

class DerSignatureSerializer implements DerSignatureSerializerInterface
{

    private $parser;

    private $formatter;

    public function __construct()
    {
        $this->parser = new Der\Parser();
        $this->formatter = new Der\Formatter();
    }

   
    public function serialize(SignatureInterface $signature): string
    {
        return $this->formatter->serialize($signature);
    }

    public function parse(string $binary): SignatureInterface
    {
        return $this->parser->parse($binary);
    }
}
