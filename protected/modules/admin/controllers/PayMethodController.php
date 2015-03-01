<?php
class PayMethodController extends BackendController {
	
	public function actionIndex() {
		
		$this->render('index');
	}
	public function actionCreate() {
		$this->render('create');
	}
	public function actionUpdate() {
		$this->render('update');
	}
}
