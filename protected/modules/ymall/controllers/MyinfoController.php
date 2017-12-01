<?php

class MyinfoController extends BaseYmallController
{

	//我的信息  订单的四大类型
	public function actionIndex()
	{
		$success = Yii::app()->request->getParam('success');
		//查询提交者信息
		$user_id = substr(Yii::app()->user->userId,0,10);
		$user_name = Yii::app()->user->name;
		$db = Yii::app()->db;
		//查询用户登录信息
		$sql0 ='select t.*,c.*,c.mobile as cmobile,c.email as cemail from nb_user t left join nb_company c on(t.dpid=c.dpid) where t.lid='.$user_id.' and t.username="'.$user_name.'"';
		// $user_info = User::model()->find('lid=:lid and username=:username and delete_flag=0',array(':lid'=>$user_id,':username'=>$user_name));
		$user_info = $db->createCommand($sql0)->queryRow();
		// p($user_info);
		//客服电话
		$sqll ='select telephone from nb_company where  type= 0 and dpid in(select comp_dpid from nb_company where dpid = '.$this->companyId.') and delete_flag=0';
		$phone = $db->createCommand($sqll)->queryRow();
		// p($phone);
		//查询未支付订单
		$sql = 'select god.*,go.*,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name from nb_goods_order_detail god '
		.' left join nb_goods_order go on(go.account_no=god.account_no) '
		.' left join nb_company c on(c.dpid=god.stock_dpid) '
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' where god.dpid='.$this->companyId
		.' and go.order_status=0'
		.' and go.pay_status=0'
		.' and go.paytype=1'
		.' and go.user_id='.$user_id
		.' and god.delete_flag=0'
		.' order by god.stock_dpid';
		$products_nopay = $db->createCommand($sql)->queryAll();
		$materials_nopay =array();
		foreach ($products_nopay as $key => $product) {
			if(!isset($materials_nopay[$product['account_no']])){
				$materials_nopay[$product['account_no']] = array();
			}
			array_push($materials_nopay[$product['account_no']], $product);
		}
		// p($materials_nopay);

		//查询待发货订单

		$sql1 = 'select god.*,go.*,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name from nb_goods_order_detail god '
		.' left join nb_goods_order go on(go.account_no=god.account_no)'
		.' left join nb_company c on(c.dpid=god.stock_dpid)'
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' where god.dpid= '.$this->companyId.' and go.user_id='.$user_id.' and god.delete_flag=0'
		.' and (( go.pay_status = 1) or ( go.pay_status = 0 and go.paytype = 2))'
		.' and go.order_status < 4 or go.order_status = 4'
		.' order by god.stock_dpid';
		$products_pay = $db->createCommand($sql1)->queryAll();
		$materials_pay =array();
		foreach ($products_pay as $product) {
			if(!isset($materials_pay[$product['account_no']])){
				$materials_pay[$product['account_no']] = array();
			}
			array_push($materials_pay[$product['account_no']], $product);
		}
		// p($materials_pay);


		//查询已发货
		$sql2 = 'select god.*,go.goods_address_id,go.user_id,go.username,go.order_status,go.order_type,go.should_total,go.reality_total,go.paytype,go.pay_status,go.pay_time,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name,gis.dpid,gis.compid,gis.goods_delivery_id,gis.goods_order_id,gis.goods_address_id,gis.goods_order_accountno,gis.invoice_accountno,gis.auditor,gis.operators,gis.sent_type,gis.sent_personnel,gis.mobile,gis.status,gis.invoice_amount,gis.remark,gis.gidremark from nb_goods_order_detail god'
		.' left join nb_goods_order go on(go.account_no=god.account_no)'
		.' left join nb_company c on(c.dpid=god.stock_dpid)'
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' left join (
		 		SELECT gi.dpid,gi.create_at as time,gi.compid,gi.sent_type,gi.goods_delivery_id,gi.goods_order_id,gi.goods_address_id,gi.goods_order_accountno,gi.invoice_accountno,gi.auditor,gi.operators,gi.sent_personnel,gi.mobile,gi.status,gi.invoice_amount,gi.pay_status,gi.remark,gid.goods_id,gid.goods_code,gid.material_code,gid.price,gid.num,gid.remark as gidremark FROM nb_goods_invoice gi LEFT JOIN nb_goods_invoice_details gid ON (gi.lid = gid.goods_invoice_id )
		 				) gis on ( gis.goods_order_accountno=go.account_no and gis.dpid=god.stock_dpid and gis.goods_id=god.goods_id)'
		.' where god.dpid='.$this->companyId
			.' and go.order_status=5'
			.' and go.user_id='.$user_id
			.' and god.delete_flag=0'
			.' and ((go.order_type=1 and go.pay_status=1) or (go.paytype=2 and go.pay_status=0)) '
			.' order by gis.time';

		$products_send = $db->createCommand($sql2)->queryAll();
		$materials_send =array();
		foreach ($products_send as $product) {
			if ($product['status'] !=2) {
				if(!isset($materials_send[$product['account_no']])){
					$materials_send[$product['account_no']] = array();
				}
				array_push($materials_send[$product['account_no']], $product);
			}
		}
		// p($materials_send);


		// 查询已收货

		$sql3 = 'select god.*,go.goods_address_id,go.user_id,go.username,go.order_status,go.order_type,go.should_total,go.reality_total,go.paytype,go.pay_status,go.pay_time,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name,gis.dpid,gis.compid,gis.goods_delivery_id,gis.goods_order_id,gis.goods_address_id,gis.goods_order_accountno,gis.invoice_accountno,gis.auditor,gis.operators,gis.sent_personnel,gis.mobile,gis.status,gis.invoice_amount,gis.sent_type,gis.remark,gis.gidremark from nb_goods_order_detail god'
		.' left join nb_goods_order go on(go.account_no=god.account_no)'
		.' left join nb_company c on(c.dpid=god.stock_dpid)'
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' left join (
		 		SELECT gi.dpid,gi.compid,gi.sent_type,gi.goods_delivery_id,gi.goods_order_id,gi.goods_address_id,gi.goods_order_accountno,gi.invoice_accountno,gi.auditor,gi.operators,gi.sent_personnel,gi.mobile,gi.status,gi.invoice_amount,gi.pay_status,gi.remark,gid.goods_id,gid.goods_code,gid.material_code,gid.price,gid.num,gid.remark as gidremark FROM nb_goods_invoice gi LEFT JOIN nb_goods_invoice_details gid ON (gi.lid = gid.goods_invoice_id and gi.status = 2)
		 				) gis on ( gis.goods_order_accountno=go.account_no and gis.dpid=god.stock_dpid and gis.goods_id=god.goods_id)'
		.' where god.dpid='.$this->companyId
		.' and go.user_id='.$user_id
		.' and god.delete_flag=0'
		// .' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))'//查询最近一个月的订单
		.' and ((go.order_status=5 and go.pay_status=1  and go.paytype=1) or (go.order_status=5 and go.pay_status=0 and go.paytype=2))'
		.' order by god.stock_dpid';

		$products_getted = $db->createCommand($sql3)->queryAll();
		$materials_get =array();
		foreach ($products_getted as $product) {
			if ($product['invoice_accountno']) {
				if(!isset($materials_get[$product['account_no']])){
					$materials_get[$product['account_no']] = array();
				}
				array_push($materials_get[$product['account_no']], $product);
			}
		}
		// p($products_getted);
		$this->render('myinfo',array(
			'phone'=>$phone,
			'user_info'=>$user_info,
			'materials_nopay'=>$materials_nopay,
			'materials_pay'=>$materials_pay,
			'materials_send'=>$materials_send,
			'materials_get'=>$materials_get,
		));
	}

	public function actionNormalsetting()
	{

		$this->render('normalsetting',array(
		));
	}

	public function actionGoodsRejected()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$account_no = Yii::app()->request->getParam('account_no',0);
		if ($account_no) {
			$str=' and gst.goods_order_accountno='.$account_no;
		}else{
			$str='';
		}
		$sql = 'select g.goods_name,g.main_picture,gst.* from nb_goods_stock_taking gst'
				.' left join nb_goods_order go on(go.account_no=gst.goods_order_accountno)'
				.' left join nb_goods g on(g.lid=gst.goods_id)'
				.' where go.dpid='.$this->companyId
				.' and go.user_id='.$user_id
				.$str
				.' order by gst.create_at desc';
		$goods = $db->createCommand($sql)->queryAll();
		$goods_rejecteds =array();
		foreach ($goods as $key => $good) {
			if(!isset($goods_rejecteds[$good['goods_order_accountno']])){
				$goods_rejecteds[$good['goods_order_accountno']] = array();
			}
			array_push($goods_rejecteds[$good['goods_order_accountno']], $good);
		}
		// p($goods_rejecteds);
		$this->render('goodsRejected',array(
			'goods_rejecteds'=>$goods_rejecteds,
			'account_no'=>$account_no,
		));
	}

	//查看全部订单
	public function actionGoodsOrderAll()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$up = Yii::app()->request->getParam('up');
		$date = Yii::app()->request->getParam('date',0);
		if($date){
			$b_time =' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP("'.$date.' 00:00:00")';
			$e_time =' and UNIX_TIMESTAMP(go.create_at)<UNIX_TIMESTAMP("'.$date.' 23:59:59")';
		}else{
			$b_time = '';
			$e_time = '';
		}
		if(Yii::app()->request->isAjaxRequest){
			$offset = $up*10;
			$sql = 'select go.* from nb_goods_order go where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' order by go.create_at desc limit '.$offset.',10';
			$goods_orders = $db->createCommand($sql)->queryAll();
			echo json_encode($goods_orders);exit;
		}else{
			$sql = 'select go.* from nb_goods_order go where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' order by go.create_at desc limit 0,10';
			$goods_orders = $db->createCommand($sql)->queryAll();
		}
		// p($goods_orders);

		$this->render('goodsOrderAll',array(
			'goods_orders'=>$goods_orders,
			'date'=>$date,
		));
	}

	//查看待支付订单
	public function actionGoodsOrderNopay()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$up = Yii::app()->request->getParam('up');
		$date = Yii::app()->request->getParam('date',0);
		if($date){
			$b_time = ' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP("'.$date.' 00:00:00")';
			$e_time = ' and UNIX_TIMESTAMP(go.create_at)<UNIX_TIMESTAMP("'.$date.' 23:59:59")';
		}else{
			$b_time = '';
			$e_time = '';
		}
		if(Yii::app()->request->isAjaxRequest){
			$offset = $up*10;
			$sql = 'select go.* from nb_goods_order go where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.paytype=1 and go.pay_status=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' order by go.create_at desc limit '.$offset.',10';
			$goods_orders = $db->createCommand($sql)->queryAll();
			echo json_encode($goods_orders);exit;
		}else{
			$sql = 'select go.* from nb_goods_order go where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.paytype=1 and go.pay_status=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' order by go.create_at desc limit 0,10';
			$goods_orders = $db->createCommand($sql)->queryAll();
		}
		// p($goods_orders);

		$this->render('goodsOrderNopay',array(
			'goods_orders'=>$goods_orders,
			'date'=>$date,
		));
	}

	//查看待发货订单
	public function actionGoodsOrderNosent()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$up = Yii::app()->request->getParam('up');
		$date = Yii::app()->request->getParam('date',0);
		if($date){
			$b_time =' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP("'.$date.' 00:00:00")';
			$e_time =' and UNIX_TIMESTAMP(go.create_at)<UNIX_TIMESTAMP("'.$date.' 23:59:59")';
		}else{
			$b_time = '';
			$e_time = '';
		}
		if(Yii::app()->request->isAjaxRequest){
			$offset = $up*10;
			$sql = 'select go.* from nb_goods_order go'
			.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and (( go.pay_status = 1) or ( go.pay_status = 0 and go.paytype = 2))'
				.' and go.order_status < 4 or go.order_status = 4'
				.' order by go.create_at desc limit '.$offset.',10';
			$goods_orders = $db->createCommand($sql)->queryAll();
			echo json_encode($goods_orders);exit;
		}else{
			$sql = 'select go.* from nb_goods_order go'
			.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and (( go.pay_status = 1) or ( go.pay_status = 0 and go.paytype = 2))'
				.' and go.order_status < 4 or go.order_status = 4'
				.' order by go.create_at desc limit 0,10';
			$goods_orders = $db->createCommand($sql)->queryAll();
		}
		// p($goods_orders);

		$this->render('goodsOrderNosent',array(
			'goods_orders'=>$goods_orders,
			'date'=>$date,
		));
	}

	//查看待接收订单
	public function actionGoodsOrderNoget()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$up = Yii::app()->request->getParam('up');
		$date = Yii::app()->request->getParam('date',0);
		if($date){
			$b_time =' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP("'.$date.' 00:00:00")';
			$e_time =' and UNIX_TIMESTAMP(go.create_at)<UNIX_TIMESTAMP("'.$date.' 23:59:59")';
		}else{
			$b_time = '';
			$e_time = '';
		}
		if(Yii::app()->request->isAjaxRequest){
			$offset = $up*10;
			$sql = 'select go.*,gd.*,gi.*,gi.status as gi_status from nb_goods_order go'
				.' left join nb_goods_delivery gd on(go.account_no=gd.goods_order_accountno)'
				.' left join nb_goods_invoice gi on(go.account_no=gi.goods_order_accountno)'
				.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and go.order_status=5'
				.' and ((go.order_type=1 and go.pay_status=1) or (go.paytype=2 and go.pay_status=0)) '
				.' and (gi.status=0 or gi.status=1)'
				.' group by go.account_no order by go.create_at desc limit '.$offset.',10';
			$goods_orders = $db->createCommand($sql)->queryAll();
			echo json_encode($goods_orders);exit;
		}else{
			$sql = 'select go.*,gd.*,gi.*,gi.status as gi_status from nb_goods_order go'
				.' left join nb_goods_delivery gd on(go.account_no=gd.goods_order_accountno)'
				.' left join nb_goods_invoice gi on(go.account_no=gi.goods_order_accountno)'
				.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and go.order_status=5'
				.' and ((go.order_type=1 and go.pay_status=1) or (go.paytype=2 and go.pay_status=0)) '
				.' and (gi.status=0 or gi.status=1)'
				.' group by go.account_no order by go.create_at desc limit 0,10';
			$goods_orders = $db->createCommand($sql)->queryAll();
		}
		// p($goods_orders);

		$this->render('goodsOrderNoget',array(
			'goods_orders'=>$goods_orders,
			'date'=>$date,
		));
	}

	//查看已签收订单
	public function actionGoodsOrderGetted()
	{
		$db = Yii::app()->db;
		$user_id = substr(Yii::app()->user->userId,0,10);
		$up = Yii::app()->request->getParam('up');
		$date = Yii::app()->request->getParam('date',0);
		if($date){
			$b_time =' and UNIX_TIMESTAMP(go.create_at)>UNIX_TIMESTAMP("'.$date.' 00:00:00")';
			$e_time =' and UNIX_TIMESTAMP(go.create_at)<UNIX_TIMESTAMP("'.$date.' 23:59:59")';
		}else{
			$b_time = '';
			$e_time = '';
		}
		if(Yii::app()->request->isAjaxRequest){
			$offset = $up*10;
			$sql = 'select go.*,gd.*,gi.*,gi.status as gi_status from nb_goods_order go'
				.' left join nb_goods_delivery gd on(go.account_no=gd.goods_order_accountno)'
				.' left join nb_goods_invoice gi on(go.account_no=gi.goods_order_accountno)'
				.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and go.order_status=5'
				.' and ((go.order_type=1 and go.pay_status=1) or (go.paytype=2 and go.pay_status=0)) '
				.' and gi.status=2'
				.' group by go.account_no order by go.create_at desc limit '.$offset.',10';
			$goods_orders = $db->createCommand($sql)->queryAll();
			echo json_encode($goods_orders);exit;
		}else{
			$sql = 'select go.*,gd.*,gi.*,gi.status as gi_status from nb_goods_order go'
				.' left join nb_goods_delivery gd on(go.account_no=gd.goods_order_accountno)'
				.' left join nb_goods_invoice gi on(go.account_no=gi.goods_order_accountno)'
				.' where go.dpid='.$this->companyId
				.' and go.delete_flag=0 and go.user_id='.$user_id
				.$b_time
				.$e_time
				.' and go.order_status=5'
				.' and ((go.order_type=1 and go.pay_status=1) or (go.paytype=2 and go.pay_status=0)) '
				.' and gi.status=2'
				.' group by go.account_no order by go.create_at desc limit 0,10';
			$goods_orders = $db->createCommand($sql)->queryAll();
		}
		// p($goods_orders);

		$this->render('goodsOrderGetted',array(
			'goods_orders'=>$goods_orders,
			'date'=>$date,
		));
	}

	public function actionOrderDetail()
	{
		// p($account_no);
		$db = Yii::app()->db;
		$account_no = Yii::app()->request->getParam('account_no');
		$type = Yii::app()->request->getParam('type',0);
		$user_id = substr(Yii::app()->user->userId,0,10);
		$sql = 'select gorder.*,gdel.*,gin.*,ga.pcc,ga.street,ga.mobile as amobile,ga.name as ganame,g.main_picture,c.company_name '
		.' from (select go.dpid,go.create_at,go.account_no,go.goods_address_id,go.username,go.user_id,go.order_status,go.reality_total,go.paytype,go.pay_status,god.stock_dpid,god.goods_name,god.goods_id,god.goods_code,god.material_code,god.price,god.num from nb_goods_order go left join nb_goods_order_detail god on ( go.account_no=god.account_no) ) gorder '
		.' left join (select gd.create_at as create_atd,gd.goods_order_accountno as dgoods_order_accountno,gd.auditor as dauditor,gd.operators as doperators,gd.delivery_accountno,gd.status as dstatus,gd.delivery_amount,gd.pay_status as dpay_status,gdd.dpid as stock_dpidd,gdd.goods_id as dgoods_id,gdd.goods_code as dgoods_code,gdd.material_code as dmaterial_code,gdd.price as dprice,gdd.num as dnum,gdd.pici from nb_goods_delivery gd left join nb_goods_delivery_details gdd on ( gd.lid=gdd.goods_delivery_id) 
			) gdel on ( gorder.account_no=gdel.dgoods_order_accountno and gorder.goods_id=gdel.dgoods_id)'
		.' left join (select gi.create_at as create_ati,gi.auditor as iauditor,gi.goods_order_accountno as igoods_order_accountno,gi.operators as operators,gi.invoice_accountno,gi.sent_type,gi.sent_personnel,gi.mobile,gi.status as istatus,gi.invoice_amount,gi.pay_status as ipay_status,gid.dpid as stock_dpidi,gid.goods_id as igoods_id,gid.goods_code as igoods_code,gid.material_code as imaterial_code,gid.price as iprice,
		gid.num as inum from nb_goods_invoice gi left join nb_goods_invoice_details gid on ( gi.lid=gid.goods_invoice_id)
		) gin on (gorder.account_no=gin.igoods_order_accountno and gorder.goods_id=gin.igoods_id)'
		.' left join nb_goods_address ga on(gorder.goods_address_id=ga.lid)'
		.' left join nb_goods g on(gorder.goods_id=g.lid)'
		.' left join nb_company c on(gorder.stock_dpid=c.dpid)'
		.' where gorder.dpid='.$this->companyId.' and gorder.user_id='.$user_id.' and gorder.account_no='.$account_no
		.' group by goods_id';
		$goods_orders = $db->createCommand($sql)->queryAll();
		// p($goods_orders);
		$this->render('orderDetail',array(
			'goods_orders'=>$goods_orders,
			'type'=>$type,
		));
	}

	public function actionStockSetting()
	{
		$model = StockSetting::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
		// p($model);
		if(Yii::app()->request->isAjaxRequest){
			if (empty($model)) {
				$model = new StockSetting();
				$se=new Sequence("stock_setting");
				$lid = $se->nextval();
				$model->create_at=date('Y-m-d H:i:s',time());
				$model->update_at=date('Y-m-d H:i:s',time());
				$model->update_at=$lid;
			}
			$csales_day = Yii::app()->request->getParam('csales_day');
			$csafe_min_day = Yii::app()->request->getParam('csafe_min_day');
			$csafe_max_day = Yii::app()->request->getParam('csafe_max_day');
			$model->csales_day=$csales_day;
			$model->csafe_min_day=$csafe_min_day;
			$model->csafe_max_day=$csafe_max_day;
			if ($model->save()) {
				echo json_encode(1111);exit;
			}else{
				echo json_encode(2222);exit;
			}
		}
		$this->render('stockSetting',array(
			'model'=>$model,
		));
	}

	public function actionSureorder()
	{
		$invoice_accountno = Yii::app()->request->getParam('invoice_accountno');
		$account_no = Yii::app()->request->getParam('account_no');
		$user_id = substr(Yii::app()->user->userId,0,10);
		$db = Yii::app()->db;

		$sql = 'select god.*,go.goods_address_id,go.user_id,go.username,go.order_status,go.order_type,go.should_total,go.reality_total,go.paytype,go.pay_status,go.pay_time,g.description,g.goods_unit,g.store_number,g.main_picture,c.company_name,gis.dpid,gis.gidlid,gis.compid,gis.goods_delivery_id,gis.goods_order_id,gis.goods_address_id,gis.goods_order_accountno,gis.invoice_accountno,gis.auditor,gis.operators,gis.sent_personnel,gis.mobile,gis.status,gis.invoice_amount,gis.remark,gis.gidremark from nb_goods_order_detail god'
		.' left join nb_goods_order go on(go.account_no=god.account_no)'
		.' left join nb_company c on(c.dpid=god.stock_dpid)'
		.' left join nb_goods g on (g.lid=god.goods_id and g.goods_code=god.goods_code )'
		.' left join (
		 		SELECT gi.dpid,gi.compid,gi.goods_delivery_id,gi.goods_order_id,gi.goods_address_id,gi.goods_order_accountno,gi.invoice_accountno,gi.auditor,gi.operators,gi.sent_personnel,gi.mobile,gi.status,gi.invoice_amount,gi.pay_status,gi.remark,gid.lid as gidlid,gid.goods_id,gid.goods_code,gid.material_code,gid.price,gid.num,gid.remark as gidremark FROM nb_goods_invoice gi LEFT JOIN nb_goods_invoice_details gid ON (gi.lid = gid.goods_invoice_id and gi.status = 1) where gi.invoice_accountno = '.$invoice_accountno.'
		 				) gis on ( gis.goods_order_accountno=go.account_no and gis.dpid=god.stock_dpid and gis.goods_id=god.goods_id)'
		.' where god.dpid='.$this->companyId.' and god.account_no='.$account_no.' and go.user_id='.$user_id.'  and god.delete_flag=0 order by god.stock_dpid';

		$products_getted = $db->createCommand($sql)->queryAll();
		// p($products_getted);
		$materials_get =array();
		foreach ($products_getted as $key => $product) {
			if ($product['invoice_accountno']) {
				if(!isset($materials_get[$product['invoice_accountno']])){
					$materials_get[$product['invoice_accountno']] = array();
				}
				array_push($materials_get[$product['invoice_accountno']], $product);
			}
		}
		// p($materials_get);
		$this->render('sureorder',array(
			'account_no'=>$account_no,
			'materials_get'=>$materials_get,
		));
	}

	public function actionSureorderd()
	{
		$invoice_accountno = Yii::app()->request->getParam('invoice_accountno');
		$account_no = Yii::app()->request->getParam('account_no');
		$value = Yii::app()->request->getParam('value',0);
		$infos = Yii::app()->request->getParam('info');
		$user_id = substr(Yii::app()->user->userId,0,10);
		$db = Yii::app()->db;
		if ($value) {
			//有运输损耗  更新invoice表状态  损耗记录  去损商品入库
			$sql = 'update nb_goods_invoice set status=2 where invoice_accountno='.$invoice_accountno.' and goods_order_accountno='.$account_no;
			$command=$db->createCommand($sql)->execute();
			$companyId = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId))->comp_dpid;

			//查询总部原料的单位
			$sql1 = 'SELECT pm.lid,pm.mphs_code,gi.dpid,gids.goods_invoice_id,gids.gidlid,gids.price,gids.num,gids.unit_code,gids.goods_id,gids.goods_code,gids.material_code,gids.unit_ratio FROM nb_goods_invoice gi
			LEFT JOIN(
					SELECT gid.goods_invoice_id,gid.lid as gidlid,gid.price,gid.num,gmu.dpid,gmu.unit_code,gid.goods_id,gid.goods_code,gid.material_code,gmu.unit_ratio FROM nb_goods_invoice_details gid
							LEFT JOIN (
								SELECT gm.dpid,gm.unit_code,gm.goods_id,gm.goods_code,gm.material_code,mu.unit_ratio FROM nb_goods_material gm LEFT JOIN nb_material_unit_ratio mu ON( gm.unit_code=mu.unit_code ) WHERE mu.dpid='.$companyId.') gmu ON( gid.goods_code=gmu.goods_code AND gid.goods_id=gmu.goods_id)

					) gids ON (gi.lid = gids.goods_invoice_id )
			LEFT JOIN nb_product_material pm on(pm.mphs_code=gids.material_code and pm.dpid='.$this->companyId.')
			where gi.invoice_accountno = '.$invoice_accountno.' AND gi.goods_order_accountno='.$account_no;
			$products = $db->createCommand($sql1)->queryAll();
			$i=0;
			$j=0;
			$x=0;
			$transaction = $db->beginTransaction();
			try{
				foreach ($products as $key => $product) {
					foreach ($infos as $info) {
						$infod = explode('_', $info);
						if ($product['gidlid']==$infod[0]) {
							// p(111);
							//入库
							$se=new Sequence("product_material_stock");
							$lid = $se->nextval();
							$is_sync = DataSync::getInitSync();
							$data=array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,//公司的dpid
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$product['lid'],
								'mphs_code'=>$product['material_code'],
								'stock_day'=>30,
								'batch_stock'=>($product['num']-$infod[1])*$product['unit_ratio'],
								'stock'=>($product['num']-$infod[1])*$product['unit_ratio'],
								'free_stock'=>0,
								'stock_cost'=>($product['num']-$infod[1])*$product['price'],
								'delete_flag'=>0,
								'is_sync'=>$is_sync,
							);
							$info1 = Yii::app()->db->createCommand()->insert('nb_product_material_stock',$data);
							//记录该商品损耗
							if ($infod[1]) {
								$see=new Sequence("goods_stock_taking");
								$lidd = $see->nextval();
								$data1=array(
									'lid'=>$lidd,
									'dpid'=>$product['dpid'],//公司的dpid
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'goods_order_accountno'=>$account_no,
									'invoice_accountno'=>$invoice_accountno,
									'goods_id'=>$product['goods_id'],
									'goods_code'=>$product['goods_code'],
									'material_code'=>$product['material_code'],
									'price'=>$product['price'],
									'num'=>$infod[1],
									'status'=>0,
									'delete_flag'=>0,
									'is_sync'=>$is_sync,
								);
								$info2 = Yii::app()->db->createCommand()->insert('nb_goods_stock_taking',$data1);
								$x+=1;
							}
							if($info1){
								$i+=1;
							}
							if($info2){
								$j+=1;
							}
						}
					}
				}
				$transaction->commit();
				echo json_encode(1);exit;
			}catch (Exception $e){
				$transaction->rollback();
				echo json_encode(0);exit;
			}
		}else{
			//无运输损耗  更新invoice表状态  商品入库

			$sql = 'update nb_goods_invoice set status=2 where invoice_accountno='.$invoice_accountno.' and goods_order_accountno='.$account_no;
			$command=$db->createCommand($sql)->execute();


			$sql0 = 'update nb_goods_invoice set status=2 where invoice_accountno='.$invoice_accountno.' and goods_order_accountno='.$account_no;
			$command=$db->createCommand($sql0)->execute();
			// echo $command;exit;
			$companyId = Company::model()->find('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId))->comp_dpid;
			$sql1 = 'SELECT pm.lid,pm.mphs_code,gi.dpid,gids.goods_invoice_id,gids.price,gids.num,gids.unit_code,gids.goods_id,gids.goods_code,gids.material_code,gids.unit_ratio FROM nb_goods_invoice gi
			LEFT JOIN(
					SELECT gid.goods_invoice_id,gid.price,gid.num,gmu.dpid,gmu.unit_code,gid.goods_id,gid.goods_code,gid.material_code,gmu.unit_ratio FROM nb_goods_invoice_details gid
							LEFT JOIN (
								SELECT gm.dpid,gm.unit_code,gm.goods_id,gm.goods_code,gm.material_code,mu.unit_ratio FROM nb_goods_material gm LEFT JOIN nb_material_unit_ratio mu ON( gm.unit_code=mu.unit_code ) WHERE mu.dpid='.$companyId.') gmu ON( gid.goods_code=gmu.goods_code AND gid.goods_id=gmu.goods_id)

					) gids ON (gi.lid = gids.goods_invoice_id )
			LEFT JOIN nb_product_material pm on(pm.mphs_code=gids.material_code and pm.dpid='.$this->companyId.')
			where gi.invoice_accountno = '.$invoice_accountno.' AND gi.goods_order_accountno='.$account_no;
			$products = $db->createCommand($sql1)->queryAll();
			// p($products);
			$i=0;
			foreach ($products as $key => $product) {
				$se=new Sequence("product_material_stock");
				$lid = $se->nextval();
				$is_sync = DataSync::getInitSync();
				$data=array(
					'lid'=>$lid,
					'dpid'=>$this->companyId,//公司的dpid
					'create_at'=>date('Y-m-d H:i:s',time()),
					'update_at'=>date('Y-m-d H:i:s',time()),
					'material_id'=>$product['lid'],
					'mphs_code'=>$product['material_code'],
					'stock_day'=>30,
					'batch_stock'=>$product['num']*$product['unit_ratio'],
					'stock'=>$product['num']*$product['unit_ratio'],
					'free_stock'=>0,
					'stock_cost'=>$product['num']*$product['price'],
					'delete_flag'=>0,
					'is_sync'=>$is_sync,
				);
				$info = Yii::app()->db->createCommand()->insert('nb_product_material_stock',$data);
				if($info){
				$i+=1;
				}
			}
			if ($i==count($products)) {
				echo json_encode(1);exit;
			}else{
				echo json_encode(0);exit;
			}
		}
	}

	public function actionDelete_nopay()
	{
		$account_no = Yii::app()->request->getParam('account_no');
		// p($account_no);
		$info = GoodsOrder::model()->deleteAll('dpid=:dpid and account_no=:account_no',array(':dpid'=>$this->companyId,':account_no'=>$account_no));
		if($info){
			$infod = GoodsOrderDetail::model()->deleteAll('dpid=:dpid and account_no=:account_no',array(':dpid'=>$this->companyId,':account_no'=>$account_no));
			if ($infod) {
				echo json_encode(1);exit;
			}
		}else{
			echo json_encode(2);exit;
		}
	}
}