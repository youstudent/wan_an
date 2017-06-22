<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache',
        ],
        'db'          => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=localhost;dbname=wan_an',
            'username'    => 'wananmysql',
            'password'    => 'Q268fAjFvr',
            'charset'     => 'utf8',
            'tablePrefix' => 'wa_',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        "urlManager"  => [
            //用于表明 urlManager 是否启用URL美化功能
            "enablePrettyUrl" => true,
            // 是否在URL中显示入口脚本
            "showScriptName"  => false,
        ],
        //session 共
        'user'    => [
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity', 'httpOnly' => true, 'domain' => '.wantu3.cn', 'path' => '/',],
        ],
        'session' => [
            'cookieParams' => ['domain' => '.wantu3.cn', 'lifetime' => 0, 'path' => '/',],
            'timeout'      => 3600,
        ],
    ],

];
