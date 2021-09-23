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

use polypay\asn\asn1\Identifier;

class ObjectDescriptor extends GraphicString
{
    public function __construct($objectDescription)
    {
        parent::__construct($objectDescription);
    }

    public function getType()
    {
        return Identifier::OBJECT_DESCRIPTOR;
    }
}
