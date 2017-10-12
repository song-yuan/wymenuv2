<?php
class ProductController extends BaseYmallController
{

	/**
	 *
	 * 获取分类商品
	 */
	public function actionIndex()
	{
		$pid = Yii::app()->request->getParam('pid',0);
		$categoryId = Yii::app()->request->getParam('category',0);

		$db = Yii::app()->db;

		//查询所有分类的产品,根据店铺的dpid查找该店铺分组的仓库,查询仓库的名字产品
		$sql = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code from nb_goods g '
				.' left join nb_company c on(c.dpid=g.dpid) '
				.' left join nb_material_category mc on (mc.lid=g.category_id )'
				.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
				.' where g.dpid in(select ad.depot_id from nb_area_group_depot ad where ad.delete_flag=0 and ad.area_group_id in (select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0))'
				.' order by g.category_id';
		$products = $db->createCommand($sql)->queryAll();

		$materials =array();
		foreach ($products as $key => $product) {
			if(!isset($materials[$product['lid']])){
				$materials[$product['lid']] = array();
			}
			array_push($materials[$product['lid']], $product);
		}

		$criteria = new CDbCriteria;
		$criteria->with = array('category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		$stocks = ProductMaterial::model()->findAll($criteria);
		$this->render('product',array(
			'stocks'=>$stocks,
			'materials'=>$materials,
			'companyId'=>$this->companyId,
		));
	}






}