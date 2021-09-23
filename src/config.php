<?php
// +----------------------------------------------------------------------
// | Title: 招商银行聚合支付配置文件
// +----------------------------------------------------------------------
// | Author: 劳谦君子 <laoqianjunzi@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月22日
// +----------------------------------------------------------------------
// | Description：
// +----------------------------------------------------------------------

return [
    'use_sandbox' => true,
    'appid'=>'8ab74856-8772-45c9-96db-54cb30ab9f74',//
    'secret'=>'5b96f20a-011f-4254-8be8-9a5ceb2f317f',//
    'private_key'=>'D5F2AFA24E6BA9071B54A8C9AD735F9A1DE9C4657FA386C09B592694BC118B38',// 商户私钥
    'cmb_key'=>'MFkwEwYHKoZIzj0CAQYIKoEcz1UBgi0DQgAE6Q+fktsnY9OFP+LpSR5Udbxf5zHCFO0PmOKlFNTxDIGl8jsPbbB/9ET23NV+acSz4FEkzD74sW2iiNVHRLiKHg==',// 招行公钥
    'mer_id'=>'3089991074200M1',// 商户 ID
    'user_id'=>'N003401513',// 收银员 ID
    'notify_url'=>'http://www.laoqianjunzi.con/notify',
];
