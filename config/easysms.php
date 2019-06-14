<?php
return [
    // HTTP 请求的超时时间
    'timeout' => 5,

    // 默认的发送配置
    'default' => [
        // 网关调用策略, 默认顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'tengxun',
        ],
    ],

    // 可用的网关设置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'tengxun' => [
            'api_key' => env('TENGXUN_APP_KEY'),
       ],
    ],
];
