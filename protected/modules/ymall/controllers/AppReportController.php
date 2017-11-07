<?php
class AppReportController extends Controller
{
	public $companyId;
	public $company;
	public $brandUser;
	public $layout = '/layouts/mainappreport';
	public function init()
	{
		$companyId = Yii::app()->request->getParam('companyId');
		$this->companyId = $companyId;
		$this->company = WxCompany::get($this->companyId);
	}
	public function beforeAction($actin){
		$dpidSelf = Yii::app()->session['dpid_self'];
		if($dpidSelf==1){
			$comdpid = $this->company['dpid'];
		}else{
			$comdpid = $this->company['comp_dpid'];
		}
		$userId = Yii::app()->session['userId-'.$comdpid];
		//如果微信浏览器
		if(Helper::isMicroMessenger()){
			if(empty($userId)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			if(empty($this->brandUser)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'url'=>urlencode($url)));
				exit;
			}
		}else{
			//pc 浏览
			$userId = 2130;
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			$userId = $this->brandUser['lid'];
			$userDpid = $this->brandUser['dpid'];
			Yii::app()->session['userId-'.$userDpid] = $userId;
		}
		return true;
	}
	private function type(){
		$type = Yii::app()->request->getParam('type',0);
		$fensql = "select lid,group_name from nb_area_group where lid=".$type." and type=3 and delete_flag=0";
		$fens = Yii::app()->db->createCommand($fensql)->queryRow();
		return $fens;	
	}
	public function actionAdminlist(){
		$companyId = $this->companyId;
		$fensql = "select lid,group_name from (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) a left join (select lid,group_name,dpid from nb_area_group where type=3 and delete_flag=0) g on a.admin_dpid=g.dpid group by lid";
		$fens = Yii::app()->db->createCommand($fensql)->queryAll();
		// var_dump($fens);exit();
		$this->render('adminlist',array(
			'fens'=>$fens,
			'companyId'=>$companyId
			));
	}
	public function actionIndex(){
		$companyId = $this->companyId;
		if(empty(Yii::app()->request->getParam('type'))){
			$fensql = "select lid,group_name from (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) a left join (select lid,group_name,dpid from nb_area_group where type=3 and delete_flag=0) g on a.admin_dpid=g.dpid group by lid";
			$fens = Yii::app()->db->createCommand($fensql)->queryAll();
			foreach ($fens as $fen) {
				if(!empty($fen['group_name'])){
				  $this->redirect(array('appReport/adminlist','companyId'=>$companyId));
				}
			}
		}
		 var_dump($companyId);exit;
		
	}
	public function actionYysj(){
		$companyId = $this->companyId;
		$type = $this->type();
		$date = Yii::app()->request->getParam('date');
		$todayProfit = array();
		$Recharges = array();
		$records = array();
		$refunds = array();
		$Paymentmethod = array();
		if(!empty($type)){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
	    		if(!empty($date)){
				$Profitsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid in(".$fens.") and order_status in (3,4,8)";
				// echo $Profitsql;exit;
				$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
				// var_dump($todayProfit);exit;
				$Paymentsql = "select y.paytype,count(y.paytype) as counts,sum(y.pay_amount) as pay_amount,y.payment_method_id from nb_order_pay y,(select lid from nb_order where order_status in (3,4,8) and dpid in(".$fens.") and create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59') o where y.dpid in(".$fens.") and y.order_id=o.lid and y.paytype !=11 and y.create_at >='".$date['start']." 00:00:00' and y.create_at <= '".$date['End']." 23:59:59' group by y.paytype";
				// echo $Paymentsql;exit();
				$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryAll();
				// var_dump($Paymentmethod);exit();
				$Rechargesql = "select sum(reality_money) as reality_money,sum(give_money) as give_money from nb_member_recharge where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid in (".$fens.")";
				$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
				// var_dump($Recharges);exit;
				$recordsql = "select sum(recharge_money) as recharge_money,sum(cashback_num) as cashback_num from nb_recharge_record where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid in (".$fens.")";
				$records = Yii::app()->db->createCommand($recordsql)->queryAll();
				// var_dump($records);exit;
				$refundsql = "select sum(pay_amount) as pay_amount,count(*) as count from nb_order_pay where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and pay_amount<0 and dpid in (".$fens.")";
				// echo $refundsql;exit;
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
			}
    	}else{
	    		if(!empty($date)){
				$Profitsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." and order_status in (3,4,8)";
				$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
				// var_dump($todayProfit);exit;
				$Paymentsql = "select paytype,counts,pay_amount,payment_method_id from (select paytype,count(paytype) as counts,sum(pay_amount) as pay_amount,payment_method_id,order_id from nb_order_pay where dpid=".$companyId." and paytype !=11 and create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' group by paytype) y left join (select lid from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at >='2017-10-02 00:00:00' and create_at <= '2017-10-31 23:59:59') o on y.order_id=o.lid";
				// echo $Paymentsql;exit();
				$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryAll();
				// var_dump($Paymentmethod);exit();
				$Rechargesql = "select sum(reality_money) as reality_money,sum(give_money) as give_money from nb_member_recharge where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid=".$companyId;
				$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
				$recordsql = "select sum(recharge_money) as recharge_money,sum(cashback_num) as cashback_num from nb_recharge_record where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid=".$companyId;
				$records = Yii::app()->db->createCommand($recordsql)->queryAll();
				// var_dump($Takeouts);exit;
				$refundsql = "select sum(pay_amount) as pay_amount,count(*) as count from nb_order_pay where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and pay_amount<0 and dpid=".$companyId;
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
			}
    	}
		// var_dump($Takeouts);exit();
		$this->render('yysj',array(
			'todayProfit'=>$todayProfit,
			'Recharges'=>$Recharges,
			'records'=>$records,
			'refunds'=>$refunds,
			'Paymentmethod'=>$Paymentmethod,
			'date'=>$date,
			'type'=>$type
			));
	}
	public function actionSdbb(){
		$companyId = $this->companyId;
		$riq = array();
		// var_dump($date);exit();
		$date = Yii::app()->request->getParam('date');
		$type = $this->type();
		if(!empty($type)){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
	    		if(!empty($date)){
				$riqsql = "select hour(create_at) as hour, count(1) as count,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT(create_at,'%Y-%m-%d') = '".$date."' and dpid in(".$fens.") group by hour(create_at)";
				// echo $riqsql;exit();
				$riq = Yii::app()->db->createCommand($riqsql)->queryAll();
				// var_dump($riq);exit();
			}
		}else{
			if(!empty($date)){
				$riqsql = "select hour(create_at) as hour, count(1) as count,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT(create_at,'%Y-%m-%d') = '".$date."' and paytype!=11 and dpid=".$companyId." group by hour(create_at)";
				// echo $riqsql;exit();
				$riq = Yii::app()->db->createCommand($riqsql)->queryAll();
			// var_dump($riq);exit();
			}
		}
		$this->render('sdbb',array(
			'riq'=>$riq,
			'date'=>$date,
			'type'=>$type
			));
		// $this->render('list');
	}
	public function actionDpxs(){
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date');
		// var_dump($date);exit();
		$type = $this->type();
		if($type){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
    		$productsql = "select product_name,amount,original_price,price from (select order_id,`product_name`,sum(amount) AS amount,sum(original_price*amount) AS original_price,sum(`price`*amount) as price from nb_order_product where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid in (".$fens.") GROUP BY product_name order by amount desc) p left join (select lid,dpid from nb_order where create_at >='2017-10-01 00:00:00' and create_at <= '2017-10-30 23:59:59' and dpid in (".$fens.") and order_status in (3,4,8)) o on o.lid=p.order_id" ;
    		// echo $productsql;exit;
			$products = Yii::app()->db->createCommand($productsql)->queryAll();
		}else{
			$productsql = "select product_name,amount,original_price,price from (select order_id, `product_name`,sum(amount) as amount,sum(original_price*amount) AS original_price,sum(`price`*amount) as price from nb_order_product where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." GROUP BY product_name order by amount desc) p left join (select lid,dpid from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." and order_status in (3,4,8)) o on o.lid=p.order_id" ;
			// echo $productsql;exit;
			$products = Yii::app()->db->createCommand($productsql)->queryAll();

		}
		// var_dump($products);exit;
		$this->render('dpxs',array(
			'products'=>$products,
			'date'=>$date,
			'type'=>$type
			));
		// $this->render('list');
	}
	public function actionYclxh(){
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date');
		$type = $this->type();
		if($type){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
    		$sql = "select material_name,stock_num,unit_name from ((select material_id,dpid,sum(stock_num) as stock_num from nb_material_stock_log where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid in (".$fens.") GROUP BY material_id) l left join (select lid,material_name,mushs_code from nb_product_material GROUP BY material_name) c on l.material_id=c.lid) left join (select unit_name,muhs_code from nb_material_unit) t on c.mushs_code=t.muhs_code group by material_name";
		// echo $sql;exit;
			$materials = Yii::app()->db->createCommand($sql)->queryAll();
		}else{
			$sql = "select material_name,stock_num,unit_name from ((select material_id,dpid,sum(stock_num) as stock_num from nb_material_stock_log where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." GROUP BY material_id) l left join (select lid,material_name,mushs_code from nb_product_material GROUP BY material_name) c on l.material_id=c.lid) left join (select unit_name,muhs_code from nb_material_unit) t on c.mushs_code=t.muhs_code group by material_name";
		// echo $sql;exit;
			$materials = Yii::app()->db->createCommand($sql)->queryAll();
		}
		// var_dump($materials);exit;
		$this->render('yclxh',array(
			'materials'=>$materials,
			'date'=>$date,
			'type'=>$type
			));
		// $this->render('list');
	}
	public function actionTcxs(){
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date');
		// var_dump($date);exit;
		$orders =array();
		$type = $this->type();
		if($type){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
    		if(!empty($date)){
				$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid in (".$fens.") group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc) c";
				// echo $sql;exit;
				$orders = Yii::app()->db->createCommand($sql)->queryAll();
				// var_dump($orders);exit();
			}
    	}else{
    		if(!empty($date)){
				$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid=".$companyId." group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc) c";
				// echo $sql;exit;
				$orders = Yii::app()->db->createCommand($sql)->queryAll();
				// var_dump($orders);exit();
			}
    	}
		$this->render('tcxs',array(
			'orders'=>$orders,
			'date'=>$date,
			'type'=>$type
			));
	}
	public function actionZffs(){
		$companyId = $this->companyId;
		$orders = array();
		$zfs = array();
		$refunds =array();
		$date = Yii::app()->request->getParam('date');
		$type = $this->type();
		if(!empty($type)){
			$fenssql = "select company_id from nb_area_group_company where area_group_id=".$type['lid']." and delete_flag=0";
			$fens = Yii::app()->db->createCommand($fenssql)->queryColumn();
			$fens = json_encode($fens);
			$fens = str_replace('[', '', $fens);
    		$fens = str_replace(']', '', $fens);
    		$fens = str_replace('"', '', $fens);
    		if($date){
				$ordersql = "select count(*) as count,sum(reality_total) as reality_total from nb_order where order_status in (3,4,8) and dpid in (".$fens.") and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				// echo $ordersql;exit;
				$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
				// var_dump($orders);exit;
				$zfsql = "select y.paytype,sum(y.pay_amount) as pay_amount from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59') o where y.dpid in (".$fens.") and y.account_no=o.account_no and y.paytype !=11 and y.create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59' group by y.paytype";
				// echo $zfsql;exit();
				$zfs = Yii::app()->db->createCommand($zfsql)->queryAll();
				// var_dump($zfs);exit;
				$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid in (".$fens.") and pay_amount<0 and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
				// echo $zffssql;exit;
				// var_dump($zfs);exit();
			}
    	}else{
    		if($date){
				$ordersql = "select count(*) as count,sum(reality_total) as reality_total from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				// echo $ordersql;exit;
				$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
				// var_dump($orders);exit;
				$zfsql = "select y.paytype,sum(y.pay_amount) as pay_amount from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59') o where y.dpid=".$companyId." and y.account_no=o.account_no and y.paytype !=11 and y.create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59' group by y.paytype";
				// echo $zfsql;exit();
				$zfs = Yii::app()->db->createCommand($zfsql)->queryAll();
				// var_dump($zfs);exit;
				$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid=".$companyId." and pay_amount<0 and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
				// echo $zffssql;exit;
				// var_dump($zfs);exit();
			}
    	}
		$this->render('zffs',array(
			'date'=>$date,
			'orders'=>$orders,
			'zfs'=>$zfs,
			'refunds'=>$refunds,
			'type'=>$type
			));
	}
	public function actionOperator(){
		$companyId = $this->companyId;
		$dpsql = "select company_name from nb_company where dpid=".$companyId;
		$dp = Yii::app()->db->createCommand($dpsql)->queryRow();
		// var_dump($dp);exit();
		$glsql = "select lid,username,staff_no,role from nb_user where dpid=".$companyId." and delete_flag=0 and role>=11";
		$gl = Yii::app()->db->createCommand($glsql)->queryAll();
		// var_dump($gl);exit;
		$this->render('operator',array(
			'dp'=>$dp,
			'gl'=>$gl
			));
	}
	public function actionTjfwy(){
		$dpsql = "select dpid,company_name from nb_company where type in (0,1) and delete_flag=0";
		$dps = Yii::app()->db->createCommand($dpsql)->queryAll();
		$this->render('tjfwy',array(
			'dps'=>$dps
			));
	}
}