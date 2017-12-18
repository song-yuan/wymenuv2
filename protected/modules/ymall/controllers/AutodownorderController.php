<?php

class AutodownorderController extends BaseYmallController
{
	/**
	 * @Author    zhang
	 * @DateTime  2017-11-01T14:45:22+0800
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]   自动生成采购订单/先删除购物车的商品/
	 *            [description]  查询店铺原料 剩余库存
	 *            				 循环比较库存与安全库存 (需要换算)
	 *            				 /比较库存与最大库存 (需要换算)
	 *            				 对低于安全库存的进行购物车添加(需要系数换算)
	 *
	 * 							 其他情况待定
	 */
	public function actionIndex()
	{
		//查询购物车信息->删除
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		$db = Yii::app()->db;
		$info = GoodsCarts::model()->deleteAll('dpid=:dpid and  user_id=:user_id',array(':dpid'=>$this->companyId,':user_id'=>$user_id));
		$companyId = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId))->comp_dpid;
		if (!$companyId) {
			$companyId=$this->companyId;
		}

		$sqls = 'select `t`.lid,`t`.material_name,`t`.mphs_code,ifnull(stock.stock,0) as stock,u.unit_name,mc.category_name,t.category_id FROM `nb_product_material` `t`'
		.' left join nb_material_unit u  ON (t.sales_unit_id=u.lid and t.dpid) '
		.' left join nb_material_category mc on (mc.lid=t.category_id )'
		.' left JOIN ( select material_id,dpid,SUM(stock) AS stock from `nb_product_material_stock` where stock!=0 and dpid ='.$this->companyId.' and delete_flag=0 GROUP BY material_id) `stock` ON (t.lid=stock.material_id and stock.dpid=t.dpid )'
		.' WHERE t.delete_flag=0 and t.dpid='.$this->companyId;
		$stocks_goods = $db->createCommand($sqls)->queryAll();

		$sqld='select k.max_stock,k.safe_stock,k.material_id FROM ( select a.* from nb_product_material_safe a where a.create_at>DATE_SUB(NOW(), INTERVAL 2 DAY) and a.dpid = '.$this->companyId
		.' order by a.create_at desc) k group by k.material_id';
		$stock_s = $db->createCommand($sqld)->queryAll();
		$stocks_arr=array();
		foreach ($stock_s as $key => $value) {
			$stocks_arr['lid'.$value['material_id']]=$value;
		}
		$stocks = array();
		foreach ($stocks_goods as $key => $val) {
			$stocks['lid'.$val['lid']]=$val;
			if (isset($stocks_arr['lid'.$val['lid']])) {
				$stocks['lid'.$val['lid']]['max_stock']=$stocks_arr['lid'.$val['lid']]['max_stock'];
				$stocks['lid'.$val['lid']]['safe_stock']=$stocks_arr['lid'.$val['lid']]['safe_stock'];
			} else{
				$stocks['lid'.$val['lid']]['max_stock']='';
				$stocks['lid'.$val['lid']]['safe_stock']='';
			}
		}
		// p($stocks);

		$product_name = '';
		$product_lost = '';
		if($stocks){
			// p($stocks);
			foreach ($stocks as  $stock) {
				//判断是否有最大库存数据 
				// 有:比较实时库存和最大库存的一半
				// 		大于 跳过
				// 		小于 执行添加购物车
				// 无:代表这最近一个月没有消耗 ,查看库存是否为0
				// 		为0 提示该产品需要手动添加购物车
				// 		不为0 不进行任何操作
				if($stock['max_stock']){
					//如果原料库存少于最大库存的一半就将该产品列入添加队列
					if (($stock['max_stock']*2/3) > $stock['stock']  ) {
						//按照总部指定仓库查询原料 , 没有则为自采购原料
						$sql1 = 'select g.goods_code,g.goods_name,g.lid as glid,g.main_picture,g.original_price,g.member_price,g.goods_unit,c.company_name,c.dpid,mc.lid,mc.category_name,gm.material_code,mu.unit_ratio from nb_goods g '
								.' left join nb_company c on(c.dpid=g.dpid) '
								.' left join nb_material_category mc on (mc.lid=g.category_id )'
								.' left join nb_goods_material gm on (g.lid=gm.goods_id )'
								.' left join nb_material_unit_ratio mu ON( gm.unit_code=mu.unit_code and mu.dpid='.$companyId.' )'
								.' where gm.material_code = '.$stock['mphs_code'].' and g.dpid in(select psgd.stock_dpid from nb_peisong_group_detail psgd where psgd.peisong_group_id=(select peisong_id from nb_company_property where dpid='.$this->companyId.') and psgd.mphs_code='.$stock['mphs_code'].')';
						$product = $db->createCommand($sql1)->queryRow();
						// p($product);
						if ($product) {
							$num = ceil(($stock['max_stock']-$stock['safe_stock'])/$product['unit_ratio']);
							$goods_cart = new GoodsCarts();
							$se=new Sequence("goods_carts");
							$lid = $se->nextval();
							$is_sync = DataSync::getInitSync();
							$goods_cart->lid = $lid;
							$goods_cart->dpid = $this->companyId;
							$goods_cart->create_at = date('Y-m-d H:i:s',time());
							$goods_cart->update_at = date('Y-m-d H:i:s',time());
							$goods_cart->stock_dpid = $product['dpid'];
							$goods_cart->goods_name = $product['goods_name'];
							$goods_cart->goods_id = $product['glid'];
							$goods_cart->goods_code = $product['goods_code'];
							$goods_cart->material_code = $product['material_code'];
							$goods_cart->user_id = $user_id;
							$goods_cart->user_name = $user_name;
							$goods_cart->promotion_price = $product['member_price'];
							$goods_cart->price = $product['original_price'];
							$goods_cart->num = $num;
							$goods_cart->end_time = '';
							$goods_cart->delete_flag=0;
							$goods_cart->is_sync = $is_sync;
							$goods_cart->insert();
						} else{
							//没有查找到商品原料
							$product_lost .= ' '.$stock['material_name'];
						}
					}
				}else{
					//没有该原料的使用数据
					if ($stock['stock']==0 || $stock['stock']<0) {
						$product_name .= ' '.$stock['material_name'];
					}
				}
			}
		}
		echo json_encode($product_name.'-'.$product_lost);exit;
	}

}