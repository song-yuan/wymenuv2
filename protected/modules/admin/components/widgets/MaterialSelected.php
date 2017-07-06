<?php
/*
 * Created on 2013-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class MaterialSelected extends CWidget {
	public $categoryId;
	public $companyId;
	public $goodmatecode;
	public function init(){

	}
	public function run(){
		$selecter = '';
		$materialso = '';
		$mates = '';
		if($this->goodmatecode!=0){
			$mates = ProductMaterial::model()->findall('t.mphs_code = :code and t.dpid=:dpid and delete_flag = 0',array(':code'=>$this->goodmatecode,':dpid'=>$this->companyId));
		//var_dump($mates);exit;
		}else{	
			if($this->categoryId!=0){
				$materialso = ProductMaterial::model()->findall('t.category_id = :cid and t.dpid=:dpid',array(':cid'=>$this->categoryId,':dpid'=>$this->companyId));
	            
			}else{
				$materialso = ProductMaterial::model()->findall('t.dpid=:dpid',array(':dpid'=>$this->companyId));
				
			}
		}
		$selecter = '<select class="form-control materials" tabindex="-1" name="material_id_selecter" >';
		$selecter .=yii::t('app', '<option value="">--请选择--</option>');
		
		if(!empty($mates)){
			foreach($mates as $c1){
				//var_dump($c1);exit;
				$selecter .= '<option value="'.$c1['lid'].'" selected>'.$c1['material_name'].'</option>';
			}
		}else{
			foreach($materialso as $c1){
				//var_dump($c1);exit;
				$selecter .= '<option value="'.$c1['lid'].'">'.$c1['material_name'].'</option>';
			}
		}
		$selecter .= '</select>';
		echo $selecter;
	}

}
?>
