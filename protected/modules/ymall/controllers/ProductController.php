<?php
class ProductController extends BaseYmallController
{

	/**
	 *
	 * 获取分类商品
	 */
	public function actionIndex()
	{
		$db = Yii::app()->db;
		//实时库存
		$sqls = 'SELECT `t`.material_name,SUM(stock.stock) AS stock,safe.safe_stock,safe.max_stock,unit.unit_name FROM `nb_product_material` `t`
			LEFT JOIN `nb_product_material_stock` `stock` ON (t.lid=stock.material_id and stock.dpid=t.dpid AND stock.delete_flag=0)
			LEFT JOIN (select a.* from nb_product_material_safe a where a.dpid = '.$this->companyId.' AND  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_product_material_safe b where b.dpid=a.dpid and b.material_id=a.material_id) ) `safe` ON (t.lid=safe.material_id and safe.dpid=t.dpid AND safe.delete_flag=0)
			LEFT JOIN (SELECT u.unit_name,r.sales_unit_id FROM nb_material_unit u LEFT JOIN nb_material_unit_ratio r on(u.lid=r.sales_unit_id AND r.delete_flag=0 ) where u.delete_flag=0) `unit` ON (t.sales_unit_id=unit.sales_unit_id )
			WHERE (t.delete_flag=0 and t.dpid='.$this->companyId.') GROUP BY t.lid';
		$stocks = $db->createCommand($sqls)->queryAll();
		
		$company = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		$company_name = $company->company_name;
		$x = mb_strlen($company_name);
		$y=0;
		if (($x/3)>8) {
			$y = ($x/3)-8;
		}
		$company_name = mb_substr($company_name,$y,$x,'utf-8');
		//轮播广告
		$sql1 = 'select * from nb_material_ad where dpid ='.$company->comp_dpid.' and delete_flag=0 order by sort asc';
		$ads = $db->createCommand($sql1)->queryAll();
		// p($ads);


		$this->render('product',array(
			'ads'=>$ads,
			'stocks'=>$stocks,
			'company_name'=>$company_name,
		));
	}






}