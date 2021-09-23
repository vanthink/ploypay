<?php
declare(strict_types=1);
namespace polypay\ecc\random;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\crypto\key\PrivateKeyInterface;

use polypay\ecc\math\MathAdapterFactory;

class RandomGeneratorFactory
{
    public static function getRandomGenerator(bool $debug = false): RandomNumberGeneratorInterface
    {
        return self::wrapAdapter(
            new RandomNumberGenerator(
                MathAdapterFactory::getAdapter($debug)
            ),
            'random_bytes',
            $debug
        );
    }

    public static function getHmacRandomGenerator(PrivateKeyInterface $privateKey, \GMP $messageHash, string $algorithm, bool $debug = false): RandomNumberGeneratorInterface
    {
        return self::wrapAdapter(
            new HmacRandomNumberGenerator(
                MathAdapterFactory::getAdapter($debug),
                $privateKey,
                $messageHash,
                $algorithm
            ),
            'rfc6979',
            $debug
        );
    }

    private static function wrapAdapter(RandomNumberGeneratorInterface $generator, string $name, bool $debug = false): RandomNumberGeneratorInterface
    {
        if ($debug === true) {
            return new DebugDecorator($generator, $name);
        }

        return $generator;
    }
}
