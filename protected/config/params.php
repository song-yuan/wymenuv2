<?php
return array(
		'adminEmail'=>'webmaster@example.com',
		
		'admin_home_url' => 'default',
		'admin_return_url' => 'login/index',
		'waiter_return_url' => 'index.php?r=waiter/user/index',
		'waiter_home_url' => 'index.php?r=waiter/seat/index',
		'frontend_home_url' => '',
		
		'salt' => 'use this string to gen password',
		'image_width' => 300,
		'image_height' => 300,
                'has_cache' => FALSE,
                'master_slave'=>'m',//主从系统，主是我们增加和管理企业，从不能增加
                'cloud_local'=>'c',
                //c is （cloud） can add company 
                //l only local） can import company data from master
                'masterdomain'=>'http://menu.wymenu.com/wymenuv2/',//获取图片的域名
                'dbcloud'=>array(
                        //'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
                        // uncomment the following lines to use a MySQL database
                        //'connectionString' => 'mysql:host=52.68.233.6;dbname=nb_wymenu',
                        //'connectionString' => 'mysql:host=120.27.29.4;dbname=nb_wymenu',
                        'connectionString' => 'mysql:host=121.42.12.97;dbname=nb_wymenu',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => 'MYmenu123',
                        'charset' => 'utf8',
                        'class' => 'CDbConnection' // DO NOT FORGET THIS!
                ),
                'dblocal'=>array(
                        //'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
                        // uncomment the following lines to use a MySQL database
                        //'connectionString' => 'mysql:host=52.68.233.6;dbname=nb_wymenu',
                        //'connectionString' => 'mysql:host=121.42.12.97;dbname=nb_wymenu',
                        'connectionString' => 'mysql:host=127.0.0.1;dbname=nb_wymenu',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => 'MYmenu123',
                        'charset' => 'utf8',
                        'class' => 'CDbConnection' // DO NOT FORGET THIS!
                ),
);