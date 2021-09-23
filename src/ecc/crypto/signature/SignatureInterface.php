<?php
declare(strict_types=1);

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


/**
 * This is the contract for describing a signature used in ECDSA.
 */
interface SignatureInterface
{
    /**
     * Returns the r parameter of the signature.
     *
     * @return \GMP
     */
    public function getR(): \GMP;

    /**
     * Returns the s parameter of the signature.
     *
     * @return \GMP
     */
    public function getS(): \GMP;
}
