<?php

namespace polypay\ecc\crypto\signature;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use polypay\ecc\Primitives\GeneratorPoint;

interface HasherInterface
{
    /**
     * @return string
     */
    public function getAlgorithm(): string;

    /**
     * @return int
     */
    public function getLengthInBytes(): int;

    /**
     * @param string $data
     * @return string
     */
    public function makeRawHash(string $data): string;

    /**
     * @param string $data
     * @param GeneratorPoint $G
     * @return \GMP
     */
    public function makeHash(string $data, GeneratorPoint $G): \GMP;
}
