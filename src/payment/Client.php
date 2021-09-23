<?php
namespace polypay\payment;
// +----------------------------------------------------------------------
// | Title: 
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年08月03日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

use think\facade\Config;
use polypay\encrypt\Sm2;

class Client
{
    protected $config;
    protected $url = 'https://api.cmbchina.com/polypay/v1.0/';

    public function __construct()
    {
        $this->config = $config=Config::get('polypay');
        if ($config["use_sandbox"]) {
            $this->url='https://api.cmburl.cn:8065/polypay/v1.0/';
        }
    }
    

    public function qrcodeapply(array $params)
    {
        return $this->handler('mchorders/qrcodeapply', $params);
    }


    public function orderQuery($orderId)
    {
        return $this->handler('mchorders/orderquery', [
            'orderId' => $orderId
        ]);
    }

  
    public function refund(array $params)
    {
        return $this->handler('mchorders/refund', $params);
    }

  
    public function refundQuery(array $params)
    {
        return $this->handler('mchorders/refundquery', $params);
    }


    public function close($params)
    {
        return $this->handler('mchorders/close', $params);
    }

   
    public function orderQrCode(array $params)
    {
        return $this->handler('mchorders/orderqrcodeapply', $params);
    }

    public function onlinePay(array $params)
    {
        return $this->handler('mchorders/onlinepay', $params);
    }

 
    public function aliServerPay(array $params)
    {
        return $this->handler('mchorders/servpay', $params);
    }

   
    public function aliQrCode(array $params)
    {
        return $this->handler('mchorders/zfbqrcode', $params);
    }

   
    public function miniAppOrder(array $params)
    {
        return $this->handler('mchorders/MiniAppOrderApply', $params);
    }

 
    public function wxQrCode(array $params)
    {
        return $this->handler('mchorders/wxqrcode', $params);
    }


    protected function handler($url, $params){
        $data = [
            'biz_content' => $this->bizContent($params),
            'encoding'   => 'UTF-8',//编码方式，固定为UTF-8(必传)
            'signMethod' => '02', //签名方法，固定为01，表示签名方式为RSA2(必传)
            'version'    => '0.0.1',//版本号，固定为0.0.1(必传字段)
        ];
        $sign = $this->sign($data);
        $data['sign']=$sign;

        $ret=post_json($this->url.$url,$this->headers($sign),$data);

        if (false !== stripos($ret, 'returnCode')) {
            return json_decode($ret, true);
        }else{
            return $ret;
        }
      
    }
    
    protected function bizContent($params){
        $bizContent = [
            'merId' => $this->config['mer_id'],
            'userId' => $this->config['user_id'],
            'notifyUrl' => app()->request->domain() .$this->config['notify_url'],
        ];
        $params = array_filter(array_merge($bizContent, $params), 'strlen'); 
        ksort($params);
        return  json_encode($params,JSON_UNESCAPED_SLASHES);
    }

    private function sign($params)
    {
        $sm2 = new Sm2('base64');
        $data=  urldecode(http_build_query($params));
        $private_key= $this->config['private_key'];
        $sign =$sm2->doSign($data, $private_key,'1234567812345678');
        return $sign;

    }

    private function headers($sign)
    {

        $appid = $this->config['appid'];
        $secret = $this->config['secret'];
        $timestamp = time();
        $apisign = md5('appid='.$appid.'&secret='.$secret.'&sign='.$sign.'&timestamp='.$timestamp);
        $data =  [
            'appid'       => $appid,
            'timestamp'   => $timestamp,
            'apisign'     => $apisign
        ];
        return  $data;
    }



}