<?php
class StatementmemberController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , '请选择公司˾');
			$this->redirect(array('company/index'));
		}
		return true;
	}

	public function actionList() {
		$type = Yii::app()->request->getParam('type');
		$this->render('list',array(
				'companyId' => $this->companyId,
				'type'=>$type,
		));
	}
	
	public function actionWxmemberReport(){
		$xAxisname = '[';
		$seriesnum = '[';
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$sex = Yii::app()->request->getParam('sex','-1');
		$sub = Yii::app()->request->getParam('sub','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,count(t.lid) as all_num';
		$criteria->condition = 't.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($sex>=0){
			$criteria->addCondition("t.sex =".$sex);
		}
		if($sub>=0){
			$criteria->addCondition("t.unsubscribe =".$sub);
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
		//$pages = new CPagination(BrandUser::model()->count($criteria));
		//$pages->applyLimit($criteria);
		$models = BrandUser::model()->findAll($criteria);
		if($models){
			foreach ($models as $model){
				if($text==1){
					$xAxisname = $xAxisname .'"'.$model->y_all.'",';
				}elseif($text==2){
					$xAxisname = $xAxisname .'"'.$model->y_all.'-'.$model->m_all.'",';
				}else{
					$xAxisname = $xAxisname .'"'.$model->y_all.'-'.$model->m_all.'-'.$model->d_all.'",';
				}
				
				$seriesnum = $seriesnum.$model->all_num.',';
			}
			$xAxisname = $xAxisname .']';
			$seriesnum = $seriesnum .']';
		}else{
			$xAxisname =0;
			$seriesnum =0;
		}
		$this->render('wxmemberReport',array(
				'models'=>$models,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'sex'=>$sex,
				'sub'=>$sub,
				'xAxisname'=>$xAxisname,
				'seriesnum'=>$seriesnum,
		));
	}

	
	public function actionCardmemberReport(){
		$xAxisname = '[';
		$seriesnum = '[';
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$sex = Yii::app()->request->getParam('sex','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,count(t.lid) as all_num';
		$criteria->condition = 't.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($sex>=0){
			$criteria->addCondition('t.sex ="'.$sex.'"');
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
	
		if($text==1){
			$criteria->group ='year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
	
		//$pages = new CPagination(MemberCard::model()->count($criteria));
		//	    $pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = MemberCard::model()->findAll($criteria);
		if($models){
			foreach ($models as $model){
				if($text==1){
					$xAxisname = $xAxisname .'"'.$model->y_all.'",';
				}elseif($text==2){
					$xAxisname = $xAxisname .'"'.$model->y_all.'-'.$model->m_all.'",';
				}else{
					$xAxisname = $xAxisname .'"'.$model->y_all.'-'.$model->m_all.'-'.$model->d_all.'",';
				}
		
				$seriesnum = $seriesnum.$model->all_num.',';
			}
			$xAxisname = $xAxisname .']';
			$seriesnum = $seriesnum .']';
		}else{
			$xAxisname =0;
			$seriesnum =0;
		}
		//var_dump($models);exit;
		$this->render('cardmemberReport',array(
				'models'=>$models,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'sex'=>$sex,
				'xAxisname'=>$xAxisname,
				'seriesnum'=>$seriesnum,
		));
	}
	
	/*
	 * 微信端充值记录报表
	*/
	public function actionWxRecharge(){
	
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = Yii::app()->request->getParam('text');
		$cardnumber = Yii::app()->request->getParam('cardnumber','');
		$memdpid = Yii::app()->request->getParam('memdpid','');
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
		$money = "";
		$recharge = "";
	
		if($cardnumber){
			$cardid = 'like "%'.$cardnumber.'%"';
		}else{
			$cardid = ' >0';
		}
		if(!empty($memdpid)){
			$dpidname = 'like "%'.$memdpid.'%"';
		}elseif($memdpid == '0'){
			$dpidname = 'like "%'.$memdpid.'%"';
		}else{
			$dpidname = ' is not null';
		}
			
		$db = Yii::app()->db;
		$com_sql = 'select type,comp_dpid ,company_name from nb_company where dpid ='.$companyId;
		$com = Yii::app()->db->createCommand($com_sql)->queryRow();
		$branch_sql = 'select dpid,company_name from nb_company where type= 1 and comp_dpid ='.$companyId;
		$branch = Yii::app()->db->createCommand($branch_sql)->queryAll();
	
	
			if($com['type']==0){
					 
				$sql = 'select cf.* from ( select sum(k.recharge_money) as recharge_all,sum(k.cashback_num) as cashback_all,p.pay_all,k.* '
						. ' from('
								.' select t1.dpid,t1.card_id,t1.user_name,t1.nickname,t1.weixin_group,t1.mobile_num,'
								.' t.recharge_money,t.cashback_num,t.brand_user_lid,com.company_name '
								.' from nb_recharge_record t,nb_brand_user t1 left join nb_company com on(t1.weixin_group = com.dpid)'
								.' where t.brand_user_lid = t1.lid and t1.dpid='.$companyId.' and '
								.' t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid = '.$companyId
						. ' ) k'
						. ' left join ('
								.'select sum(op.pay_amount) as pay_all,op.remark from nb_order_pay op '
										.'where op.paytype = 10 group by op.remark '
						. ' ) p on(p.remark = k.card_id) where k.company_name '.$dpidname.' and (k.card_id '.$cardid.' or k.mobile_num '.$cardid.') group by k.card_id) cf';
			
			}else{
				 
				$sql = 'select cf.* from ( select sum(k.recharge_money) as recharge_all,sum(k.cashback_num) as cashback_all,p.pay_all,k.* '
						. ' from('
								.' select t1.dpid,t1.card_id,t1.user_name,t1.nickname,t1.weixin_group,t1.mobile_num,'
								.' t.recharge_money,t.cashback_num,t.brand_user_lid,com.company_name '
								.' from nb_recharge_record t,nb_brand_user t1 ,nb_company com'
								.' where  t.brand_user_lid = t1.lid and t1.dpid='.$com['comp_dpid'].' and t1.weixin_group = '.$companyId.' and com.dpid = t1.weixin_group and '
								.' t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid = '.$com['comp_dpid']
						. ' ) k'
						. ' left join ('
								.' select sum(op.pay_amount) as pay_all,op.remark from nb_order_pay op '
										.' where op.paytype = 10 group by op.remark '
						.' ) p on(p.remark = k.card_id) where k.company_name '.$dpidname.' and (k.card_id '.$cardid.' or k.mobile_num '.$cardid.') group by k.card_id ) cf';
			
			}
		
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('cf.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
	
	
		//var_dump($models);exit;
		$this->render('wxRecharge',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'moneys'=>$money,
				'recharge'=>$recharge,
				'text'=>$text,
				'com'=>$com,
				'branch'=>$branch,
				'cardnumber'=>$cardnumber,
				'memdpid'=>$memdpid,
		));
	}
	public function actionPaymentReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text','1');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d ',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d ',time()));
	
		
		$sql = 'select k.dpid from nb_company k left join nb_company_property cp on(cp.dpid = k.dpid) where k.dpid = '.$this->companyId.' or k.comp_dpid = '.$this->companyId.' and k.delete_flag =0 and cp.is_rest in(2,3)';
		$dpids = Yii::app()->db->createCommand($sql)->queryAll();
		$strs ='0000000000';
		foreach ($dpids as $dpid){
			$strs = $strs .','.$dpid['dpid'];
		}
		
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid in ('.$strs.') and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//var_dump($ords);exit;
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,t.username,sum(orderpay.pay_amount) as all_reality,orderpay.paytype,orderpay.payment_method_id,count(distinct t.account_no) as all_num,count(distinct orderpay.order_id) as all_nums';//array_count_values()
		$criteria->with = array('company','orderpay');
		$criteria->condition = 'orderpay.paytype != "11" and t.order_status in (3,4,8)' ;
		$criteria->addCondition('t.lid in('.$ords.')');
		if($strs){
			$criteria->condition = 't.dpid in('.$strs.')';
		}else{
			$criteria->condition = 't.dpid ='.$this->companyId;
		}
		
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		
		$criteria->group ='t.dpid';
		$criteria->order = 'year(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';

		$criteria->distinct = TRUE;
		$models = Order::model()->findAll($criteria);
		//var_dump($models);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		
		$cfmodels = array();
		if($models){
			foreach ($models as $model){
				
				$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$model->dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
				$orders = Yii::app()->db->createCommand($sql)->queryAll();
				$ords ='0000000000';
				foreach ($orders as $order){
					$ords = $ords .','.$order['lid'];
				}
				
				$payprice = array();
				$payprice[8] = '0.00';
				$payprice[9] = '0.00';
				$payprice[10] = '0.00';
				$sqlprice = 'select sum(t.pay_amount) as all_reality,t.paytype from nb_order_pay t where t.dpid ='.$model->dpid.' and t.paytype in(8,9,10) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_id in('.$ords.') group by t.paytype';
				$prices = Yii::app()->db->createCommand($sqlprice)->queryAll();
				if(!empty($prices)){
					foreach ($prices as $price){
						$payprice[$price['paytype']] = $price['all_reality']?$price['all_reality']:'0.00';
					}
				}
				//var_dump($payprice);exit;
				$cfmodel = array();
				$cfmodel['company_name'] = $model->company->company_name;
				$cfmodel['all_nums'] = $model->all_nums;
				$cfmodel['all_reality'] = $model->all_reality;
				$cfmodel['wxcard_cupon'] = $payprice[9];
				$cfmodel['wxcard_point'] = $payprice[8];
				$cfmodel['wxcard_charge'] = $payprice[10];
				$cfmodels[] = $cfmodel;
			}
		}
		//var_dump($cfmodels);exit;
		$this->render('paymentReport',array(
				'models'=>$cfmodels,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'userid'=>$userid,
		));
	}
	public function actionConsumelist(){
		$db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
		$dpid = Yii::app()->request->getParam('dpid','0');
		$cardid = Yii::app()->request->getParam('cardid',"0000000000");
		$name = Yii::app()->request->getParam('name',"");
		
		$sql = 'select k.* from ('
				. ' select com.company_name,mo.should_total,mo.reality_total,mo.order_type,op.create_at,op.order_id,op.account_no,op.pay_amount,op.remark from nb_order_pay op '
				. ' left join (select o.* from nb_order o where o.order_status in("3","4","8") ) mo on(mo.dpid = op.dpid and mo.lid = op.order_id) '
				. ' left join nb_company com on(op.dpid = com.dpid)'
				. ' where op.paytype = 10 and op.remark ='.$cardid
				. ' and op.dpid in(select com.dpid from nb_company com where com.delete_flag = 0 and com.dpid = '.$companyId.' or com.comp_dpid ='.$companyId.') ) k';
		//echo $sql;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
		$this->renderPartial('consumelist' , array(
				'models' => $models,
				'pages' => $pages,
				'name' => $name
		));
	}
	

	public function actionRechargelist(){
		$db=Yii::app()->db;
		$companyId = Yii::app()->request->getParam('companyId',"0000000000");
		$dpid = Yii::app()->request->getParam('dpid','0');
		$cardid = Yii::app()->request->getParam('cardid',"0000000000");
		$cardlid = Yii::app()->request->getParam('cardlid',"0000000000");
		$name = Yii::app()->request->getParam('name',"");
	
		$sql = 'select k.* from ('
				. ' select com.company_name,rr.create_at,rr.recharge_money,rr.cashback_num,rr.point_num from nb_recharge_record rr '
				. ' left join nb_brand_user bu on(bu.dpid = rr.dpid and bu.lid = rr.brand_user_lid) '
				. ' left join nb_company com on(rr.dpid = com.dpid)'
				. ' where rr.delete_flag = 0'
				. ' and rr.brand_user_lid = "'.$cardlid.'" ) k';
	
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
		$this->renderPartial('rechargelist' , array(
				'models' => $models,
				'pages' => $pages,
				'name' => $name
		));
	}
	//获取店铺的支付方式....
	public function getPayments($dpid){
		$model =  '';
		if($dpid){
			$sql = 'select t.* from nb_payment_method t where t.delete_flag = 0 and t.dpid='.$dpid;
			$connect = Yii::app()->db->createCommand($sql);
			$models = $connect->queryAll();
			//$accountMoney = $money['all_zhifu'];
		}
		if(!empty($models)){
			return $models;
		}else{
			return $model;
		}
	}
	public function getPayment($dpid){
		$sql = 'select t.lid,t.dpid,t.name from nb_payment_method t where t.delete_flag = 0 and t.dpid ='.$dpid;
		$connect = Yii::app()->db->createCommand($sql);
		$models = $connect->queryAll();
		return $models;
	}
	public function getUsername($dpid){
		$name='';
		$sql = 'select t.* from nb_user t where t.delete_flag = 0 and t.dpid='.$dpid;
		$connect = Yii::app()->db->createCommand($sql);
		$model = $connect->queryAll();
		if(!empty($model)){
			return $model;
		}else{
			return $name;
		}
	}
	public function getComName(){
		$uid = Yii::app()->user->id;
		$sql = 'select t.lid,t.dpid,t1.company_id,t2.company_name from nb_user t left join nb_user_company t1 on(t.dpid = t1.dpid and t.lid = t1.user_id and t1.delete_flag = 0) left join nb_company t2 on(t1.company_id = t2.dpid ) where t.delete_flag = 0 and t.username = "'.$uid.'"';
		//var_dump($sql);exit;
		$connect = Yii::app()->db->createCommand($sql);
		//var_dump($connect);exit;
		$models = $connect->queryAll();
		//var_dump($model);exit;
		//$options = array();
		$optionsReturn = array();
		//var_dump($optionsReturn);exit;
		if($models) {
			foreach ($models as $model) {
				//var_dump($model);exit;
				$optionsReturn[$model['company_id']] = $model['company_name'];
				//var_dump($optionsReturn);exit;
			}
			//var_dump($optionsReturn);exit;
		}
	
		//var_dump($optionsReturn);exit;
		return $optionsReturn;
	}

	//gross profit 毛利润计算
	public function getGrossProfit($dpid,$begin_time,$end_time){
	
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
	
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.should_total) as should_all,sum(t.reality_total) as reality_all,count(*) as all_num';//array_count_values()
		//$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$dpid ;
		$criteria->addCondition ('t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"');
		$criteria->addCondition('t.lid in('.$ords.')');
		
		$model = Order::model()->findAll($criteria);
		$price = '';
		//var_dump($model);exit;
		if(!empty($model)){
			foreach ($model as $models){
				$price = $models->reality_all?$models->reality_all:0;
			}
		}
		return $price;
	}

	/*
	 * 支付方式报表的退款查询
	*/
	public function getPaymentRetreat($dpid,$begin_time,$end_time){
		$begin_time = $begin_time.' 00:00:00';
		$end_time = $end_time.' 23:59:59';
		$db = Yii::app()->db;
		
		$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;
		
		//var_dump($sql2);exit;
		$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		return $retreat['retreat_allprice'];
	}	
	
	//办卡记录excel
	public function actionCardmemberExport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$sex = Yii::app()->request->getParam('sex','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,count(t.lid) as all_num';
		$criteria->condition = 't.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($sex>=0){
			$criteria->addCondition('t.sex ="'.$sex.'"');
		}
		if($sex=="m"){
			$tiaojian = '性别：男；';
		}elseif($sex=="f"){
			$tiaojian = '性别：女；';
		}else{
			$tiaojian = '性别：所有；';
		}
			
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
	
		if($text==1){
			$criteria->group ='year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
	
		$models = MemberCard::model()->findAll($criteria);
	
		$objPHPExcel = new PHPExcel();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
		//设置字体
		$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
		$styleArray1 = array(
				'font' => array(
						'bold' => true,
						'color'=>array(
								'rgb' => '000000',
						),
						'size' => '20',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		$styleArray2 = array(
				'font' => array(
						'color'=>array(
								'rgb' => 'ff0000',
						),
						'size' => '16',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		//大边框样式 边框加粗
		$lineBORDER = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array('argb' => '000000'),
						),
				),
		);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
		//细边框样式
		$linestyle = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => 'FF000000'),
						),
				),
		);

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','实体卡会员增长记录报表')
		->setCellValue('A2',yii::t('app','条件：').$tiaojian.yii::t('app','时间段：').$begin_time.yii::t('app',' 至 ').$end_time."".yii::t('app','生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','数量')
		->setCellValue('C3','');
	
		$i=4;
		foreach($models as $v){
			//print_r($v);
			if ($text==1){
				$time = $v->y_all;
			}elseif($text==2){
				$time = $v->y_all.'-'.$v->m_all;
			}else{
				$time = $v->y_all.'-'.$v->m_all.'-'.$v->d_all;
			}
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$time)
				->setCellValue('B'.$i,$v->all_num)
				->setCellValue('C'.$i,'');
				
			$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:C'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//A2字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//A2字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色
	
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="实体卡会员增长记录报表（".date('m-d h:i',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
	

	//办卡记录excel
	public function actionWxmemberExport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$sex = Yii::app()->request->getParam('sex','-1');
		$sub = Yii::app()->request->getParam('sub','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,count(t.lid) as all_num';
		$criteria->condition = 't.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($sex>=0){
			$criteria->addCondition("t.sex =".$sex);
		}
		if($sex==0){
			$tiaojian = '性别：未知；';
		}elseif($sex==1){
			$tiaojian = '性别：男；';
		}elseif($sex==2){
			$tiaojian = '性别：女；';
		}else{
			$tiaojian = '性别：所有；';
		}
		if($sub>=0){
			$criteria->addCondition("t.unsubscribe =".$sub);
		}
		if($sex==0){
			$tiaojian2 = '关注：关注；';
		}elseif($sex==1){
			$tiaojian2 = '关注：取消；';
		}else{
			$tiaojian2 = '关注：所有；';
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
		//$pages = new CPagination(BrandUser::model()->count($criteria));
		//$pages->applyLimit($criteria);
		$models = BrandUser::model()->findAll($criteria);
	
		$objPHPExcel = new PHPExcel();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
		//设置字体
		$objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
		$styleArray1 = array(
				'font' => array(
						'bold' => true,
						'color'=>array(
								'rgb' => '000000',
						),
						'size' => '20',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		$styleArray2 = array(
				'font' => array(
						'color'=>array(
								'rgb' => 'ff0000',
						),
						'size' => '16',
				),
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				),
		);
		//大边框样式 边框加粗
		$lineBORDER = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THICK,
								'color' => array('argb' => '000000'),
						),
				),
		);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
		//细边框样式
		$linestyle = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => 'FF000000'),
						),
				),
		);
	
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','微信会员增长记录报表')
		->setCellValue('A2',yii::t('app','条件>>').$tiaojian.$tiaojian2.yii::t('app','时间段：').$begin_time.yii::t('app',' 至 ').$end_time."".yii::t('app','生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','数量')
		->setCellValue('C3','');
	
		$i=4;
		foreach($models as $v){
			//print_r($v);
			if ($text==1){
				$time = $v->y_all;
			}elseif($text==2){
				$time = $v->y_all.'-'.$v->m_all;
			}else{
				$time = $v->y_all.'-'.$v->m_all.'-'.$v->d_all;
			}
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i,$time)
			->setCellValue('B'.$i,$v->all_num)
			->setCellValue('C'.$i,'');
	
			$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:C'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//A2字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//A2字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色
	
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="微信会员增长记录报表（".date('m-d h:i',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
	
	public function actionClearTestdata() {
		$type = Yii::app()->request->getParam('type');
		$this->render('clearTestdata',array(
				'companyId' => $this->companyId,
				'type'=>$type,
		));
	}
	public function actionClearOrderdata(){
		$cleartype = Yii::app()->request->getParam('cleartype');
		$begin_time = Yii::app()->request->getParam('begin_time');
		$end_time = Yii::app()->request->getParam('end_time');
	
		$sqlorder = 'select * from nb_order where order_status in(3,4,8) and dpid='.$this->companyId.' and create_at >="'.$begin_time.'" and create_at <="'.$end_time.'"';
		$res = Yii::app()->db->createCommand($sqlorder)->queryAll();
		
		if(!empty($res)){
			if($cleartype == '1'){
				$sql = 'update nb_order set order_status =7 where dpid='.$this->companyId.' and create_at >="'.$begin_time.'" and create_at <="'.$end_time.'"';
			}else{
				$sql = 'update nb_order set order_status =7 where dpid='.$this->companyId;
			}
			$result = Yii::app()->db->createCommand($sql)->execute();
			if($result){
				Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功！')));
			}else{
				Yii::app()->end(json_encode(array("status"=>"eror",'msg'=>'失败')));
			}
		}else{
			Yii::app()->end(json_encode(array("status"=>"eror",'msg'=>'无可清除数据')));
		}
		exit;
	}
}