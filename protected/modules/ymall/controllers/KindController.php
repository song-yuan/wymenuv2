<?php
class KindController extends BaseYmallController
{
	public function actionKind()
	{

		$db = Yii::app()->db;

		$sql = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,ggm.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name 
		from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm 
		inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid=(select peisong_id from nb_company_property where dpid='.$this->companyId.' and delete_flag=0) and psg.delete_flag=0) psgs on (ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)
		inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)
		inner join nb_company c on(c.dpid=ggm.dpid) 
		left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) 
		where  ggm.delete_flag=0
		order by ggm.category_id;';
		$products = $db->createCommand($sql)->queryAll();
		// p($products);
		$materials =array();
		foreach ($products as $key => $product) {
			if(!isset($materials[$product['lid']])){
				$materials[$product['lid']] = array();
			}
			array_push($materials[$product['lid']], $product);
		}


		$this->render('kind',array(
			'materials'=>$materials,
			'companyId'=>$this->companyId,
		));
	}




	/**
	 * @Author    zhang
	 * @DateTime  2017-09-11T11:32:57+0800
	 * @return    [type]         搜索产品          [description]
	 */
	public function actionSearch(){
		$content = Yii::app()->request->getParam('content');
		// p($content);
		$db=Yii::app()->db;
		$area_group_id = $db->createCommand('select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0')->queryAll();
		
		// p($area_group_id);
		$products = '';
		if (!empty($content)) {
			$sql = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,ggm.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name 
		from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm 
		inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid=(select peisong_id from nb_company_property where dpid='.$this->companyId.' and delete_flag=0) and psg.delete_flag=0) psgs
 on(ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)
		inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)
		inner join nb_company c on(c.dpid=ggm.dpid) 
		left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) 
		where  ggm.delete_flag=0'
				.' and ggm.goods_name like "%'.$content.'%"';
			$products = $db->createCommand($sql)->queryAll();
			// p($products);
		}
		$this->render('search',array(
			'content'=>$content,
			'products'=>$products,
			'companyId'=>$this->companyId,
		));
	}
}