<?php
/*
 * Created on 2013-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class ProductSetCategorySelecter extends CWidget {
	public $categoryId;
	public $companyId;
	public function init(){

	}
	public function run(){
		$selecter = '';
                
		$rootCategoties = Helper::getSetCategories($this->companyId);
                //var_dump($this->categoryId,$rootCategoties);exit;
		if($this->categoryId!=0 && $category = ProductCategory::model()->find('t.lid = :cid and t.dpid=:dpid',array(':cid'=>$this->categoryId,':dpid'=>$this->companyId))){
			//var_dump($category->tree);exit;
                        $categoryTree = explode(',',$category['tree']);
                        //var_dump($categoryTree);exit;
                        echo $this->getSelecter($categoryTree);
		}else{
                   // var_dump($rootCategoties);exit;
			$selecter = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">';
			$selecter .=yii::t('app', '<option value="">--请选择--</option>');
			foreach($rootCategoties as $c1){
				$selecter .= '<option value="'.$c1['lid'].'">'.$c1['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		echo $selecter;
	}
	
	public function getSelecter($categoryTree){
		$selecter = '';
		for($i=0, $count = count($categoryTree); $i<$count-1; $i++){
			$categoties = Helper::getSetCategories($this->companyId,$categoryTree[$i]);
			$selecter .= '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">';
			$selecter .= yii::t('app','<option value="">--请选择--</option>');
			foreach($categoties as $c){
				$selecter .= '<option value="'.$c['lid'].'" '.(in_array($c['lid'],$categoryTree)?'selected':'').'>'.$c['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		return $selecter;
	}
} 
?>
