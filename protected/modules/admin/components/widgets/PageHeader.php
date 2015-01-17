<?php
/*
 * Created on 2013-11-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class PageHeader extends CWidget{
	public $head;
	public $subhead;
	public $breadcrumbs;//array(array('words'=>'','url'=>''));
	public $back;//array('word'=>'word','url'=>'url')
	public function init(){
		
	}
	public function run(){
		$this->render('pageHeader',array('companyId'=>$this->getController()->companyId,'head'=>$this->head,'subhead'=>$this->subhead,'breadcrumbs'=>$this->breadcrumbs,'back'=>$this->back));
	}
} 
?>
