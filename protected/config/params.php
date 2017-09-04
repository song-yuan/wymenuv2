<?php
return array(
		'adminEmail'=>'webmaster@example.com',
		
		'admin_home_url' => 'default',
		'admin_return_url' => array('/admin/login/index'),
		'waiter_return_url' => 'index.php?r=waiter/user/index',
		'waiter_home_url' => 'index.php?r=waiter/seat/index',
		'ymall_home_url' => array('/ymall/product/index'),
		'ymall_return_url' => array('/ymall/login'),
		'frontend_home_url' => '',
		
		'salt' => 'use this string to gen password',
		'image_width' => 300,
		'image_height' => 300,
                'has_cache' => FALSE,
                ////////////////master_slave///////////////
                //m是主从系统，可以增加企业用户等,由物易管理
                //s是从用户，为客户准备的，不能增加企业
                'master_slave'=>'m',
               //////////////////////////////////////////
               
               /////////////cloud_local/////////////
               //c是云端系统，表示本系统运行在云端，不需要和下位系统同步，所有的同步是下位系统发出的。
               //l是本地系统，表示本系统运行在本地，需要同步
                'cloud_local'=>'c',
                //c is （cloud） can add company 
                //l only local） can import company data from master
                ////////////////////////////////////////
    
                //获取图片的域名
                //'masterdomain'=>'http://120.27.29.4/wymenuv2/',
				'masterdomain'=>'http://121.42.12.97/wymenuv2/',
                //云端数据库连接
                'dbcloud'=>array(
                        //'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
                        // uncomment the following lines to use a MySQL database
                        //'connectionString' => 'mysql:host=52.68.233.6;dbname=nb_wymenu',
                        //'connectionString' => 'mysql:host=120.27.29.4;dbname=nb_wymenu_customer',
                        'connectionString' => 'mysql:host=121.42.12.97;dbname=nb_wymenu',
                        //'connectionString' => 'mysql:host=192.168.1.37;dbname=nb_wymenu',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => 'MYmenu123',
                        'charset' => 'utf8',
                        'class' => 'CDbConnection' // DO NOT FORGET THIS!
                ),
    
                //本地数据库连接
                'dblocal'=>array(
                        //'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
                        // uncomment the following lines to use a MySQL database
                        //'connectionString' => 'mysql:host=52.68.233.6;dbname=nb_wymenu',
                        //'connectionString' => 'mysql:host=121.42.12.97;dbname=nb_wymenu',
                       // 'connectionString' => 'mysql:host=120.27.29.4;dbname=nb_wymenu',
                       // 'connectionString' => 'mysql:host=192.168.1.37;dbname=nb_wymenu',
                        'connectionString' => 'mysql:host=192.168.63.8;dbname=nb_wymenu',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        //'password' => 'MYmenu123',
                        'password' => 'wuyunjie887',
                        'charset' => 'utf8',
                        'class' => 'CDbConnection' // DO NOT FORGET THIS!
                ),
                
                //如果cloud_local是l,本机器对应的local机器编号是多少，
                //因为一个云端可以对用多个本地机器local,
                //所以sequcen肯定也要变化了，暂时不考虑多个时sequence的变化
                //，将来有再说。
                'sync_localnum'=>1,
                //最多有结果local,默认是5
                'sync_maxlocal'=>5,
                
                //超级本地机器，除了操作本机器产生的各种数据外，还能操作其他机器产生的数据
                //但是，当网络出现问题时，不能及时同步时，只能在各自的机器上开发
                'is_super_local'=>true,
                'super_location_function'=>array(
                    'account'=>true,
                ),
                //memcache的定义
//                 'memcache'=>array(
//                    'class'=>'CMemCache',
// 					'servers'=>array(
// 							array(
// 									'host'=>'121.42.12.97',
// 									'port'=>11211,
// 									'weight'=>60,
// 							),
// 					),
//                 ),
				'memcache'=>array(
						//'server'=>'120.27.29.4',
						'server'=>'121.42.12.97',
						'port'=>11211,
				),
);
