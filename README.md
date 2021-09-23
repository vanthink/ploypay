# Polypay for ThinkPHP6

## 安装

> composer require laoqianjunzi/polypay

## 配置

> 配置文件位于 `config/pilypay.php`

### 公共配置

```

[
    'use_sandbox' => true,
    'appid'=>'8ab74856-8772-45c9-96db-54cb30ab9f74',//APP ID
    'secret'=>'5b96f20a-011f-4254-8be8-9a5ceb2f317f',//APP SECRET
    'private_key'=>'D5F2AFA24E6BA9071B54A8C9AD735F9A1DE9C4657FA386C09B592694BC118B38',// 商户私钥
    'cmb_key'=>'MFkwEwYHKoZIzj0CAQYIKoEcz1UBgi0DQgAE6Q+fktsnY9OFP+LpSR5Udbxf5zHCFO0PmOKlFNTxDIGl8jsPbbB/9ET23NV+acSz4FEkzD74sW2iiNVHRLiKHg==',// 招行公钥
    'mer_id'=>'3089991074200M1',// 商户 ID
    'user_id'=>'N003401513',// 收银员 ID
    'notify_url'=>'http://www.laoqianjunzi.con/notify',
]

```
### 两个例子

```
namespace app\order;

use polypay\payment\Client;
//
class orderPay{

    //微信统一下单
    public function pay1(){
        // 业务字段
        $bizContent = [
            "subAppId"=>"wxcb09c54c91986108",//子公众帐号ID（必传）
            'orderId'    => date('YmdHis').random_int(1000, 9999),
            "tradeType"=> "JSAPI",//交易类型 APP/MWEB/JSAPI（必传）
            "tradeScene"=> "OFFLINE",//交易场景
            "body"=> "xxx-生活服务",
            'payValidTime'=>'1000',
            'currencyCode' => '156',//交易币种，默认156，目前只支持人民币（156）
            "spbillCreateIp"=>"192.168.1.10",
            'txnAmt' => '1',//交易金额，单位为分（必传）
            'mchReserved' => '1',
            'subOpenId'=>'osd7d5Z9S_t5OCaoQoGbhEaxKjmY',
        ];
        $client=new Client();
        $ret=  $client->onlinePay($bizContent);
    }

    //收款码申请
    public function pay2(){
        // 业务字段
        $bizContent = [
            'orderId'    => date('YmdHis').random_int(1000, 9999),
            'payValidTime'=>'1000',
            'notifyUrl' => 'https://baidu.com',
            'txnAmt' => '1',//交易金额，单位为分（必传）
            "body"=> "XXXX-生活服务",
            "tradeScene"=> "OFFLINE",//交易场景
        ];
        $client=new Client();
        $ret=  $client->qrcodeapply($bizContent);
    }

}

```

