<?php
return [
    // HTTP 请求的超时时间
    'timeout' => 10,

    // 默认的发送配置
    'default' => [
        // 网关调用策略, 默认顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun',
        ],
    ],

    // 可用的网关设置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'qcloud' => [
            'api_key' => env('TENGXUN_APP_KEY'),
            'sdk_app_id' => env('TENGXUN_APP_ID'),
        ],
        'aliyun' => [
            'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => env('Larabbs'),
            'templates' => [
                'register' => env('SMS_ALIYUN_TEMPLATE_REGISTER'),
            ],
        ],
    ],
];
