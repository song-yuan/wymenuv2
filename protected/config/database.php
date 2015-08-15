<?php

// This is the database connection configuration.
$params=require(dirname(__FILE__).'/params.php');

 //var_dump($params);exit;

if($params['cloud_local']=="c")
{
    return $params['dbcloud'];
}else{
    return $params['dblocal'];
}