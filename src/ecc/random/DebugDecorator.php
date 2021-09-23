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

class DebugDecorator implements RandomNumberGeneratorInterface
{

    private $generator;

    private $generatorName;

    public function __construct(RandomNumberGeneratorInterface $generator, string $name)
    {
        $this->generator = $generator;
        $this->generatorName = $name;
    }

   
    public function generate(\GMP $max): \GMP
    {
        echo $this->generatorName.'::rand() = ';

        $result = $this->generator->generate($max);

        echo gmp_strval($result, 10).PHP_EOL;

        return $result;
    }
}
