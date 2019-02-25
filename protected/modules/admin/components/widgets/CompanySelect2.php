<?php
/*
 * Created on 2013-11-21
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CompanySelect2 extends CWidget{
	public $companyType;
	public $companyId;
	public $selectCompanyId;
	public $multiple = '';
	public function init(){
		
	}
	public function run(){
		if($this->companyType==0){
			//总部
			$sql = 'select dpid,company_name from nb_company where type=1 and comp_dpid='.$this->companyId.' and delete_flag=0';
			$companys = Yii::app()->db->createCommand($sql)->queryAll();
		}else{
			//门店
			$companys = array();
			$sql = 'select dpid,company_name from nb_company where dpid='.$this->companyId.' and delete_flag=0';
			$company = Yii::app()->db->createCommand($sql)->queryRow();
			if($company){
				array_push($companys, $company);
			}
		}
		$this->render('companySelect2',array('companys'=>$companys,'selectDpid'=>$this->selectCompanyId,'multiple'=>$this->multiple));
	}
} 
?>
