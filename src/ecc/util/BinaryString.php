<?php
declare(strict_types=1);

namespace polypay\ecc\Util;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

class BinaryString
{
   
    public static function length(string $str): int
    {
        static $exists = null;
        if ($exists === null) {
            $exists = function_exists('mb_strlen');
        }

        if ($exists) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }

   
    public static function substring(string $str, int $start = 0, int $length = null): string
    {
        static $exists = null;
        if ($exists === null) {
            $exists = function_exists('mb_substr');
        }

        if ($exists) {
            return mb_substr($str, $start, $length, '8bit');
        } elseif ($length !== null) {
            return substr($str, $start, $length);
        }
        return substr($str, $start);
    }
    
    public static function constantTimeCompare(string $knownString, string $userString): bool
    {
        return hash_equals($knownString, $userString);
    }
}
