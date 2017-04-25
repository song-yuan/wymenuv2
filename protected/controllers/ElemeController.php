<?php

class ElemeController extends Controller
{
	public function actionElemeceshi(){
		$dpid = '0000000027';
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		var_dump($xml);
		$obj = json_decode($xml,true);
		var_dump($obj);
	}
}