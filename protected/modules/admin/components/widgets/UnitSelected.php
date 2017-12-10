<?php
/*
 * Created on 2013-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class UnitSelected extends CWidget {
	
	public $companyId;
	public $goodunitcode;
	public function init(){

	}
	public function run(){
		$selecter = '';
		$materialso = '';
		$mates = '';
		//if($this->goodunitcode!=0){
			//$sql = 'select mus.unit_name,mus.unit_specifications,mul.unit_name,mul.unit_specifications,t.* from nb_material_unit_ratio t left join nb_material_unit mul on(mul.muhs_code = t.mulhs_code) left join nb_material_unit mus on(mus.muhs_code = t.mushs_code) where t.delete_flag=0 and t.unit_code ='.$this->goodunitcode;
			//$units = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($mates);exit;
		//}else{
			$sql = 'select mus.unit_name,mus.unit_specifications,mul.unit_name,mul.unit_specifications,t.* from nb_material_unit_ratio t left join nb_material_unit mul on(mul.muhs_code = t.mulhs_code) left join nb_material_unit mus on(mus.muhs_code = t.mushs_code) where t.delete_flag=0 and t.dpid ='.$this->companyId.' group by t.lid';
			$unitso = Yii::app()->db->createCommand($sql)->queryAll();
		//}
		$selecter = '<select class="form-control materials" tabindex="-1" name="material_id_selecter" >';
		$selecter .=yii::t('app', '<option value="">--请选择--</option>');
		
		if($this->goodunitcode){
			foreach($unitso as $c1){
				//var_dump($c1);exit;
				if($c1['unit_code']==$this->goodunitcode){
					$s = 'selected';
				}else{
					$s = '';
				}
				$selecter .= '<option value="'.$c1['unit_code'].'" '.$s.'>'.$c1['unit_name'].'</option>';
			}
		}else{
			foreach($unitso as $c1){
				//var_dump($c1);exit;
				$selecter .= '<option value="'.$c1['unit_code'].'">'.$c1['unit_name'].'</option>';
			}
		}
		$selecter .= '</select>';
		echo $selecter;
	}

}
?>
