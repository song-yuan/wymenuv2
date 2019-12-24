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
		$comdpid = $this->company['comp_dpid'];
		$userId = Yii::app()->session['userId_'.(int)$comdpid];
		//如果微信浏览器
		if(Helper::isMicroMessenger()){
			if(empty($userId)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'pcompanyId'=>$comdpid,'url'=>urlencode($url)));
				exit;
			}
			$this->brandUser = WxBrandUser::get($userId, $this->companyId);
			if(empty($this->brandUser)){
				$url = Yii::app()->request->url;
				$this->redirect(array('/weixin/redirect','companyId'=>$this->companyId,'pcompanyId'=>$comdpid,'url'=>urlencode($url)));
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
		$beginMonth = date('Y-m-01 00:00:00',time());
		$endMonth = date('Y-m-31 23:59:59',time());
		$beginTime = date('Y-m-d 00:00:00',time());
		$endTime = date('Y-m-d 23:59:59',time());
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
		 	$admindpid = 0;
		 	$sql = "select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0";
		 	$adminDpids = Yii::app()->db->createCommand($sql)->queryColumn();
		 	if($adminDpids){
		 		$admindpid = join(',', $adminDpids);
		 	}
		 	
		 	$ordersql ="select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total,sum(should_total) as pay_amount from nb_order where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and order_status in (3,4,8) and dpid in (".$admindpid."))o ";
		 	$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
		 	
		 	$Membersql = "select distinct count(paytype_id) as paytype_id from nb_order_pay where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and paytype=4 and dpid in (".$admindpid.")";
		 	$Members = Yii::app()->db->createCommand($Membersql)->queryAll();
		 	
		 	$cuponsql = "select sum(pay_amount) as pay_amount from nb_order_pay where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and paytype=9 and dpid=".$companyId;
		 	$cupons = Yii::app()->db->createCommand($cuponsql)->queryAll();
		 	
		 	$cardsql = "select count(rfid) as rfid from nb_member_card where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and dpid in(".$admindpid.")";
		 	$cards = Yii::app()->db->createCommand($cardsql)->queryAll();
		 	
		 	$Rechargesql = "select sum(reality_money) as reality_money,count(*) as count from nb_member_recharge where create_at >= '".$beginMonth."' and create_at <= '".$endMonth."' and dpid in(".$admindpid.")";
		 	$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		 	
		 	$monthsql = "select counts,number,reality_total,pay_amount from (select lid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total,sum(should_total) as pay_amount from nb_order where create_at >= '".$beginMonth."' and create_at <= '".$endMonth."' and order_status in (3,4,8) and dpid in (".$admindpid."))o";
		 	$months = Yii::app()->db->createCommand($monthsql)->queryAll();
		 	
		 	$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid in(".$admindpid.") and pay_amount<0 and create_at >= '".$beginMonth."' and create_at <= '".$endMonth."'";
		 	$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		 }else{
		 	$ordersql ="select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total,sum(should_total) as pay_amount from nb_order where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and order_status in (3,4,8) and dpid=".$companyId.")o";
		 	$orders = Yii::app()->db->createCommand($ordersql)->queryAll();
		 	
		 	$monthsql = "select counts,number,reality_total,pay_amount from (select dpid,count(*) as counts,sum(number) as number,sum(reality_total) as reality_total,sum(should_total) as pay_amount from nb_order where create_at >= '".$beginMonth."' and create_at <= '".$endMonth."' and order_status in (3,4,8) and dpid=".$companyId.")o";
		 	$months = Yii::app()->db->createCommand($monthsql)->queryAll();
		 	
		 	$Membersql = "select distinct count(paytype_id) as paytype_id from nb_order_pay where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and paytype=4 and dpid=".$companyId;
		 	$Members = Yii::app()->db->createCommand($Membersql)->queryAll();
		 	
		 	$cuponsql = "select sum(op.pay_amount) as pay_amount from nb_order_pay op,nb_order o where op.order_id=o.lid and op.dpid=o.dpid and op.dpid=".$companyId." and op.create_at >= '".$beginTime."' and op.create_at <= '".$endTime."' and op.paytype=9 and o.order_status in(3,4,8)";
		 	$cupons = Yii::app()->db->createCommand($cuponsql)->queryAll();
		 	
		 	$cardsql = "select count(rfid) as rfid from nb_member_card where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and dpid=".$companyId;
		 	$cards = Yii::app()->db->createCommand($cardsql)->queryAll();
		 	
		 	$Rechargesql = "select sum(reality_money) as reality_money,count(*) as count from nb_member_recharge where create_at >= '".$beginTime."' and create_at <= '".$endTime."' and dpid=".$companyId;
		 	$Recharges = Yii::app()->db->createCommand($Rechargesql)->queryAll();
		 	
		 	$refundsql = "select sum(pay_amount) as pay_amount from nb_order_pay where dpid=".$companyId." and pay_amount<0 and create_at >= '".$beginMonth."' and create_at <= '".$endMonth."'";
		 	$refunds = Yii::app()->db->createCommand($refundsql)->queryAll();
		 }
		 $this->render('index',array(
		 		'orders'=>$orders,
		 		'months'=>$months,
		 		'Members'=>$Members,
		 		'cupons'=>$cupons,
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
	}
	public function actionTcxs(){
		$now = time();
		$defaultData = array('start'=>date('Y-m-d',$now),'End'=>date('Y-m-d',$now));
		$companyId = $this->companyId;
		$date = Yii::app()->request->getParam('date',$defaultData);
		$orders =array();
		$type = $this->type();
		if($type){
    		if(!empty($date)){
				$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid in (select admin_dpid from nb_brand_user_admin where brand_user_id=".$this->brandUser['lid']." and delete_flag=0) group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc) c";
				$orders = Yii::app()->db->createCommand($sql)->queryAll();
			}
    	}else{
    		if(!empty($date)){
				$sql ="select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,t.set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >='".$date['start']." 00:00:00' and t.create_at <= '".$date['End']." 23:59:59' and t.dpid=".$companyId." group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc) c";
				$orders = Yii::app()->db->createCommand($sql)->queryAll();
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
		$glsql = "select lid,username,staff_no,mobile,role from nb_user where dpid=".$companyId." and delete_flag=0 and role>=11";
		$gls = Yii::app()->db->createCommand($glsql)->queryAll();
		$this->render('czygl',array(
			'gls'=>$gls
		));
	}
	public function actionTjgly(){
		$companyId = $this->companyId;
		$sql = "select * from nb_user where dpid=".$companyId." and role in (1,5,11)";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		$role = $model['role'];
		$roles = $this->Jurisdiction($role);
		$this->render('tjgly',array(
			'model'=>$model,
			'roles'=>$roles
		));
	}
	//厂商分类
	public function actionCsfl(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId.' and t.delete_flag=0';	
		$criteria->order = ' t.lid desc ';	
		$models = ManufacturerClassification::model()->findAll($criteria);
		$this->render('csfl',array(
				'models'=>$models
		
		));
	}
	public function actionCreateCsfl(){
		$id = Yii::app()->request->getParam('id',0);
		if($id){
			$criteria = new CDbCriteria;
			$criteria->condition =  'lid='.$id.' and dpid='.$this->companyId.' and delete_flag=0';
			$model = ManufacturerClassification::model()->find($criteria);
		}else{
			$model = new ManufacturerClassification();
		}
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('ManufacturerClassification');
			if(!$id){
				$se=new Sequence("manufacturer_classification");
				$model->lid = $se->nextval();
				$model->dpid = $this->companyId;
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
			}
			$model->attributes = $data;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('appReport/csfl' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('ccsfl',array(
				'model'=>$model
	
		));
	}
	public function actionDeleteCsfl(){
		$id = Yii::app()->request->getParam('id',0);
		$sql = 'update nb_manufacturer_classification set delete_flag=1 where lid='.$id.' and dpid='.$this->companyId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		if($result){
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}else{
			Yii::app()->user->setFlash('success',yii::t('app','删除失败！'));
		}
		$this->redirect(array('appReport/csfl' , 'companyId' => $this->companyId ));
	}
	//厂商信息
	public function actionCsxx(){
		$criteria = new CDbCriteria;
		$criteria->condition =  't.dpid='.$this->companyId.' and t.delete_flag=0';
		$criteria->order = ' t.lid desc ';
		$models = ManufacturerInformation::model()->findAll($criteria);
		$this->render('csxx',array(
				'models'=>$models
	
		));
	}
	public function actionCreateCsxx(){
		$id = Yii::app()->request->getParam('id',0);
		$classifications = ManufacturerClassification::model()->findAll('dpid='.$this->companyId.' and delete_flag=0');
		if($id){
			$criteria = new CDbCriteria;
			$criteria->condition =  'lid='.$id.' and dpid='.$this->companyId.' and delete_flag=0';
			$model = ManufacturerInformation::model()->find($criteria);
		}else{
			$model = new ManufacturerInformation();
		}
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('ManufacturerClassification');
			if(!$id){
				$se=new Sequence("manufacturer_information");
				$model->lid = $se->nextval();
				$model->dpid = $this->companyId;
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
			}
			$model->attributes = $data;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('appReport/csxx' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('ccsxx',array(
				'model'=>$model,
				'classifications'=>$classifications
		));
	}
	public function actionDeleteCsxx(){
		$id = Yii::app()->request->getParam('id',0);
		$sql = 'update nb_manufacturer_information set delete_flag=1 where lid='.$id.' and dpid='.$this->companyId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		if($result){
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}else{
			Yii::app()->user->setFlash('success',yii::t('app','删除失败！'));
		}
		$this->redirect(array('appReport/csxx' , 'companyId' => $this->companyId ));
	}
	//安全库存
	public function actionAqkc(){
		$companyId = $this->companyId;
		$sql = "select * from nb_stock_setting where dpid=".$companyId." and delete_flag=0";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('m');
			if($model){
				$sql = 'update nb_stock_setting set dsales_day='.$data['dsales_day'].',dsafe_min_day='.$data['dsafe_min_day'].',dsafe_max_day='.$data['dsafe_max_day'].' where lid='.$model['lid'].' and dpid='.$model['dpid'];
			}else{
				$time = date('Y-m-d H:i:s',time());
				$se = new Sequence("stock_setting");
            	$lid = $se->nextval();
				$sql = 'insert into nb_stock_setting(lid,dpid,create_at,update_at,dsales_day,dsafe_min_day,dsafe_max_day) values ('.$lid.','.$companyId.','.$time.','.$time.','.$data['dsales_day'].','.$data['dsafe_min_day'].','.$data['dsafe_max_day'].')';
			}
			$result = Yii::app()->db->createCommand($sql)->execute();
			if($result){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
			}else{
				Yii::app()->user->setFlash('success',yii::t('app','修改失败！'));
			}
			$this->redirect(array('appReport/aqkc','companyId'=>$companyId));
		}
		$this->render('aqkc',array(
				'model'=>$model,
		));
	}
	// 实时库存
	public function actionSskc(){
		$companyId = $this->companyId;
		$categoryId = Yii::app()->request->getParam('cid',0);
		$categorys = array();
		$sql = 'select * from nb_material_category where dpid='.$companyId.' and delete_flag=0';
		$cs = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cs as $c){
			$pid = 'lid-'.$c['pid'];
			if(!isset($categorys[$pid])){
				$categorys[$pid] = array();
			}
			array_push($categorys[$pid], $c);
		}
		$sql = 'select t.*,t1.category_name from nb_product_material t,nb_material_category t1 where t.dpid=t1.dpid and t.category_id=t1.lid and t.dpid='.$companyId;
		if($categoryId){
			$sql .= ' and t.category_id = '.$categoryId;
		}
		$sql .= ' and t.delete_flag=0';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		$this->render('sskc',array(
				'categoryId'=>$categoryId,
				'categorys'=>$categorys,
				'models'=>$models,
		));
	}
	//入库订单
	public function actionRkdd(){
		$companyId = $this->companyId;
		$time = date('Y-m-d',time());
		$defaultData = array('start'=>$time,'End'=>$time);
		$date = Yii::app()->request->getParam('date',$defaultData);
		$criteria = new CDbCriteria;
		$criteria->addCondition('dpid=:dpid and delete_flag=0');
		$criteria->order = ' lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		$pages = new CPagination(StorageOrder::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = StorageOrder::model()->findAll($criteria);
		$this->render('rkdd',array(
				'models'=>$models,
				'date'=>$date,
				'pages'=>$pages,
		));
	}
	public function actionRkdddetail(){
		$companyId = $this->companyId;
		$id = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->addCondition('lid='.$id.' and dpid='.$companyId.' and delete_flag=0');
		$model = StorageOrder::model()->find($criteria);
		$details = StorageOrderDetail::model()->findAll('storage_id='.$id.' and dpid='.$companyId.' and delete_flag=0');
		$this->render('rkdddetail',array(
				'model'=>$model,
				'details'=>$details
		));
	}
	public function actionCreateRkdd(){
		$companyId = $this->companyId;
		$id = Yii::app()->request->getParam('id',0);
		
		$model = StorageOrder::model()->find('lid='.$id.' and dpid='.$companyId.' and delete_flag=0');
		if(Yii::app()->request->isPostRequest){
			$storage = Yii::app()->request->getPost('StorageOrder');
			$materials = Yii::app()->request->getPost('material');
			$transaction = Yii::app()->db->beginTransaction();
			try{
				if(!$model){
					$model = new StorageOrder();
					$se = new Sequence("storage_order");
					$model->lid = $se->nextval();
					$model->dpid = $companyId;
					$model->create_at = date('Y-m-d H:i:s',time());
					$model->update_at = date('Y-m-d H:i:s',time());
					$model->storage_account_no = date('YmdHis',time()).substr($model->lid,-4);
					$model->purchase_account_no = '';
					$model->status = 0;
				}
				$model->manufacturer_id = $storage['manufacturer_id'];
				$model->organization_id = $companyId;
				$model->admin_id = $storage['admin_id'];
				$model->remark = $storage['remark'];
				$model->save();
				$id = $model->lid;
				$sql = 'update nb_storage_order_detail set delete_flag=1 where storage_id='.$id.' and dpid='.$companyId.' and delete_flag=0';
				Yii::app()->db->createCommand($sql)->execute();
				foreach ($materials as $key=>$material){
					$mphsCode = $material['mphs_code'];
					$stock = $material['stock'];
					$price = $material['price'];
					if($stock > 0){
						$stde = new StorageOrderDetail();
						$se = new Sequence("storage_order_detail");
						$stde->lid = $se->nextval();
						$stde->dpid = $companyId;
						$stde->create_at = date('Y-m-d H:i:s',time());
						$stde->update_at = date('Y-m-d H:i:s',time());
						$stde->storage_id = $id;
						$stde->material_id = $key;
						$stde->mphs_code = $mphsCode;
						$stde->stock = $stock;
						$stde->price = $price;
						$stde->save();
					}
				}
				$transaction->commit();
				$status = true;
			}catch (Exception $e) {
				$transaction->rollback(); //如果操作失败, 数据回滚
				$status = false;
			} 
			if($status){
				Yii::app()->user->setFlash('success',yii::t('app','保存成功！'));
				$this->redirect(array('/ymall/appReport/rkdddetail','companyId'=>$companyId,'id'=>$id));
			}else{
				Yii::app()->user->setFlash('success',yii::t('app','保存失败！'));
				$this->redirect(array('/ymall/appReport/createRkdd','companyId'=>$companyId,'id'=>$id));
			}
		}
		$details = array();
		$dets = StorageOrderDetail::model()->findAll('storage_id='.$id.' and dpid='.$companyId.' and delete_flag=0');
		
		$mfrs = ManufacturerInformation::model()->findAll('dpid='.$companyId.' and delete_flag=0');
		$users = User::model()->findAll('dpid='.$companyId.' and status=1 and delete_flag=0') ;
		
		foreach ($dets as $det){
			$mid = $det['material_id'];
			if(!isset($details[$mid])){
				$details[$mid] = array();
			}
			array_push($details[$mid], $det);
		}
		$categorys = $this->getMaterialCategory($companyId);
		$materials = $this->getMaterials($companyId);
		
		$this->render('createRkdd',array(
				'model'=>$model,
				'details'=>$details,
				'categorys'=>$categorys,
				'materials'=>$materials,
				'mfrs'=>$mfrs,
				'users'=>$users
		));
	}
	//库存盘点
	public function actionKcpd(){
		$db = Yii::app()->db;
		$cate ='>0';
		$sql = 'select ms.stock_all,mu.unit_name,mu.lid as mu_lid,ms.lid as ms_lid,ms.unit_name as sales_name,k.category_name,inv.inventory_stock,inv.inventory_sales,inv.ratio,inv.lid as invtid,t.* from nb_product_material t '.
				'left join nb_material_category k on(t.category_id = k.lid and t.dpid = k.dpid)'.
				'left join nb_material_unit mu on(t.stock_unit_id = mu.lid and t.dpid = mu.dpid) '.
				'left join nb_material_unit ms on(t.sales_unit_id = ms.lid and t.dpid = ms.dpid) '.
				'left join (select sum(stock) as stock_all,material_id from nb_product_material_stock where dpid='.$this->companyId.' and delete_flag=0 group by material_id) ms on(t.lid = ms.material_id)'.
				'left join (select lid,material_id,inventory_stock,inventory_sales,ratio from nb_inventory_detail where inventory_id in(select max(lid) from nb_inventory where dpid ='.$this->companyId.' and type =2 and status =0) group by material_id) inv on(inv.material_id = t.lid)'.
				'where t.lid in(select tt.lid from nb_product_material tt where tt.delete_flag = 0 and tt.dpid ='.$this->companyId.' and tt.category_id '.$cate.') and mu.delete_flag =0 and ms.delete_flag =0 order by t.material_identifier asc,t.category_id asc,t.lid asc';
		$models = $db->createCommand($sql)->queryAll();
		$categories = $this->getMaterialCategory($this->companyId);
		$this->render('kcpd',array(
				'models'=>$models,
				'categorys'=>$categories,
		
		));
	}
	//库存盘损原因
	public function actionKcpsyy(){
		$criteria = new CDbCriteria;
		$criteria->condition = 't.dpid='.$this->companyId.' and t.type=2 and t.delete_flag=0';
		$criteria->order = ' t.lid desc ';
		$models = Retreat::model()->findAll($criteria);
		$this->render('kcpsyy',array(
				'models'=>$models
		));
	}
	public function actionCreateKcpsyy(){
		$id = Yii::app()->request->getParam('id',0);
		if($id){
			$criteria = new CDbCriteria;
			$criteria->condition =  'lid='.$id.' and dpid='.$this->companyId.' and type=2 and delete_flag=0';
			$model = Retreat::model()->find($criteria);
		}else{
			$model = new Retreat();
		}
		if(Yii::app()->request->isPostRequest){
			$data = Yii::app()->request->getPost('Retreat');
			if(!$id){
				$se = new Sequence("retreat");
				$model->lid = $se->nextval();
				$model->dpid = $this->companyId;
				$model->create_at = date('Y-m-d H:i:s',time());
				$model->update_at = date('Y-m-d H:i:s',time());
				$model->type = 2;
			}
			$model->attributes = $data;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('appReport/kcpsyy' , 'companyId' => $this->companyId ));
			}
		}
		$this->render('ckcpsyy',array(
				'model'=>$model
	
		));
	}
	public function actionDeleteKcpsyy(){
		$id = Yii::app()->request->getParam('id',0);
		$sql = 'update nb_retreat set delete_flag=1 where lid='.$id.' and dpid='.$this->companyId;
		$result = Yii::app()->db->createCommand($sql)->execute();
		if($result){
			Yii::app()->user->setFlash('success',yii::t('app','删除成功！'));
		}else{
			Yii::app()->user->setFlash('success',yii::t('app','删除失败！'));
		}
		$this->redirect(array('appReport/kcpsyy' , 'companyId' => $this->companyId ));
	}
	//库存盘损
	public function actionKcps(){
		$companyId = $this->companyId;
		$reasons = Retreat::model()->findAll('dpid='.$companyId.' and type=2 and delete_flag=0');
		$users = User::model()->findAll('dpid='.$companyId.' and status=1 and delete_flag=0') ;
		
		$pcategorys = $this->getProductCategory($companyId);
		$categorys = $this->getMaterialCategory($companyId);
		$products = $this->getProducts($companyId);
		$materials = $this->getMaterials($companyId);
		
		$this->render('kcps',array(
				'pcategorys'=>$pcategorys,
				'categorys'=>$categorys,
				'materials'=>$materials,
				'products'=>$products,
				'reasons'=>$reasons,
				'users'=>$users
		));
	}
	// 盘损记录
	public function actionPsjl(){
		$retreatId = Yii::app()->request->getParam('rid',0);
		$start = Yii::app()->request->getParam('start','');
		$end = Yii::app()->request->getParam('end','');
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid='.$this->companyId.' and t.type =1 and t.delete_flag=0');
		if($retreatId){
			$criteria->addCondition('t.reason_id='.$retreatId);
		}
		if(!empty($start)){
			$criteria->addCondition('t.create_at >="'.$start.' 00:00:00"');
		}
		if(!empty($end)){
			$criteria->addCondition('t.create_at <="'.$end.' 23:59:59"');
		}
		$criteria->order = ' t.lid desc ';
		
		$pages = new CPagination(Inventory::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = Inventory::model()->findAll($criteria);
		
		$retreats = Retreat::model()->findAll('dpid='.$this->companyId.' and type=2 and delete_flag=0');
		
		$this->render('psjl',array(
				'models'=>$models,
				'retreats'=>$retreats,
				'start'=>$start,
				'end'=>$end,
				'retreatId'=>$retreatId,
				'pages'=>$pages
		));
	}
	public function actionPsdetail(){
		$id = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->with = 'retreat';
		$criteria->condition = 't.lid='.$id.' and t.dpid='.$this->companyId.' and t.delete_flag=0';
		$model = Inventory::model()->find($criteria);
		$models = InventoryDetail::model()->findAll('dpid='.$this->companyId.' and inventory_id='.$id.' and delete_flag=0');
		$this->render('psdetail',array(
				'model'=>$model,
				'models'=>$models
		));
	}
	// 盘点记录
	public function actionPdjl(){
		$type = Yii::app()->request->getParam('type',0);
		$start = Yii::app()->request->getParam('start','');
		$end = Yii::app()->request->getParam('end','');
		$criteria = new CDbCriteria;
		$criteria->addCondition('t.dpid='.$this->companyId.' and t.delete_flag=0');
		if($type){
			$criteria->addCondition('t.type='.$type);
		}
		if(!empty($start)){
			$criteria->addCondition('t.create_at >="'.$start.' 00:00:00"');
		}
		if(!empty($end)){
			$criteria->addCondition('t.create_at <="'.$end.' 23:59:59"');
		}
		$criteria->order = ' t.lid desc ';
	
		$pages = new CPagination(StockTaking::model()->count($criteria));
		$pages->applyLimit($criteria);
		$models = StockTaking::model()->findAll($criteria);
	
	
		$this->render('pdjl',array(
				'models'=>$models,
				'start'=>$start,
				'end'=>$end,
				'type'=>$type,
				'pages'=>$pages
		));
	}
	public function actionPddetail(){
		$id = Yii::app()->request->getParam('id',0);
		$criteria = new CDbCriteria;
		$criteria->condition = 't.lid='.$id.' and t.dpid='.$this->companyId.' and t.delete_flag=0';
		$model = StockTaking::model()->find($criteria);
		$models = StockTakingDetail::model()->findAll('dpid='.$this->companyId.' and logid='.$id.' and delete_flag=0');
		$this->render('pddetail',array(
				'model'=>$model,
				'models'=>$models
		));
	}
	public function actionStore(){
		$sid = Yii::app()->request->getParam('sid');
		$sql = 'select * from nb_storage_order where lid='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
		$storage = Yii::app()->db->createCommand($sql)->queryRow();
		if($storage){
			$sql = 'select * from nb_storage_order_detail where storage_id='.$sid.' and dpid='.$this->companyId.' and delete_flag=0';
			$storageDetails = Yii::app()->db->createCommand($sql)->queryAll();
			$transaction = Yii::app()->db->beginTransaction();
			try{
				foreach ($storageDetails as $detail){
					$sql = 'select * from nb_product_material where lid='.$detail['material_id'].' and dpid='.$this->companyId.' and delete_flag=0';
					$prodmaterial = Yii::app()->db->createCommand($sql)->queryRow();
					
					$sql = 'select * from nb_material_unit_ratio where stock_unit_id='.$prodmaterial['stock_unit_id'].' and sales_unit_id='.$prodmaterial['sales_unit_id'].' and dpid='.$this->companyId.' and delete_flag=0';
					$unitratio = Yii::app()->db->createCommand($sql)->queryRow();
					if(!empty($unitratio)){
						$sql = 'update nb_product_material_stock set stock=0 where stock<0 and delete_flag=0 and material_id='.$detail['material_id'].' and dpid='.$this->companyId;
						Yii::app()->db->createCommand($sql)->execute();
						
						$num = $detail['stock'] * $unitratio['unit_ratio'];
						$pms = new Sequence("product_material_stock");
						$lid = $pms->nextval(); 
						$gmdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$detail['material_id'],
								'mphs_code'=>$detail['mphs_code'],
								'stock_day'=>$detail['stock_day'],
								'batch_stock'=>$num,
								'stock'=>$num,
								'free_stock'=>$detail['free_stock'],
								'stock_cost'=>$detail['price'],
						);
						Yii::app()->db->createCommand()->insert('nb_product_material_stock',$gmdata);
						//入库日志
						$se=new Sequence("material_stock_log");
						$lid = $se->nextval();
						$gmlogdata = array(
								'lid'=>$lid,
								'dpid'=>$this->companyId,
								'create_at'=>date('Y-m-d H:i:s',time()),
								'update_at'=>date('Y-m-d H:i:s',time()),
								'material_id'=>$detail['material_id'],
								'type'=>0,
								'stock_num'=>$num,
								'resean'=>'入库单入库',
						);
						
						Yii::app()->db->createCommand()->insert('nb_material_stock_log',$gmlogdata);
					}
					//如果没有入库单位和零售单位比的话，要提示没有入库成功。。。
				}
				$sql = 'update nb_storage_order set status=3,storage_date="'.date('Y-m-d H:i:s',time()).'" where dpid='.$this->companyId.' and lid='.$sid;
				Yii::app()->db->createCommand($sql)->execute();
				$transaction->commit();
				echo 'true';exit;
			}catch (Exception $e){
				var_dump($e->getMessage());
				$transaction->rollback();
				echo 'false';exit;
			}
		}
		echo 'false';
		exit;
		
	}
	//盘损保存
	public function actionAjaxKcps(){
		$dpid = $this->companyId;
		$inventory = Yii::app()->request->getPost('Inventory');
		$materials = Yii::app()->request->getPost('material');
		$products = Yii::app()->request->getPost('product');
		$time = time();
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			//盘损日志
			$se = new Sequence("inventory");
			$lid = $se->nextval();
			$stockArr = array(
					'lid'=>$lid,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'type'=>1,
					'opretion_id'=>$inventory['opretion_id'],
					'reason_id'=>$inventory['reason_id'],
					'inventory_account_no'=>date('YmdHis',time()).substr($lid,-4),
					'status'=>1,
					'remark'=>$inventory['remark'],
			);
			$db->createCommand()->insert('nb_inventory',$stockArr);
			
			$materialArr = array();
			foreach ($materials as $key=>$m){
				$num = $m['number'];
				if(!empty($num)){
					$se = new Sequence("inventory_detail");
					$dlid = $se->nextval();
					$stockArr = array(
							'lid'=>$dlid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'type'=>1,
							'inventory_id'=>$lid,
							'material_id'=>$key,
							'inventory_stock'=>$num,
					);
					$db->createCommand()->insert('nb_inventory_detail',$stockArr);
					
					$tempArr = array();
					$tempArr['material_id'] = $key;
					$tempArr['inventory_stock'] = $num;
					array_push($materialArr, $tempArr);
				}
			}
			foreach ($products as $key=>$p){
				$num = $p['number'];
				if(!empty($num)){
					$se = new Sequence("inventory_detail");
					$dlid = $se->nextval();
					$stockArr = array(
							'lid'=>$dlid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'type'=>2,
							'inventory_id'=>$lid,
							'material_id'=>$key,
							'inventory_stock'=>$num,
					);
					$db->createCommand()->insert('nb_inventory_detail',$stockArr);
					
					$sql = 'select * from nb_product_bom where dpid='.$dpid.' and product_id='.$key.' and delete_flag=0';
					$boms = $db->createCommand($sql)->queryAll();
					foreach ($boms as $bom){
						$tempArr = array();
						$tempArr['material_id'] = $bom['material_id'];
						$tempArr['inventory_stock'] = $num*$bom['number'];
						array_push($materialArr, $tempArr);
					}
				}
			}
			
			$pid = $lid;
			foreach ($materialArr as $material){
				$id = $material['material_id'];
				$nowNum = $material['inventory_stock'];//盘损的库存
					
				// 盘损记录
				$originalNum = 0;
				$sql = 'select sum(stock) as stocks from nb_product_material_stock where stock != 0 and dpid ='.$dpid.' and material_id ='.$id;
				$ms = $db->createCommand($sql)->queryRow();
				if($ms){
					$originalNum = $ms['stocks'];//原始库存
				}
					
				$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$this->companyId.' and delete_flag=0 order by create_at desc limit 1';
				$stocks = $db->createCommand($sql)->queryRow();
				// 已经入库
				if(!empty($stocks)){
					//对该次盘损进行日志保存
					if($nowNum>0){
						$sql = 'select * from nb_product_material_stock where stock != 0 and dpid ='.$dpid.' and material_id = '.$id.' and delete_flag = 0 order by create_at asc';
						$stock2 = $db->createCommand($sql)->queryAll();
						$minusnum = $nowNum;
							
						foreach ($stock2 as $stock){
							$stockori = $stock['stock'];
							if($minusnum >= 0){
								$minusnums = $minusnum - $stockori ;
								if($stock['batch_stock'] == '0.00'){
									$unit_price = '0';
								}else{
									$unit_price = $stock['stock_cost'] / $stock['batch_stock'];
								}
								if($minusnums <= 0 ) {
									$changestock = $stock['stock'] - $minusnum;
										
									$sql = 'update nb_product_material_stock set stock = '.$changestock. ' where dpid ='.$this->companyId.' and lid='.$stock['lid'].' and delete_flag = 0';
									$command=$db->createCommand($sql)->execute();
									// 盘损成本
									//对该次盘损进行日志保存
									$se = new Sequence("material_stock_log");
									$stocktakingdetails = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>4,
											'logid'=>$pid,
											'material_id'=>$id,
											'stock_num' => $minusnum,
											'original_num' => $stock['stock'],
											'unit_price'=>$unit_price,
											'resean'=>'盘损消耗',
									);
									$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
									break;
								}else{
									$minusnum = $minusnums;
									$sql = 'update nb_product_material_stock set stock=0 where delete_flag = 0 and lid ='.$stock['lid'].' and dpid ='.$this->companyId;
									$command = $db->createCommand($sql)->execute();
										
									//对该次盘点进行日志保存
									$se = new Sequence("material_stock_log");
									$stocktakingdetails = array(
											'lid'=>$se->nextval(),
											'dpid'=>$dpid,
											'create_at'=>date('Y-m-d H:i:s',time()),
											'update_at'=>date('Y-m-d H:i:s',time()),
											'type'=>4,
											'logid'=>$pid,
											'material_id'=>$id,
											'stock_num' => $stock['stock'],
											'original_num' => $stock['stock'],
											'unit_price'=>$unit_price,
											'resean'=>'盘损消耗',
									);
									$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
								}
							}
						}
					}
				}else{
					$se = new Sequence("material_stock_log");
					$stocktakingdetails = array(
							'lid'=>$se->nextval(),
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'type'=>4,
							'logid'=>$pid,
							'material_id'=>$id,
							'stock_num' => $nowNum,
							'original_num' => 0,
							'unit_price'=>0,
							'resean'=>'盘损消耗',
					);
					$command = $db->createCommand()->insert('nb_material_stock_log',$stocktakingdetails);
				}
			}
			$transaction->commit();
			$status = true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			$status = false;
		}
		if($status){
			Yii::app()->user->setFlash('success',yii::t('app','盘点成功！'));
			$this->redirect(array('/ymall/appReport/psjl','companyId'=>$companyId));
		}else{
			Yii::app()->user->setFlash('success',yii::t('app','盘点失败！'));
			$this->redirect(array('/ymall/appReport/kcps','companyId'=>$companyId));
		}
	}
	//盘点保存
	public function actionAjaxKcpd(){
		$sttype = Yii::app()->request->getPost('type',0);
		$materials = Yii::app()->request->getPost('material',0);
		$username = $this->brandUser['user_name'];
		$nostockmsg = '';
		$time = time();
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			//清除之前盘点
			$sql = 'update nb_stock_taking set delete_flag=1 where dpid='.$dpid.' and status=0';
			$db->createCommand()->execute();
			//盘点日志
			$se = new Sequence("stock_taking");
			$logid = $se->nextval();
			$stockArr = array(
					'lid'=>$logid,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',$time),
					'update_at'=>date('Y-m-d H:i:s',$time),
					'username'=>$username,
					'type'=>$sttype,
					'title'=>date('m月d日 H时i分',$time).' 盘点操作记录',
					'status'=>0
			);
			$db->createCommand()->insert('nb_stock_taking',$stockArr);
				
			foreach ($materials as $key=>$material){
		
				$id = $key;
				$nownumd = $material['inventory_stock'];
				$nownumx = $material['inventory_sales'];
				$ratio = $material['ratio'];
		
				// 系统库存
				$originalNum = $material['origin-num'];
				// 原料销售单位
				$salesName = $material['sales_name'];
		
				$systemNum = $originalNum;//系统库存
				$nowNum = $nownumd*$ratio + $nownumx;// 盘点库存
				
				if(empty($nowNum)){
					continue;
				}
				// 查询原料是否入库
				$sql = 'select * from nb_product_material_stock where material_id='.$id.' and dpid='.$dpid.' and delete_flag=0 order by create_at desc limit 1';
				$stocks = $db->createCommand($sql)->queryRow();
				// 已入库
				if(!empty($stocks)){
					//盘点详情记录
					$se = new Sequence("stock_taking_detail");
					$lid = $se->nextval();
					$stocktakingdetails = array(
							'lid'=>$lid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' =>$stocks['lid'],
							'reality_stock' =>$systemNum,
							'taking_stock' =>$nowNum,
							'number'=>'1',
							'reasion'=>$salesName,
					);
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetails);
				}else{
					$matername = Common::getmaterialName($id);
					$nostockmsg = $nostockmsg.','.$matername;
						
					//对该次盘点进行日志保存
					$se=new Sequence("stock_taking_detail");
					$detailid = $se->nextval();
					$stocktakingdetail = array(
							'lid'=>$detailid,
							'dpid'=>$dpid,
							'create_at'=>date('Y-m-d H:i:s',$time),
							'update_at'=>date('Y-m-d H:i:s',$time),
							'logid'=>$logid,
							'material_id'=>$id,
							'material_stock_id' => '0000000000',
							'reality_stock' => $systemNum,
							'taking_stock' => $nowNum,
							'number'=>'0',
							'reasion'=>'该次盘点['.$matername.']尚未入库，无法进行盘点,请先添加入库单进行入库.',
					);
					$command = $db->createCommand()->insert('nb_stock_taking_detail',$stocktakingdetail);
				}
			}
				
			$sql = 'update nb_inventory set status =2 where status=0 and dpid='.$dpid.' and delete_flag=0';
			$db->createCommand($sql)->execute();
			$transaction->commit();
			$status = true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			$message = $e->getMessage();
			$status = false;
		}
		if($status){
			Yii::app()->user->setFlash('success',yii::t('app','盘点成功！'));
			$this->redirect(array('/ymall/appReport/pdjl','companyId'=>$dpid));
		}else{
			Yii::app()->user->setFlash('success',yii::t('app','盘点失败！'.$message));
			$this->redirect(array('/ymall/appReport/kcpd','companyId'=>$dpid));
		}
	}
	private function getProductCategory($dpid){
		$categorys = array();
		$sql = 'select * from nb_product_category where dpid='.$dpid.' and cate_type=0 and delete_flag=0';
		$cates = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cates as $cate){
			$pid = $cate['pid'];
			if(!isset($categorys[$pid])){
				$categorys[$pid] = array();
			}
			array_push($categorys[$pid], $cate);
		}
		return $categorys;
	}
	private function getMaterialCategory($dpid){
		$categorys = array();
		$sql = 'select * from nb_material_category where dpid='.$dpid.' and delete_flag=0';
		$cates = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($cates as $cate){
			$pid = $cate['pid'];
			if(!isset($categorys[$pid])){
				$categorys[$pid] = array();
			}
			array_push($categorys[$pid], $cate);
		}
		return $categorys;
	}
	private function getMaterials($dpid){
		$sql = 'select * from nb_product_material where dpid='.$dpid.' and delete_flag=0';
		$materials = Yii::app()->db->createCommand($sql)->queryAll();
		return $materials;
	}
	private function getProducts($dpid){
		$sql = 'select * from nb_product where dpid='.$dpid.' and delete_flag=0';
		$materials = Yii::app()->db->createCommand($sql)->queryAll();
		return $materials;
	}
	public function getRatio($mulid,$mslid){
		$sql = 'select unit_ratio from nb_material_unit_ratio where stock_unit_id='.$mulid.' and sales_unit_id='.$mslid;
		$models = Yii::app()->db->createCommand($sql)->queryRow();
		if(!empty($models)){
			$r = $models['unit_ratio'];
		}else{
			$r = '0';
		}
		return $r;
	}
	private  function Jurisdiction($role){
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