<?php

class YmallcartController extends BaseYmallController
{
	/**
	 * @Author    zhang
	 * @DateTime  2017-09-18T09:51:12+0800
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]        购物车列表页           [description]
	 */
	public function actionIndex()
	{

		// p(substr(Yii::app()->user->userId,0,10));
		// p(Yii::app()->user->name);

		$user_id = substr(Yii::app()->user->userId,0,10);

		$db = Yii::app()->db;
		$sql = 'select gc.*,g.description,g.goods_unit,g.store_number,g.main_picture,g.price as now_price,g.price as now_mbprice,c.company_name,mu.unit_name from nb_goods_carts gc '
				.' left join nb_company c on(c.dpid=gc.stock_dpid) '
				.' left join nb_goods g on (g.lid=gc.goods_id and g.goods_code=gc.goods_code )'
				.' left join nb_goods_material gm on (g.lid=gm.goods_id and g.goods_code=gm.goods_code )'
				.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=gm.unit_code)'
				.' where gc.dpid='.$this->companyId
				.' and gc.user_id='.$user_id
				.' and gc.delete_flag=0'
				.' order by gc.stock_dpid';
		$products = $db->createCommand($sql)->queryAll();
		// p($products);

		$materials =array();
		foreach ($products as $key => $product) {
			if(!isset($materials[$product['stock_dpid']])){
				$materials[$product['stock_dpid']] = array();
			}
			array_push($materials[$product['stock_dpid']], $product);
		}
		// $cart_num = $this->getCartsnum();
		// p($cart_num);
		$this->render('ymallcart',array(
			'materials'=>$materials,
			'companyId'=>$this->companyId,
			// 'cart_num'=>$cart_num['num'],
		));
	}

	/**
	 * @Author    zhang
	 * @DateTime  2017-09-18T09:53:01+0800
	 * @copyright [copyright]
	 * @license   [license]
	 * @version   [version]
	 * @return    [type]        添加购物车           [description]
	 */
	public function actionAddymallcart()
	{
		//接收ajax提交的商品信息
		$stock_dpid = Yii::app()->request->getParam('stock_dpid');
		$goods_name = Yii::app()->request->getParam('goods_name');
		$goods_id = Yii::app()->request->getParam('goods_id');
		$price = Yii::app()->request->getParam('price');
		$promotion_price = Yii::app()->request->getParam('promotion_price',0);
		$end_time = '';
		$goods_code = Yii::app()->request->getParam('goods_code');
		$material_code = Yii::app()->request->getParam('material_code');
		$num =  Yii::app()->request->getParam('num',1);
		//查询提交者信息
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		// p(Yii::app()->user->userId);
		if (!$promotion_price) {
			$promotion_price = $price;
		}

		if(Yii::app()->request->isAjaxRequest){
			//查询购物车中是否存在该商品, 存在直接数量加1 , 如果不存在则直接插入,
			$goods_cart = GoodsCarts::model()->find('dpid=:dpid and stock_dpid=:stock_dpid and goods_code=:goods_code and user_id=:user_id and delete_flag=0',array(':dpid'=>$this->companyId,':stock_dpid'=>$stock_dpid,':goods_code'=>$goods_code,':user_id'=>$user_id,));

			if (!empty($goods_cart)) {
				$goods_num = $goods_cart->num;
				$goods_cart->num = $goods_num + 1;
					// echo json_encode($goods_cart->num);exit;
				// 更新成功查询购物车是否增加数字
				if ($goods_cart->update()) {
					$num = $this->getCartsnum();
					echo json_encode($num);exit;
				}else {
					$num = $this->getCartsnum();
					echo json_encode($num);exit;
				}
			}else {
				$goods_cart = new GoodsCarts();
				$se=new Sequence("goods_carts");
				$lid = $se->nextval();
				$is_sync = DataSync::getInitSync();
				$goods_cart->lid = $lid;
				$goods_cart->dpid = $this->companyId;
				$goods_cart->create_at = date('Y-m-d H:i:s',time());
				$goods_cart->update_at = date('Y-m-d H:i:s',time());
				$goods_cart->stock_dpid = $stock_dpid;
				$goods_cart->goods_name = $goods_name;
				$goods_cart->goods_id = $goods_id;
				$goods_cart->goods_code = $goods_code;
				$goods_cart->material_code = $material_code;
				$goods_cart->user_id = $user_id;
				$goods_cart->user_name = $user_name;
				$goods_cart->promotion_price = $promotion_price;
				$goods_cart->price = $price;
				$goods_cart->num = $num;
				$goods_cart->end_time = $end_time;
				$goods_cart->delete_flag=0;
				$goods_cart->is_sync = $is_sync;
				if ($goods_cart->insert()) {
					$num = $this->getCartsnum();
					echo json_encode($num);exit;
				}else {
					$num = $this->getCartsnum();
					echo json_encode($num);exit;
				}
			}
		}
	}

	public function actionEditymallcart()
	{
		//接收ajax提交的商品信息
		$goods_num_edit = Yii::app()->request->getParam('goods_num_edit');
		//查询提交者信息
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		// Yii::app()->user->username;
		// Yii::app()->user->userId;
		$arr = array();
		foreach (explode(',',$goods_num_edit) as  $value) {
			$val = explode('_',$value);
			$arr[$val[0]] = $val[1];
		}
 		// print_r($arr[18]);exit;
		if(Yii::app()->request->isAjaxRequest){
			foreach ($arr as $key => $num) {
				//查询购物车中是否存在该商品, 存在直接数量加1 , 如果不存在则直接插入,价格不一致直接插入
				$goods_cart = GoodsCarts::model()->find('dpid=:dpid and lid=:lid and user_id=:user_id and delete_flag=0',array(':dpid'=>$this->companyId,':lid'=>$key,':user_id'=>$user_id));
				if (!empty($goods_cart)) {
					$goods_cart->num = $num ;
					if ($goods_cart->update()) {
						echo json_encode(1);exit;//更新成功
					}else {
						echo json_encode(2);exit;//更新失败
					}
				}else {
						echo json_encode(3);exit;//没有查询到商品,数据有问题
				}
			}
		}
	}

	public function actionDelete()
	{
		//接收ajax提交的商品信息
		$goods_num_edit = Yii::app()->request->getParam('goods_num_edit');
		$delete = Yii::app()->request->getParam('delete',0);
		//查询提交者信息
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		// Yii::app()->user->username;
		// Yii::app()->user->userId;
		$arr = array();
		foreach (explode(',',$goods_num_edit) as  $value) {
			$val = explode('_',$value);
			$arr[$val[0]] = $val[1];
		}
		if(Yii::app()->request->isAjaxRequest){
			$i = 0;
			foreach ($arr as $key => $num) {
				// p($key);
				$info = GoodsCarts::model()->find('dpid=:dpid and lid=:lid and user_id=:user_id',array(':dpid'=>$this->companyId,':lid'=>$key,':user_id'=>$user_id));
				if($info->delete()){
					$i+=1;
				}
			}
			if ($i==count($arr)) {
				echo json_encode(1);exit;
			}else if($i<count($arr)){
				echo json_encode(2);exit;
			}
		}
	}

	public function actionAddgoodsorder()
	{
		$lids = Yii::app()->request->getParam('lid');
		// $lids = explode(',',$lid);
		//生成订单号(账单号) 店铺id.时间戳
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		//查询默认的$goods_address_id
		$goods_address_id = GoodsAddress::model()->find('dpid=:dpid and user_id=:user_id and default_address = 1',array(':dpid'=>$this->companyId,':user_id'=>$user_id));
		if (empty($goods_address_id)) {
			$goods_address_id = GoodsAddress::model()->find('dpid=:dpid and user_id=:user_id',array(':dpid'=>$this->companyId,':user_id'=>$user_id));
			if (empty($goods_address_id)) {
				$this->redirect(array('address/addaddress' , 'companyId' => $this->companyId,'error'=>3)) ;
				exit;
			}
		}
		// p($goods_address_id);
		$goods_address_id = $goods_address_id->lid;
		$goods_address_id = Yii::app()->request->getParam('goods_address_id',$goods_address_id);

		$db = Yii::app()->db;
		$sql = 'select gc.dpid,gc.stock_dpid,gc.goods_name,gc.goods_id,gc.goods_code,gc.material_code,gc.promotion_price,gc.price,g.price as new_price,gc.num,gc.end_time from nb_goods_carts gc left join nb_goods g on(g.lid=gc.goods_id and g.goods_code=gc.goods_code ) where gc.lid in('.$lids.') and gc.delete_flag=0';
		$products = $db->createCommand($sql)->queryAll();
		$should_total = 0;
		$reality_total = 0;
		foreach ($products as $product) {
			$should_total += $product['num']*$product['price'];
			$reality_total += $product['num']*$product['new_price'];
		}
		if (!empty($lids)) {
			$transaction = $db->beginTransaction();
            try{
				$goods_order = new GoodsOrder();
				$se=new Sequence("goods_order");
				$lid = $se->nextval();
				$ses=new Sequence("goods_codes");
				$clid = $ses->nextval();
				$account_no = Common::getCodes($this->companyId,$lid,$clid);
				$is_sync = DataSync::getInitSync();
				$goods_order->lid = $lid;
				$goods_order->dpid = $this->companyId;
				$goods_order->create_at = date('Y-m-d H:i:s',time());
				$goods_order->update_at = date('Y-m-d H:i:s',time());
				$goods_order->account_no =$account_no;
				$goods_order->user_id = $user_id;
				$goods_order->username = $user_name;
				$goods_order->goods_address_id = $goods_address_id;
				$goods_order->order_status = 0;//订单状态
				$goods_order->order_type = 1;
				$goods_order->should_total = $should_total;
				$goods_order->reality_total = $reality_total;
				$goods_order->pay_status = 0;//未支付
				$goods_order->paytype = 1;//默认线上支付
				$goods_order->pay_time = 0;//不确定
				$goods_order->order_info = '';
				$goods_order->wayofpay = '微信线上支付';
				$goods_order->delete_flag=0;
				$goods_order->is_sync = $is_sync;
				if ($goods_order->insert()) {
					// 添加详情
					foreach ($products as $product) {
						$goods_order_detail = new GoodsOrderDetail();
						$se=new Sequence("goods_order_detail");
						$id = $se->nextval();
						$goods_order_detail->lid = $id;
						$goods_order_detail->dpid = $this->companyId;
						$goods_order_detail->create_at = date('Y-m-d H:i:s',time());
						$goods_order_detail->update_at = date('Y-m-d H:i:s',time());
						$goods_order_detail->account_no = $account_no;
						$goods_order_detail->stock_dpid = $product['stock_dpid'];
						$goods_order_detail->goods_name = $product['goods_name'];
						$goods_order_detail->goods_id = $product['goods_id'];
						$goods_order_detail->goods_order_id = $lid;
						$goods_order_detail->goods_code = $product['goods_code'];
						$goods_order_detail->material_code = $product['material_code'];
						$goods_order_detail->promotion_price = $product['promotion_price'];
						$goods_order_detail->price = $product['new_price'];/////////////////////
						$goods_order_detail->num = $product['num'];
						$goods_order_detail->end_time = $product['end_time'];
						$goods_order_detail->delete_flag=0;
						$goods_order_detail->is_sync = $is_sync;
						$goods_order_detail->insert();
				// p($goods_order_detail);
					}

				$sql = 'delete from nb_goods_carts  where lid in('.$lids.') and dpid=:dpid and delete_flag=0';
				$command=Yii::app()->db->createCommand($sql)->execute(array(':dpid'=>$this->companyId));
				}
				$transaction->commit();
				$url = $this->createUrl('ymallcart/orderlist');
                $this->redirect($url.'?companyId='.$this->companyId.'&account_no='.$account_no) ;
            }catch (Exception $e){
                $transaction->rollback();
                $this->redirect(array('ymallcart/index' , 'companyId' => $this->companyId,'error'=>1));
            }
		}
	}


	public function actionEditgoodsorder()
	{
		$account_no = Yii::app()->request->getParam('account_no');
		$daofu = Yii::app()->request->getParam('daofu',0);
		$textarea = Yii::app()->request->getParam('textarea');
		$pay_way = Yii::app()->request->getParam('pay_way');

		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		$order = GoodsOrder::model()->find('user_id=:user_id and account_no=:account_no and dpid=:dpid and delete_flag=0',array(':user_id'=>$user_id,':account_no'=>$account_no,':dpid'=>$this->companyId));
		if ($daofu==1) {
			if ($order) {
				$order->paytype = 2;
				$order->order_status = 3;
				$order->order_info = $textarea;
				$order->wayofpay = $pay_way;
				if ($order->update()) {
					$this->redirect(array('myinfo/goodsOrderCheck' , 'companyId' => $this->companyId,'success'=>1));
				}else{
					$this->redirect(array('myinfo/goodsOrderCheck' , 'companyId' => $this->companyId,'success'=>3));
				}
			} else {
				$this->redirect(array('myinfo/goodsOrderCheck' , 'companyId' => $this->companyId,'success'=>2));
			}
		}else if($daofu==0){//修改地址
			//查询默认的$goods_address_id
			$goods_address_id = GoodsAddress::model()->find('dpid=:dpid and user_id=:user_id and default_address = 1',array(':dpid'=>$this->companyId,':user_id'=>$user_id))->lid;
			$goods_address_id = Yii::app()->request->getParam('address_id',$goods_address_id);

			if ($order) {
				$order->goods_address_id = $goods_address_id;
				if ($order->update()) {
					$this->redirect(array('ymallcart/orderlist' , 'companyId' => $this->companyId,'account_no'=>$account_no,'success'=>1));
				}else{
					$this->redirect(array('ymallcart/orderlist' , 'companyId' => $this->companyId,'account_no'=>$account_no,'success'=>3));
				}
			} else {
				$this->redirect(array('ymallcart/orderlist' , 'companyId' => $this->companyId,'account_no'=>$account_no,'success'=>2));
			}
		}
	}


	//确认下单
	public function actionOrderlist()
	{
		$account_no = Yii::app()->request->getParam('account_no');
		$success = Yii::app()->request->getParam('success',0);
		//收货人地址
		// $user_id = substr(Yii::app()->user->userId,0,10);
		// $userId = Yii::app()->user->userId;
		$db = Yii::app()->db;
		$sql = 'select go.goods_address_id,ga.* from nb_goods_order go'
		.' left join nb_goods_address ga on(ga.lid=go.goods_address_id and go.dpid=ga.dpid )'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$address = $db->createCommand($sql)->queryrow();

		$sql1 = 'select go.lid,go.account_no from nb_goods_order go'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$golid = $db->createCommand($sql1)->queryrow();
		// p($golid);
		//以仓库分类订单详情表

		$sql2 = 'select god.*,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name,mu.unit_name from nb_goods_order_detail god '
				.' left join nb_company c on(c.dpid=god.stock_dpid) '
				.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
				.' left join nb_goods_material gm on (g.lid=gm.goods_id and g.goods_code=gm.goods_code )'
				.' left join (select m.unit_specifications,m.unit_name,m.dpid,mr.unit_code from nb_material_unit m inner join nb_material_unit_ratio mr on(m.lid=mr.stock_unit_id)) mu on(mu.dpid=c.comp_dpid and mu.unit_code=gm.unit_code)'
				.' where god.dpid='.$this->companyId
				.' and god.account_no='.$account_no
				.' and god.delete_flag=0'
				.' order by god.stock_dpid';
		$products = $db->createCommand($sql2)->queryAll();

		$materials =array();
		foreach ($products as $key => $product) {
			if(!isset($materials[$product['stock_dpid']])){
				$materials[$product['stock_dpid']] = array();
			}
			array_push($materials[$product['stock_dpid']], $product);
		}

		// p($materials);
		//实付款
		$sql3 = 'select go.reality_total from nb_goods_order go'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$reality_total = $db->createCommand($sql3)->queryRow();


		$sql4 = 'select comp_dpid from nb_company where dpid='.$this->companyId.' and delete_flag=0';
		$companyId = $db->createCommand($sql4)->queryrow();
		// p($companyId);

		$sql5 = 'select * from nb_company_property where dpid='.$this->companyId.' and delete_flag=0';
		$company_property = $db->createCommand($sql5)->queryrow();
		// p($company_property);

		$this->render('suretopay',array(
			'companyId'=>$companyId['comp_dpid'],
			'company_property'=>$company_property,
			'golid'=>$golid,
			'success'=>$success,
			'address'=>$address,
			'account_no'=>$account_no,
			'materials'=>$materials,
			'reality_total'=>$reality_total['reality_total'],
		));
	}

	// 微信支付
	public function actionWxPay()
	{
		$account_no = Yii::app()->request->getParam('account_no');
		$success = Yii::app()->request->getParam('success',0);
		// p($account_no);
		//收货人地址
		// $user_id = substr(Yii::app()->user->userId,0,10);
		// $userId = Yii::app()->user->userId;
		$db = Yii::app()->db;
		$sql = 'select go.goods_address_id,ga.* from nb_goods_order go'
		.' left join nb_goods_address ga on(ga.lid=go.goods_address_id and go.dpid=ga.dpid )'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$address = $db->createCommand($sql)->queryrow();

		$sql1 = 'select go.lid,go.account_no from nb_goods_order go'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$golid = $db->createCommand($sql1)->queryrow();
		// p($golid);
		//以仓库分类订单详情表


		// p($materials);
		//实付款
		$sql3 = 'select go.reality_total from nb_goods_order go'
		.' where go.dpid='.$this->companyId
		.' and go.account_no='.$account_no
		.' and go.delete_flag=0';
		$reality_total = $db->createCommand($sql3)->queryRow();


		$sql4 = 'select comp_dpid from nb_company where dpid='.$this->companyId.' and delete_flag=0';
		$companyId = $db->createCommand($sql4)->queryrow();
		// p($companyId);

		$sql5 = 'select * from nb_company_property where dpid='.$this->companyId.' and delete_flag=0';
		$company_property = $db->createCommand($sql5)->queryrow();
		// p($company_property);

		$this->render('wxPay',array(
			'companyId'=>$companyId['comp_dpid'],
			'company_property'=>$company_property,
			'golid'=>$golid,
			'success'=>$success,
			'address'=>$address,
			'account_no'=>$account_no,
			'reality_total'=>$reality_total['reality_total'],
		));
	}

}