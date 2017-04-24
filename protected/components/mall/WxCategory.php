<?php 
/**
 * 
 * 
 * 微信端产品类
 * 
 */
class WxCategory
{
	public static function get($dpid){
		$sql = 'select * from nb_product_category where dpid=:dpid and pid=0 and cate_type=0 and delete_flag=0 order by order_num asc,lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->queryAll();
		return $categorys;
	}
	public static function getChrildren($dpid,$categoryId){
		$sql = 'select * from nb_product_category where dpid=:dpid and pid=:categoryId and delete_flag=0 order by order_num asc,lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':categoryId',$categoryId)->queryAll();
		return $categorys;
	}
	public static function getChrildrenIds($dpid,$categoryId){
		$sql = 'select lid from nb_product_category where dpid=:dpid and pid=:categoryId and delete_flag=0 order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':categoryId',$categoryId)->queryColumn();
		return $categorys;
	}
	public static function getHideCate($dpid,$showType){
		$hideCategory = array();
		$sql = 'select * from nb_product_category where dpid=:dpid and pid=0 and show_type=:showType and delete_flag=0 order by order_num asc,lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':showType',$showType)->queryAll();
		foreach ($categorys as $category){
			$childe = self::getChrildrenIds($dpid,$category['lid']);
			$hideCategory = array_merge($hideCategory,$childe);
		}
		return $hideCategory;
	}
}