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

		$sqls = 'select `t`.lid,`t`.material_name,ifnull(stock.stock,0) as stock,u.unit_name,mc.category_name,t.category_id FROM `nb_product_material` `t`'
		.' left join nb_material_unit u  ON (t.sales_unit_id=u.lid and t.dpid) '
		.' left join nb_material_category mc on (mc.lid=t.category_id )'
		.' left JOIN ( select material_id,dpid,SUM(stock) AS stock from `nb_product_material_stock` where stock!=0 and dpid ='.$this->companyId.' and delete_flag=0 GROUP BY material_id) `stock` ON (t.lid=stock.material_id and stock.dpid=t.dpid )'
		.' WHERE t.delete_flag=0 and t.dpid='.$this->companyId;
		$stocks = $db->createCommand($sqls)->queryAll();

		$sqld='select k.* FROM ( select a.* from nb_product_material_safe a where a.dpid = '.$this->companyId.'   order by a.create_at desc) k group by k.material_id';
		$stock_s = $db->createCommand($sqld)->queryAll();
		$stocks_arr=array();
		foreach ($stock_s as $key => $value) {
			$stocks_arr['lid'.$value['material_id']]=$value;
		}

		$company = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		$company_name = $company->company_name;
		$x = mb_strlen($company_name);
		$y=0;
		if (($x/3)>8) {
			$y = ($x/3)-8;
		}
		$company_name = mb_substr($company_name,$y,$x,'utf-8');
		//轮播广告
		$sql1 = 'select * from nb_material_ad where dpid ='.$company->comp_dpid.' and delete_flag=0 and is_show=1 order by sort asc';
		$ads = $db->createCommand($sql1)->queryAll();
		// p($ads);

		//滚动公告
		$marquee='';
		$this->render('product',array(
			'marquee'=>$marquee,
			'ads'=>$ads,
			'stocks'=>$stocks,
			'stocks_arr'=>$stocks_arr,
			'company_name'=>$company_name,
		));
	}






}