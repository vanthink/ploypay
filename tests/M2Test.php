<?php
namespace polypay\test;
use polypay\encrypt\Sm2;

class M2Test
{

//返回的签名16进制还是base64, 目前可选hex,与base64两种
$sm2 = new Sm2('base64');

 
$private_key='D5F2AFA24E6BA9071B54A8C9AD735F9A1DE9C4657FA386C09B592694BC118B38';
$cmb_key='MFkwEwYHKoZIzj0CAQYIKoEcz1UBgi0DQgAE6Q+fktsnY9OFP+LpSR5Udbxf5zHCFO0PmOKlFNTxDIGl8jsPbbB/9ET23NV+acSz4FEkzD74sW2iiNVHRLiKHg==';

$appid = '8ab74856-8772-45c9-96db-54cb30ab9f74';
$secret = '5b96f20a-011f-4254-8be8-9a5ceb2f317f';

$mer_id='3089991074200M1'; 
$user_id='N003401513'; 
$notify_url=  'http://www.baidu.com';   

$timestamp = time();

  $url='https://api.cmburl.cn:8065/polypay/v1.0/mchorders/onlinepay';
 
$userId = '1234567812345678';


$data = [
'biz_content' => '',
'encoding'   => 'UTF-8',//编码方式，固定为UTF-8(必传)
//'sign'=>'',
'signMethod' => '02', //签名方法，固定为01，表示签名方式为RSA2(必传)
'version'    => '0.0.1',//版本号，固定为0.0.1(必传字段)
];


        
        

// 业务字段
$bizContent = [
'merId' => $mer_id,
"subAppId"=>"wxcb09c54c91986108",//子公众帐号ID（必传）
'orderId'    => date('YmdHis').random_int(1000, 9999),
"tradeType"=> "JSAPI",//交易类型 APP/MWEB/JSAPI（必传）
"tradeScene"=> "OFFLINE",//交易场景
'userId' => $user_id,//收银员(必传)
"body"=> "千城一面-生活服务",
'notifyUrl' => 'https://api.cmburl.cn:8065/polypay/v1.0/mchorders/onlinepay',
'payValidTime'=>'1000',
'currencyCode' => '156',//交易币种，默认156，目前只支持人民币（156）
"spbillCreateIp"=>"27.211.249.160",
'txnAmt' => '1',//交易金额，单位为分（必传）
'mchReserved' => '1',
'subOpenId'=>'osd7d5Z9S_t5OCaoQoGbhEaxKjmY',
];

       

$data['biz_content']=json_encode($bizContent,JSON_UNESCAPED_SLASHES);
echo "加密前：".PHP_EOL;
var_export($data);
echo PHP_EOL;
$params=  urldecode(http_build_query($data));


  $sign = $sm2->doSign($params, $private_key, $userId);
$data['sign']=$sign;

echo PHP_EOL;

//商户在接入聚合收单平台前，行内其他系统会为商户分配一对APP ID和APP SECRET，其中商户和APP ID是一一对应的，APP ID作为商户在调用聚合收单API时的身份标识。商户在上送请求报文时需将此APP ID放在报文头中上送过来，且需对
//APP ID、APP SECRET、报文体中的sign值、Linux格式时间戳按KEY值的首字母排序并用&连接，然后用MD5算法进行加签，加签结果为小写字母，并将时间戳和加签结果和APP ID一起放在请求报文的报文头中（APP SECRET无需上送）。具体可参考如下示例：
$apisign = md5('appid='.$appid.'&secret='.$secret.'&sign='.$sign.'&timestamp='.$timestamp);
$headers =  [
    'appid'       => $appid,
    'timestamp'   => $timestamp,
    'apisign'     => $apisign
];






$ret=post_json($url,$headers,$data);
var_export($ret);
}