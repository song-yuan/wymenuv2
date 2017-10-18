<?php
class AppReportController extends Controller
{
	public $companyId;
	public $company;
	public $brandUser;
	public $brandUserAdmin = array();// 管理员关系的店铺
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
			$this->brandUserAdmin = WxBrandUserAdmin::get($this->brandUser['lid'],$this->brandUser['dpid']);
			if(empty($this->brandUserAdmin)){
				echo 'no access';
				return false;
			}
			if($this->company['type']=='0'){
				if(count($this->brandUserAdmin) > 1){
					$this->render('adminlist',array('admindpids'=>$this->brandUserAdmin));
				}else{
					$this->redirect(array('appReport/index','companyId'=>$this->brandUserAdmin[0]['dpid']));
				}
				exit;
			}
		}else{
			//pc 浏览
			$userId = 2182;
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			$userId = $this->brandUser['lid'];
			$userDpid = $this->brandUser['dpid'];
			Yii::app()->session['userId-'.$userDpid] = $userId;
			
			$this->brandUserAdmin = WxBrandUserAdmin::get($this->brandUser['lid'],$this->brandUser['dpid']);
			if(empty($this->brandUserAdmin)){
				echo 'no access';
				return false;
			}
		}
		return true;
	}
	public function actionIndex(){
		$companyId = $this->companyId;
		$ordersql ="select count(*) as counts,sum(number) as number,sum(reality_total) as reality_total from nb_order where to_days(create_at) = to_days(now()) and order_status in (3,4,8) and dpid=".$companyId;
		// echo $ordersql;exit;
		$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
		$Profitsql = "select sum(pay_amount) as pay_amount from nb_order_pay where to_days(create_at) = to_days(now()) and dpid=".$companyId;
		// echo $Profitsql;exit();
		$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
		$Membersql = "select * from nb_order_pay where to_days(create_at) = to_days(now()) and paytype=4 and dpid=".$companyId;
		$Member = Yii::app()->db->createCommand($Membersql)->queryAll();
		// var_dump($todayProfit);exit;
		$cardsql = "select * from nb_member_card where to_days(create_at) = to_days(now()) and dpid=".$companyId;
		$card = Yii::app()->db->createCommand($cardsql)->queryAll();
		// var_dump($Paymentmethod);exit;
		$Rechargesql = "select sum(reality_money) as reality_money,count(*) as count from nb_member_recharge where to_days(create_at) = to_days(now()) and dpid=".$companyId;
		$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		// var_dump($Recharge);exit();
		$monthsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order WHERE DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and dpid=".$companyId." and order_status in (3,4,8)";
		$months = Yii::app()->db->createCommand($monthsql)->queryAll();
		// $Paymentsql = "select sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' ) and dpid=".$companyId." and paytype!=11";
		$Paymentsql = "select sum(y.pay_amount) as pay_amount from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid=".$companyId." and DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' )) o where y.dpid=".$companyId." and y.account_no=o.account_no and y.paytype !=11 and DATE_FORMAT( y.create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' )";
		$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryColumn();
		$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid=".$companyId." and pay_amount<0 and DATE_FORMAT( create_at, '%Y%m' ) = DATE_FORMAT( CURDATE( ) , '%Y%m' )";
		$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		// var_dump($Paymentmethod);exit;
		$this->render('index',array(
				'todayProfit'=>$todayProfit,
				'Member'=>$Member,
				'card'=>$card,
				'orders'=>$orders,
				'Paymentmethod'=>$Paymentmethod,
				'Recharges'=>$Recharges,
				'months'=>$months,
				'refunds'=>$refunds
			));
	}
	public function actionYysj(){
		$companyId = $this->companyId;
		$Profitsql = "select count(*) as counts,sum(reality_total) as reality_total,sum(number) as number from nb_order where to_days(create_at) = to_days(now()) and dpid=".$companyId." and order_status in (3,4,8)";
		$todayProfit = Yii::app()->db->createCommand($Profitsql)->queryAll();
		$Paymentsql = "select y.paytype,count(y.paytype) as counts,sum(y.pay_amount) as pay_amount,y.payment_method_id from nb_order_pay y,(select * from nb_order where order_status in (3,4,8) and dpid=".$companyId." and to_days(create_at) = to_days(now())) o where y.dpid=".$companyId." and y.account_no=o.account_no and y.paytype !=11 and to_days(y.create_at) = to_days(now()) group by y.paytype";
		// echo $Paymentsql;exit();
		$Paymentmethod = Yii::app()->db->createCommand($Paymentsql)->queryAll();
		// var_dump($Paymentmethod);exit();
		$Rechargesql = "select sum(reality_money) as reality_money,sum(give_money) as give_money from nb_member_recharge where to_days(update_at) = to_days(now()) and dpid=".$companyId;
		$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		$recordsql = "select sum(recharge_money) as recharge_money,sum(cashback_num) as cashback_num from nb_recharge_record where to_days(update_at) = to_days(now()) and dpid=".$companyId;
		$records = Yii::app()->db->createCommand($recordsql)->queryAll();
		// var_dump($Takeouts);exit;
		$refundsql = "select sum(pay_amount) as pay_amount,count(*) as count from nb_order_pay where to_days(create_at) = to_days(now()) and pay_amount<0 and dpid=".$companyId;
		$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		// var_dump($Takeouts);exit();
		$this->render('yysj',array(
			'todayProfit'=>$todayProfit,
			'Recharges'=>$Recharges,
			'records'=>$records,
			'refunds'=>$refunds,
			'Paymentmethod'=>$Paymentmethod
			));
	}
	public function actionSdbb(){
		$companyId = $this->companyId;
		$riq = array();
		$date = Yii::app()->request->getParam('date');
		if(Yii::app()->request->getParam('date')){
			$riqsql = "select hour(create_at) as hour, count(1) as count,sum(pay_amount) as pay_amount from nb_order_pay where DATE_FORMAT(create_at,'%Y-%m-%d') = '".$date."' and dpid=".$companyId." group by hour(create_at)";
			// echo $riqsql;exit();
			$riq = Yii::app()->db->createCommand($riqsql)->queryAll();
			// var_dump($riq);exit();
		}
		
		$this->render('sdbb',array(
			'riq'=>$riq,
			'date'=>$date
			));
		// $this->render('list');
	}
	public function actionDpxs(){
		$companyId = $this->companyId;
		$productsql = "select p.`product_name`,count(1) AS counts,sum(original_price) AS original_price,sum(`price`) as price from nb_order_product p,(select lid,dpid from nb_order where to_days(create_at) = to_days(now()) and dpid=".$companyId." and order_status in (3,4,8)) o where o.lid=p.order_id and p.dpid=o.dpid GROUP BY product_name order by counts desc" ;
		$products = Yii::app()->db->createCommand($productsql)->queryAll();
		// var_dump($products);exit;
		$this->render('dpxs',array(
			'products'=>$products
			));
		// $this->render('list');
	}
	public function actionYclxh(){
		$companyId = $this->companyId;
		$sql = "select c.material_name,l.stock_num from nb_product_material c,(select material_id,dpid,sum(stock_num) as stock_num from nb_material_stock_log where to_days(create_at) = to_days(now()) and dpid=".$companyId." GROUP BY material_id) l where c.lid=l.material_id and c.dpid=l.dpid GROUP BY material_name";
		// echo $sql;exit;
		$materials = Yii::app()->db->createCommand($sql)->queryAll();
		// var_dump($materials);exit;
		$this->render('yclxh',array(
			'materials'=>$materials
			));
		// $this->render('list');
	}
	public function actionTcxs(){
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date');
		// var_dump($date);exit;
		$orders =array();
		if(!empty($date)){
			$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid=".$companyId." group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc)c";
			// echo $sql;exit;
			$orders = Yii::app()->db->createCommand($sql)->queryAll();
			// var_dump($orders);exit();
		}
		$this->render('tcxs',array(
			'orders'=>$orders,
			'date'=>$date
			));
	}
	public function actionZffs(){
		$companyId = $this->companyId;
		$orders = array();
		$zfs = array();
		$refunds =array();
		$date = Yii::app()->request->getParam('date');
		// var_dump($date);exit;
		if(Yii::app()->request->getParam('date')){
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
		$this->render('zffs',array(
			'date'=>$date,
			'orders'=>$orders,
			'zfs'=>$zfs,
			'refunds'=>$refunds
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