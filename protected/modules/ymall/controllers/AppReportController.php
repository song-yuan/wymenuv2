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
			$userId = 2204;
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
	public function actionIndex(){
		$companyId = $this->companyId;
		$type = $this->type();
		if(empty($type)){
			if($this->brandUser['dpid']==$companyId){
			$fensql = "select lid,group_name,area_group_id,y.dpid,company_name,logo,address from (select dpid,admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) a inner join (select lid,group_name,dpid from nb_area_group where type=3 and delete_flag=0) g on a.dpid=g.dpid inner join (select area_group_id,dpid,company_id from nb_area_group_company where delete_flag=0) c on c.area_group_id=g.lid and a.admin_dpid=c.company_id inner join (select dpid,company_name,logo,address from nb_company where type=1 and delete_flag=0) y on a.admin_dpid=y.dpid group by lid,group_name,y.dpid";
				$fens = Yii::app()->db->createCommand($fensql)->queryAll();
				if(count($fens)>1){
					//重新组成的数组
						$array = array();

						foreach ($fens as  $key=>$value) {
							if(!isset($array[$value['group_name']])){
								$array[$value['group_name']] = array();
							}
							array_push($array[$value['group_name']], $value);

						}
						if(!empty($fens)){
								$this->render('adminlist',array(
									'array'=>$array,
									'companyId'=>$companyId
								));
							exit;
						}
					}else{
						foreach ($fens as $fen) {
							$dpid = $fen['dpid'];
						}
						$this->redirect(array('appReport/index','companyId'=>$dpid));
					}
				}
		}
		 if(!empty($type)){
		 	$ordersql ="select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total from nb_order where to_days(create_at) = to_days(now()) and order_status in (3,4,8) and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)) o inner join (select dpid,sum(pay_amount) as pay_amount from nb_order_pay where to_days(create_at) = to_days(now()) and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and paytype!=11) y on o.dpid=y.dpid";
		 	$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
		 	$Membersql = "select distinct count(paytype_id) as paytype_id from nb_order_pay where to_days(create_at) = to_days(now()) and paytype=4 and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
		 	$Members = Yii::app()->db->createCommand($Membersql)->queryAll();
		 	$cardsql = "select count(rfid) as rfid from nb_member_card where to_days(create_at) = to_days(now()) and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
		 	$cards = Yii::app()->db->createCommand($cardsql)->queryAll();
		 	$Rechargesql = "select sum(reality_money) as reality_money,count(*) as count from nb_member_recharge where to_days(create_at) = to_days(now()) and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
		 	$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		 	$monthsql = "select counts,number,reality_total,pay_amount from (select lid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total from nb_order where DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and order_status in (3,4,8) and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)) o left join (select order_id,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and paytype!=11) y on o.lid=y.order_id";
		 	$months = Yii::app()->db->createCommand($monthsql)->queryAll();
		 	$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and pay_amount<0 and DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' )";
		 	$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		 }else{
		 	$ordersql ="select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total from nb_order where to_days(create_at) = to_days(now()) and order_status in (3,4,8) and dpid=".$companyId.") o inner join (select dpid,sum(pay_amount) as pay_amount from nb_order_pay where to_days(create_at) = to_days(now()) and dpid=".$companyId." and paytype!=11) y on o.dpid=y.dpid";
		 	$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
		 	$monthsql = "select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total from nb_order where DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and order_status in (3,4,8) and dpid=".$companyId.") o left join (select dpid,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and dpid=".$companyId." and paytype!=11) y on o.dpid=y.dpid";
		 	$months = Yii::app()->db->createCommand($monthsql)->queryAll();
		 	$Membersql = "select distinct count(paytype_id) as paytype_id from nb_order_pay where to_days(create_at) = to_days(now()) and paytype=4 and dpid=".$companyId;
		 	$Members = Yii::app()->db->createCommand($Membersql)->queryAll();
		 	$cardsql = "select count(rfid) as rfid from nb_member_card where to_days(create_at) = to_days(now()) and dpid=".$companyId;
		 	$cards = Yii::app()->db->createCommand($cardsql)->queryAll();
		 	$Rechargesql = "select sum(reality_money) as reality_money,count(*) as count from nb_member_recharge where to_days(create_at) = to_days(now()) and dpid=".$companyId;
		 	$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		 	$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid=".$companyId." and pay_amount<0 and DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' )";
		 	$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		 }
		 $this->render('index',array(
		 		'orders'=>$orders,
		 		'months'=>$months,
		 		'Members'=>$Members,
		 		'cards'=>$cards,
		 		'Recharges'=>$Recharges,
		 		'refunds'=>$refunds,
		 		'type'=>$type
		 ));
	}
	public function actionYysj(){
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$type = $this->type();
		$date = Yii::app()->request->getParam('date',$defaultData);
		$todayProfit = array();
		$Recharges = array();
		$records = array();
		$refunds = array();
		$Paymentmethod = array();
		if(!empty($type)){
	    		if(!empty($date)){
				$Profitsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and order_status in (3,4,8)";
				$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
				
				$Paymentsql = "select y.paytype,count(y.paytype) as counts,sum(y.pay_amount) as pay_amount,y.payment_method_id from nb_order_pay y,(select lid from nb_order where order_status in (3,4,8) and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59') o where y.dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and y.order_id=o.lid and y.paytype !=11 and y.create_at >='".$date['start']." 00:00:00' and y.create_at <= '".$date['End']." 23:59:59' group by y.paytype";
				$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryAll();
				
				$Rechargesql = "select sum(reality_money) as reality_money,sum(give_money) as give_money from nb_member_recharge where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
				$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
				
				$recordsql = "select sum(recharge_money) as recharge_money,sum(cashback_num) as cashback_num from nb_recharge_record where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
				$records = Yii::app()->db->createCommand($recordsql)->queryAll();
				
				$refundsql = "select sum(pay_amount) as pay_amount,count(*) as count from nb_order_pay where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and pay_amount<0 and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0)";
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
			}
    	}else{
	    		if(!empty($date)){
				$Profitsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." and order_status in (3,4,8)";
				$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
				
				$Paymentsql = "select y.paytype,count(y.paytype) as counts,sum(y.pay_amount) as pay_amount,y.payment_method_id from nb_order_pay y,(select lid from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59') o where y.dpid=".$companyId." and y.order_id=o.lid and y.paytype !=11 and y.create_at >='".$date['start']." 00:00:00' and y.create_at <= '".$date['End']." 23:59:59' group by y.paytype";
				$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryAll();
				
				$Rechargesql = "select sum(reality_money) as reality_money,sum(give_money) as give_money from nb_member_recharge where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid=".$companyId;
				$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
				
				$recordsql = "select sum(recharge_money) as recharge_money,sum(cashback_num) as cashback_num from nb_recharge_record where update_at >='".$date['start']." 00:00:00' and update_at <= '".$date['End']." 23:59:59' and dpid=".$companyId;
				$records = Yii::app()->db->createCommand($recordsql)->queryAll();
				
				$refundsql = "select sum(pay_amount) as pay_amount,count(*) as count from nb_order_pay where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and pay_amount<0 and dpid=".$companyId;
				$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
			}
    	}
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
		$now = time();
		$defaultData = date('Y-m-d',$now);
		$companyId = $this->companyId;
		$riq = array();
		$date = Yii::app()->request->getParam('date',$defaultData);
		$type = $this->type();
		if(!empty($type)){
	    		if(!empty($date)){
				$riqsql = "select hour(create_at) as hour, count(1) as count,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT(create_at,'%Y-%m-%d') = '".$date."' and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) group by hour(create_at)";
				$riq = Yii::app()->db->createCommand($riqsql)->queryAll();
			}
		}else{
			if(!empty($date)){
				$riqsql = "select hour(create_at) as hour, count(1) as count,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT(create_at,'%Y-%m-%d') = '".$date."' and paytype!=11 and dpid=".$companyId." group by hour(create_at)";
				$riq = Yii::app()->db->createCommand($riqsql)->queryAll();
			}
		}
		$this->render('sdbb',array(
			'riq'=>$riq,
			'date'=>$date,
			'type'=>$type
			));
	}
	public function actionDpxs(){
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date',$defaultData);
		$type = $this->type();
		if($type){
    		$productsql = "select product_name,amount,original_price,price from (select order_id,`product_name`,sum(amount) AS amount,sum(original_price*amount) AS original_price,sum(`price`*amount) as price from nb_order_product where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) GROUP BY product_name order by amount desc) p left join (select lid,dpid from nb_order where create_at >='2017-10-01 00:00:00' and create_at <= '2017-10-30 23:59:59' and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and order_status in (3,4,8)) o on o.lid=p.order_id" ;
			$products = Yii::app()->db->createCommand($productsql)->queryAll();
		}else{
			$productsql = "select product_name,amount,original_price,price from (select order_id, `product_name`,sum(amount) as amount,sum(original_price*amount) AS original_price,sum(`price`*amount) as price from nb_order_product where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." GROUP BY product_name order by amount desc) p left join (select lid,dpid from nb_order where create_at >='".$date['start']." 00:00:00' and create_at <= '".$date['End']." 23:59:59' and dpid=".$companyId." and order_status in (3,4,8)) o on o.lid=p.order_id" ;
			$products = Yii::app()->db->createCommand($productsql)->queryAll();

		}
		$this->render('dpxs',array(
			'products'=>$products,
			'date'=>$date,
			'type'=>$type
			));
		// $this->render('list');
	}
	public function actionYclxh(){
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date',$defaultData);
		$type = $this->type();
		if($type){
    		$sql = 'select t.material_id,sum(t.stock_num) as material_num,t1.material_name,t2.unit_name from nb_material_stock_log t left join nb_product_material t1 on t.material_id=t1.lid and t.dpid=t1.dpid left join nb_material_unit t2 on t1.sales_unit_id=t2.lid and t1.dpid=t2.dpid where t.create_at >= "'.$date['start'].' 00:00:00" and "'.$date['End'].' 23:59:59" >= t.create_at and t.type=1 and t.material_id in(select k.lid from nb_product_material k where k.delete_flag = 0 and k.dpid in(select admin_dpid as dpid from nb_brand_user_admin where brand_user_id='.$this->brandUser['lid'].' and delete_flag=0)) group by t.material_id';
    		$materials = Yii::app()->db->createCommand($sql)->queryAll();
		}else{
			$sql = 'select t.material_id,sum(t.stock_num) as stock_num,t1.material_name,t2.unit_name from nb_material_stock_log t left join nb_product_material t1 on t.material_id=t1.lid and t.dpid=t1.dpid left join nb_material_unit t2 on t1.sales_unit_id=t2.lid and t1.dpid=t2.dpid where t.dpid='.$companyId.' and t.create_at >= "'.$date['start'].' 00:00:00" and "'.$date['End'].' 23:59:59" >= t.create_at and t.type=1 and t.material_id in(select k.lid from nb_product_material k where k.delete_flag = 0 and k.dpid = '.$companyId.') group by t.material_id';
			$materials = Yii::app()->db->createCommand($sql)->queryAll();
		}
		$this->render('yclxh',array(
			'materials'=>$materials,
			'date'=>$date,
			'type'=>$type
			));
		// $this->render('list');
	}
	public function actionTcxs(){
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date',$defaultData);
		// var_dump($date);exit;
		$orders =array();
		$type = $this->type();
		if($type){
    		if(!empty($date)){
				$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc) c";
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
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$orders = array();
		$zfs = array();
		$refunds =array();
		$date = Yii::app()->request->getParam('date',$defaultData);
		$type = $this->type();
		if(!empty($type)){
    		if($date){
				$ordersql = "select count(*) as count,sum(reality_total) as reality_total from nb_order where order_status in (3,4,8) and dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
				
				$zfsql = "select y.paytype,sum(y.pay_amount) as pay_amount,count(y.paytype) as pay_count from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid in(select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59') o where y.dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and y.account_no=o.account_no and y.paytype !=11 and y.create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59' group by y.paytype";
				$zfs = Yii::app()->db->createCommand($zfsql)->queryAll();
				
				$refundsql = "select sum(pay_amount) as pay_amount,count(lid) as pay_count from nb_order_pay where dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) and pay_amount<0 and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$refunds = Yii::app()->db->createCommand($refundsql)->queryRow();
			}
    	}else{
    		if($date){
				$ordersql = "select count(*) as count,sum(reality_total) as reality_total from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
				
				$zfsql = "select y.paytype,sum(y.pay_amount) as pay_amount,count(y.paytype) as pay_count from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid=".$companyId." and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59') o where y.dpid=".$companyId." and y.account_no=o.account_no and y.paytype !=11 and y.create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59' group by y.paytype";
				$zfs = Yii::app()->db->createCommand($zfsql)->queryAll();
				
				$refundsql = "select sum(pay_amount) as pay_amount,count(lid) as pay_count from nb_order_pay where dpid=".$companyId." and pay_amount<0 and create_at BETWEEN '".$date['start']." 00:00:00' AND '".$date['End']." 23:59:59'";
				$refunds = Yii::app()->db->createCommand($refundsql)->queryRow();
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
	public function actionCzygl(){
		$companyId = $this->companyId;
		// var_dump($dp);exit();
		$glsql = "select lid,username,staff_no,mobile,role from nb_user where dpid=".$companyId." and delete_flag=0 and role>=11";
		$gls = Yii::app()->db->createCommand($glsql)->queryAll();
		// var_dump($gls);exit;
		$this->render('czygl',array(
			'gls'=>$gls
			));
	}
	public function actionTjgly(){
		$companyId = $this->companyId;
		$sql = "select * from nb_user where dpid=".$companyId." and role in (1,5,11)";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		// var_dump($model);exit();
		$role = $model['role'];
		// var_dump($role);exit();
		$roles = $this->Jurisdiction($role);
		// var_dump($roles);exit;
		// var_dump($modle);exit();
		// echo $modle['role'];exit;
		$this->render('tjgly',array(
			'model'=>$model,
			'roles'=>$roles
			));
	}
	public function Jurisdiction($role){
		// var_dump($role);exit();
		switch($role){
			case 1: $roles = array(
			'1' => yii::t('app','超级管理员'),
			'3' => yii::t('app','超级副管理员'),
			'5' => yii::t('app','总部管理员'),
			'7' => yii::t('app','总部副管理员'),
                        '8' => yii::t('app','营销员'),
			'9' => yii::t('app','区域管理员'),
			'11' => yii::t('app','店长'),
			'13' => yii::t('app','副店长'),
			'15' => yii::t('app','组长'),
			'17' => yii::t('app','收银员'),
			'19' => yii::t('app','服务员'),
		);break;
			case 3: $roles = array(
			'5' => yii::t('app','总部管理员'),
		);break;
			case 5: $roles = array(
			//'7' => yii::t('app','总部副管理员'),
			//'9' => yii::t('app','区域管理员'),
			'11' => yii::t('app','店长'),
			'13' => yii::t('app','副店长'),
			//'15' => yii::t('app','组长'),
			'17' => yii::t('app','收银员'),
			'19' => yii::t('app','服务员'),
		);break;
			case 7: $roles = array(
			//'9' => yii::t('app','区域管理员'),
			'11' => yii::t('app','店长'),
			'13' => yii::t('app','副店长'),
			//'15' => yii::t('app','组长'),
			'17' => yii::t('app','收银员'),
			'19' => yii::t('app','服务员'),
		);break;
			case 9: $roles = array(
				'11' => yii::t('app','店长'),
				'13' => yii::t('app','副店长'),
				'15' => yii::t('app','组长'),
				'17' => yii::t('app','收银员'),
				'19' => yii::t('app','服务员'),
		);break;
			case 11: $roles = array(
				'13' => yii::t('app','副店长'),
				//'15' => yii::t('app','组长'),
				'17' => yii::t('app','收银员'),
				'19' => yii::t('app','服务员'),
		);break;
			case 13: $roles = array(
				//'15' => yii::t('app','组长'),
				'17' => yii::t('app','收银员'),
				'19' => yii::t('app','服务员'),
		);break;
			case 15: $roles = array(
				'17' => yii::t('app','收银员'),
				'19' => yii::t('app','服务员'),
		);break;
			default: $roles = '';break;
		}
		return $roles;
	}
}