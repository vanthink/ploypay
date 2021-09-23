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

use Exception;
use polypay\asn\asn1\exception\ParserException;
use polypay\asn\asn1\universal\Sequence;

class TemplateParser
{
    /**
     * @param string $data
     * @param array $template
     * @return \asn\asn1\ASNObject|Sequence
     * @throws ParserException if there was an issue parsing
     */
    public function parseBase64($data, array $template)
    {
        // TODO test with invalid data
        return $this->parseBinary(base64_decode($data), $template);
    }

    /**
     * @param string $binary
     * @param array $template
     * @return \asn\asn1\ASNObject|Sequence
     * @throws ParserException if there was an issue parsing
     */
    public function parseBinary($binary, array $template)
    {
        $parsedObject = ASNObject::fromBinary($binary);

        foreach ($template as $key => $value) {
            $this->validate($parsedObject, $key, $value);
        }

        return $parsedObject;
    }

    private function validate(ASNObject $object, $key, $value)
    {
        if (is_array($value)) {
            $this->assertTypeId($key, $object);

            /* @var Construct $object */
            foreach ($value as $key => $child) {
                $this->validate($object->current(), $key, $child);
                $object->next();
            }
        } else {
            $this->assertTypeId($value, $object);
        }
    }

    private function assertTypeId($expectedTypeId, ASNObject $object)
    {
        $actualType = $object->getType();
        if ($expectedTypeId != $actualType) {
            throw new Exception("Expected type ($expectedTypeId) does not match actual type ($actualType");
        }
    }
}
