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

namespace polypay\asn\asn1\exception;

class ParserException extends \Exception
{
    private $errorMessage;
    private $offset;

    public function __construct($errorMessage, $offset)
    {
        $this->errorMessage = $errorMessage;
        $this->offset = $offset;
        parent::__construct("ASN Parser Exception at offset {$this->offset}: {$this->errorMessage}");
    }

    public function getOffset()
    {
        return $this->offset;
    }
}
