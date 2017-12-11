<?php
class KindController extends BaseYmallController
{
	public function actionKind()
	{

		$db = Yii::app()->db;
		// $sql = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code from nb_goods g '
		// 		.' left join nb_company c on(c.dpid=g.dpid) '
		// 		.' left join nb_material_category mc on (mc.lid=g.category_id )'
		// 		.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
		// 		.' where g.dpid in(select ad.depot_id from nb_area_group_depot ad where ad.delete_flag=0 and ad.area_group_id in (select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0)) and g.delete_flag=0'
		// 		.' order by g.category_id';

		$sql = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,ggm.original_price,ggm.member_price,ggm.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name 
		from ( select psgd.* from nb_peisong_group_detail psgd inner join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid=(select peisong_id from nb_company_property where dpid='.$this->companyId.')) psgs
		inner join (select g.*,gm.material_code from nb_goods g inner join nb_goods_material gm on (g.lid=gm.goods_id )) ggm on(ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)
		inner join nb_material_category mc on (mc.lid=ggm.category_id and mc.delete_flag=0)
		inner join nb_company c on(c.dpid=ggm.dpid) 
		where  ggm.delete_flag=0
		order by ggm.category_id;';
		$products = $db->createCommand($sql)->queryAll();

		
		$materials =array();
		foreach ($products as $key => $product) {
			if(!isset($materials[$product['lid']])){
				$materials[$product['lid']] = array();
			}
			array_push($materials[$product['lid']], $product);
		}
		// p($materials);
		// $cates = $db->createCommand('select * from nb_material_category where pid=0 and delete_flag = 0 and dpid ='.$this->companyId)->queryAll();
		// p($cates);



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
			$sql = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code from nb_goods g '
				.' left join nb_company c on(c.dpid=g.dpid) '
				.' left join nb_material_category mc on (mc.lid=g.category_id )'
				.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
				.' where g.dpid in(select ad.depot_id from nb_area_group_depot ad where ad.delete_flag=0 and ad.area_group_id in (select area_group_id from nb_area_group_company where company_id='.$this->companyId.' and delete_flag=0))'
				.' and g.goods_name like "%'.$content.'%"';
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