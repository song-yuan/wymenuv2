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
		$sql = 'select * from nb_product_category where dpid=:dpid and pid=0 and cate_type=0 and delete_flag=0 order by order_num desc,lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->queryAll();
		return $categorys;
	}
	public static function getChrildren($dpid,$categoryId){
		$sql = 'select * from nb_product_category where dpid=:dpid and pid=:categoryId and delete_flag=0 order by order_num desc,lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':categoryId',$categoryId)->queryAll();
		return $categorys;
	}
	public static function getChrildrenIds($dpid,$categoryId){
		$sql = 'select lid from nb_product_category where dpid=:dpid and pid=:categoryId and delete_flag=0 order by lid desc';
		$categorys = Yii::app()->db->createCommand($sql)->bindValue(':dpid',$dpid)->bindValue(':categoryId',$categoryId)->queryColumn();
		return $categorys;
	}
}