<?php
/*
 * Created on 2013-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class ProductCategorySelecter extends CWidget {
	public $categoryId;
	public $companyId;
	public function init(){

	}
	public function run(){
		$selecter = '';
		$rootCategoties = Helper::getCategories($this->companyId);
		if($category = ProductCategory::model()->findByPk($this->categoryId)){
			$categoryTree = explode(',',$category->tree);
			echo $this->getSelecter($categoryTree);
		}else{
			$selecter = '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">';
			$selecter .= '<option value="">--请选择--</option>';
			foreach($rootCategoties as $c1){
				$selecter .= '<option value="'.$c1['category_id'].'">'.$c1['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		echo $selecter;
	}
	
	public function getSelecter($categoryTree){
		$selecter = '';
		for($i=0, $count = count($categoryTree); $i<$count-1; $i++){
			$categoties = Helper::getCategories($this->companyId,$categoryTree[$i]);
			$selecter .= '<select class="form-control category_selecter" tabindex="-1" name="category_id_selecter">';
			$selecter .= '<option value="">--请选择--</option>';
			foreach($categoties as $c){
				$selecter .= '<option value="'.$c['category_id'].'" '.(in_array($c['category_id'],$categoryTree)?'selected':'').'>'.$c['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		return $selecter;
	}
} 
?>
