<?php

class TestController extends Controller
{
	public $layout = '/layouts/productmain';
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionTest(){
		$this->render('test');
	}
	public function actionHh(){
		$this->render('hh');
	}
	public function actionReadLog()
	{
		echo '<meta charset="utf-8">';
		$log = file_get_contents( Yii::app()->basePath."/data/log.txt");
		echo $log;
		exit;
	}
}