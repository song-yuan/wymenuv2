<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$params=require(dirname(__FILE__).'/params.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',
	//'language'=>'zh_cn',
        //'language'=>'jp',
	//'sourceLanguage'=>'en',
        'sourceLanguage'=>'zh_cn',
		
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.dataAppSync.*',
		'application.components.weixinpay.*',
		'application.components.weixin.*',
		'application.components.mall.*',
		'application.components.wxcard.*',
		'application.components.shouqianba.*',
		'application.components.meituanpay.*',
		'application.components.meituan.*',
		'application.components.eleme.*',
		'application.components.alipay.*',
		'application.components.alipay.f2fpay.*',
		'application.components.alipay.f2fpay.aop.*',
		'application.components.alipay.f2fpay.aop.request.*',
		'application.components.alipay.f2fpay.service.*',
		'application.components.alipay.f2fpay.model.builder.*',
		'application.components.alipay.f2fpay.model.result.*',
		'application.extensions.redis.*',
		'application.extensions.qrcode.*',
        'application.extensions.PHPExcel.*'

	),
	
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'admin' => array(
				
		),
		'ymall' => array(
		
		),
		'waiter',
                'thinterface',
                'wifi',
	),

        
    
	// application components
	'components'=>array(
		"redis" => array(
				"class" => "application.extensions.redis.ARedisConnection",
				"hostname" => "121.40.124.21",
				"port" => 6379,
				"database" => 0,
				"prefix" => "",
				'password'=>'MYmenu123',
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class' => 'application.components.WebUser',
		),
		'image'=>array(
				'class'=>'application.extensions.image.CImageComponent',
				// GD or ImageMagick
				'driver'=>'GD',
				// ImageMagick setup path
				'params'=>array('directory'=>''),
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
                'showScriptName'=>false,
// 				'appendParams' => false,//设置为false，使提交的search参数以?a=b&c=d的形式显示在url后面
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'ymall/<controller:\w+>/<action:\w+>'=>'ymall/<controller>/<action>',
			),
		),
		/*
                'urlManager'=>array(  
                    'showScriptName'=>false,    // 这一步是将代码里链接的index.php隐藏掉�? 
                    'urlFormat'=>'path',  
                    'rules'=>array(    
                        '<controller:\w+>/<id:\d+>'=>'<controller>/view',                
                        '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',  
                        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',                  

                    ),  
                ), 
                */
		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),
            
                'dbcloud'=>$params['dbcloud'],
                'dblocal'=>$params['dblocal'],
                    
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		//'cache'=>$params['memcache'],
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
// 				array(
// 					'class'=>'CWebLogRoute',
// 				),
			),
		),
            
                'coreMessages'=>array(  
                    'basePath'=>'protected/messages',  
                    ),

	),
        // application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
	
);
