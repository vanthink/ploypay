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


namespace polypay\asn\asn1;

use polypay\asn\asn1\exception\ParserException;

/**
 * The Parsable interface describes classes that can be parsed from their binary DER representation.
 */
interface Parsable
{
    /**
     * Parse an instance of this class from its binary DER encoded representation.
     *
     * @param string $binaryData
     * @param int $offsetIndex the offset at which parsing of the $binaryData is started. This parameter ill be modified
     *            to contain the offset index of the next object after this object has been parsed
     *
     * @throws ParserException if the given binary data is either invalid or not currently supported
     *
     * @return static
     */
    public static function fromBinary(&$binaryData, &$offsetIndex = null);
}
