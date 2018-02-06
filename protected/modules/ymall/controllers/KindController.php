<?php
class KindController extends BaseYmallController
{
	public function actionKind()
	{

		$db = Yii::app()->db;
		$sql =' select peisong_id,material_price_group_id from nb_company_property where dpid='.$this->companyId.' and delete_flag=0';
		$info = $db->createCommand($sql)->queryRow();
		// p($info);
		if(0 != $info['peisong_id']){
			if (0 != $info['material_price_group_id']) {
				//指定产品价格
				$sql1 = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,mpgs.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name '
			.' from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm '
			.' inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid='.$info['peisong_id'].' and psg.delete_flag=0) psgs on (ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)'

			.' inner join ( select mpgd.* from nb_material_price_group_detail mpgd where mpgd.price_group_id='.$info['material_price_group_id'].' and mpgd.delete_flag=0) mpgs on (ggm.lid=mpgs.goods_id )'

			.' inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)'
			.' inner join nb_company c on(c.dpid=ggm.dpid) '
			.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) '
			.' where  ggm.delete_flag=0 order by ggm.category_id;';
			}else{
				//默认总部价格
				$sql1 = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,ggm.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name '
			.' from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm '
			.' inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid='.$info['peisong_id'].' and psg.delete_flag=0) psgs on (ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)'
			.' inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)'
			.' inner join nb_company c on(c.dpid=ggm.dpid) '
			.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) '
			.' where  ggm.delete_flag=0 order by ggm.category_id;';
			}
		}else{
				$sql1='select null';
		}
		$products = $db->createCommand($sql1)->queryAll();
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

		$products = '';
		if (!empty($content)) {
		$sql =' select peisong_id,material_price_group_id from nb_company_property where dpid='.$this->companyId.' and delete_flag=0';
		$info = $db->createCommand($sql)->queryRow();
		// p($info);
		if(0 != $info['peisong_id']){
			if (0 != $info['material_price_group_id']) {
				//指定产品价格
				$sql1 = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,mpgs.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name '
			.' from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm '
			.' inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid='.$info['peisong_id'].' and psg.delete_flag=0) psgs on (ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)'

			.' inner join ( select mpgd.* from nb_material_price_group_detail mpgd where mpgd.price_group_id='.$info['material_price_group_id'].' and mpgd.delete_flag=0) mpgs on (ggm.lid=mpgs.goods_id )'

			.' inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)'
			.' inner join nb_company c on(c.dpid=ggm.dpid) '
			.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) '
			.' where  ggm.delete_flag=0 '
			.' and ggm.goods_name like "%'.$content.'%" order by ggm.category_id';
			$products = $db->createCommand($sql1)->queryAll();

			}else{
				//默认总部价格
				$sql1 = 'select ggm.material_code,ggm.goods_code,ggm.goods_name,ggm.lid as glid,ggm.main_picture,ggm.price,ggm.goods_unit,ggm.unit_code,c.company_name,c.dpid,mc.lid,mc.category_name,mu.unit_name '
			.' from (select g.*,gm.material_code,gm.unit_code from nb_goods g left join nb_goods_material gm on (g.lid=gm.goods_id )) ggm '
			.' inner join ( select psgd.* from nb_peisong_group_detail psgd left join nb_peisong_group psg on(psgd.peisong_group_id=psg.lid) where psg.lid='.$info['peisong_id'].' and psg.delete_flag=0) psgs on (ggm.dpid=psgs.stock_dpid and ggm.material_code=psgs.mphs_code)'
			.' inner join nb_material_category mc on ( mc.lid=ggm.category_id and mc.delete_flag=0)'
			.' inner join nb_company c on(c.dpid=ggm.dpid) '
			.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=ggm.unit_code) '
			.' where  ggm.delete_flag=0 '
			.' and ggm.goods_name like "%'.$content.'%" order by ggm.category_id';
			$products = $db->createCommand($sql1)->queryAll();
			}
		}
				
			// p($products);
		}
		$this->render('search',array(
			'content'=>$content,
			'products'=>$products,
			'companyId'=>$this->companyId,
		));
	}
}