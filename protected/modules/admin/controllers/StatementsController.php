<?php
class StatementsController extends BackendController
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

/**
 * 产品销售报表
 *
 **/
	public function actionList() {
		$type = Yii::app()->request->getParam('type');
		$this->render('list',array(
				'companyId' => $this->companyId,
				'type'=>$type,
		));
	}
	public function actionProductsalesReport(){
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$catId = Yii::app()->request->getParam('cid',0);
		//var_dump($catId);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.update_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.update_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount) as all_price';
		$criteria->with = array('company','product');

		$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");

		if($text==1){
		$criteria->group ='t.product_id,year(t.update_at)';
		$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.product_id,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}else{
			$criteria->group ='t.product_id,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}

		$pages = new CPagination(OrderProduct::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();
		$comName = $this->getComName();


		$this->render('productsalesReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				//'catId'=>$catId
		));
	}

	/**
	 *
	 * 各种支付方式营业额报表
	 *
	 * **/
	public function actionSalesReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		if($text==1){
			if($str){
				$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid in('.$str.') and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}else{
			$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid ='.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}
		}elseif ($text==2){
			if($str){
				$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid in('.$str.') and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}else{
				$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}
		}elseif ($text==3){
			if($str){
				$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid in('.$str.') and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}else{
				$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
				$money = Yii::app()->db->createCommand($sql)->queryAll();
			}
		}

		$criteria = new CDbCriteria;

		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id';
		$criteria->with = array('company','order8','paymentMethod');
		$criteria->condition = 't.paytype != 4 and t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = 't.paytype != 4 and t.dpid in('.$str.')';
		}
		$criteria->addCondition("order8.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order8.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}elseif($text==3){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}

		$pages = new CPagination(OrderPay::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
	    $model = OrderPay::model()->findAll($criteria);
	    $comName = $this->getComName();
		$this->render('salesReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'money'=>$money,
		));
	}

	/**
	 *
	 * 菜品分类营业额报表
	 *
	 * **/
	public function actionCgReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$comName = $this->getComName();
		$db = Yii::app()->db;

			if ($text==1) {
				if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==2){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==3){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
			}

		//$sql = 'select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.update_at asc';
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		$this->render('cgReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
		));
	}

	public function actionIncomeReport(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		if($setid == 0){
			$setids = '=0';
		}elseif ($setid == 2){
			$setids = '>0';
		}else{
			$setids = '>=0';
		}
		//var_dump($setid);exit;
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$comName = $this->getComName();
		$db = Yii::app()->db;


		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = $db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.create_at asc';
		//var_dump($sql);exit;
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

							where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
									group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

							where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
									group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}
		if($download){
			$models = $db->createCommand($sql)->queryAll();
			//var_dump($models);exit;
			$this->exportIncomeReport($models,$text);
			exit;
		}
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(1,2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.create_at asc';
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//echo $sql;exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$this->render('incomeReport',array(
		'models'=>$models,
		'pages'=>$pages,
		'begin_time'=>$begin_time,
		'end_time'=>$end_time,
		'text'=>$text,
		'str'=>$str,
		'setid'=>$setid,
		//'catname'=>$Catname,
		'comName'=>$comName,
		));
	}

	/*
	 * 收款统计报表（支付方式）
	 */
public function actionPayallReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d ',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d ',time()));


		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order4','paymentMethod');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		$criteria->addCondition("order4.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order4.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,year(t.create_at),month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==3){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}
		$pages = new CPagination(OrderPay::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
	    $model = OrderPay::model()->findAll($criteria);
	   	//var_dump($model);exit;
	    $comName = $this->getComName();
		$this->render('payallReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
		));
	}

	/*
	 * 支付方式报表（支付方式）
	*/
	public function actionPaymentReport2(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d ',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d ',time()));

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num,count(distinct order_id) as all_nums';//array_count_values()
		$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($userid != '0' && $userid != '-1'){
			$criteria->addCondition ('order4.username ="'.$userid.'"');
		}
		$criteria->addCondition("order4.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order4.create_at <='$end_time 23:59:59'");
		if($text==1){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),order4.username';
				$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==2){
			if($userid != '0' ){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),order4.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==3){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at),order4.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
			}
		}
	    $model = OrderPay::model()->findAll($criteria);
	   	//var_dump($model);exit;
	   	$payments = $this->getPayment($this->companyId);
	   	$username = $this->getUsername($this->companyId);
	    $comName = $this->getComName();
		$this->render('paymentReport',array(
				'models'=>$model,
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

	public function actionPaymentReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';

		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,t.username,sum(orderpay.pay_amount) as all_reality,orderpay.paytype,orderpay.payment_method_id,count(distinct t.account_no) as all_num,count(distinct orderpay.order_id) as all_nums';//array_count_values()
		$criteria->with = array('company','orderpay');
		$criteria->condition = 'orderpay.paytype != "11" and t.order_status in (3,4,8)' ;
		$criteria->addCondition('t.lid in('.$ords.')');
		$criteria->addCondition('t.dpid ='.$this->companyId);


		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($userid != '0' && $userid != '-1'){
			$criteria->addCondition ('t.username ="'.$userid.'"');
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==2){
			if($userid != '0' ){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==3){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}
		$criteria->distinct = TRUE;
		$model = Order::model()->findAll($criteria);
		//var_dump($model);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//var_dump($model);exit;
		$this->render('paymentReport',array(
				'models'=>$model,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'userid'=>$userid,
				'dpname'=>$dpname,
		));
	}
	
	public function actionRijie(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');
	
		$sql = 'select * from nb_order_paytype_total where pay_amount_total >0 and dpid ='.$this->companyId.' and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" '
				.' union all '
				.' select * from nb_order_paytype_total where pay_amount_total <0 and dpid ='.$this->companyId.' and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" ';
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		
		//var_dump($models);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//var_dump($model);exit;
		$this->render('rijie',array(
				'models'=>$models,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'userid'=>$userid,
				'dpname'=>$dpname,
		));
	}
	
	public function actionPaymentReportSql(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}

		if($text==1){
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at)';
				$useros = 't.dpid,year(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at)';
				$usernames = ' != -1';
			}
		}elseif($text == 2){
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at)';
				$useros = 't.dpid,year(t.create_at),month(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at)';
				$usernames = ' != -1';
			}
		}else{
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),day(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),day(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),day(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),day(oo.create_at)';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),day(ot.create_at)';
				$usernames = ' != -1';
			}
		}

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';

		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all, '
				.' t.dpid,t.username,t.create_at '
				.' ,op.all_reality ,o.all_should, '
				.' op.all_nums,o.all_num,op0.all_cash,op1.all_wxpay,op2.all_alipay,op3.all_htpay,op4.all_member,op5.all_bankpay,op8.all_point,op9.all_cupon,op10.all_wxmember,op12.all_wxdd,op13.all_wxwm,op14.all_mtwm,op15.all_elemewm '
				.' from nb_order t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype !=11 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op on(t.dpid = op.dpid and op.username '.$usernames.' and year(t.create_at) = op.y_oo and month(t.create_at) = op.m_oo and day(t.create_at) = op.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cash,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =0 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op0 on(t.dpid = op0.dpid and op0.username '.$usernames.' and year(t.create_at) = op0.y_oo and month(t.create_at) = op0.m_oo and day(t.create_at) = op0.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =1 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op1 on(t.dpid = op1.dpid and op1.username '.$usernames.' and year(t.create_at) = op1.y_oo and month(t.create_at) = op1.m_oo and day(t.create_at) = op1.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_alipay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =2 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op2 on(t.dpid = op2.dpid and op2.username '.$usernames.' and year(t.create_at) = op2.y_oo and month(t.create_at) = op2.m_oo and day(t.create_at) = op2.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_htpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =3 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op3 on(t.dpid = op3.dpid and op3.username '.$usernames.' and year(t.create_at) = op3.y_oo and month(t.create_at) = op3.m_oo and day(t.create_at) = op3.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_member,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =4 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op4 on(t.dpid = op4.dpid and op4.username '.$usernames.' and year(t.create_at) = op4.y_oo and month(t.create_at) = op4.m_oo and day(t.create_at) = op4.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_bankpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =5 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op5 on(t.dpid = op5.dpid and op5.username '.$usernames.' and year(t.create_at) = op5.y_oo and month(t.create_at) = op5.m_oo and day(t.create_at) = op5.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_point,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =8 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op8 on(t.dpid = op8.dpid and op8.username '.$usernames.' and year(t.create_at) = op8.y_oo and month(t.create_at) = op8.m_oo and day(t.create_at) = op8.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =9 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op9 on(t.dpid = op9.dpid and op9.username '.$usernames.' and year(t.create_at) = op9.y_oo and month(t.create_at) = op9.m_oo and day(t.create_at) = op9.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =10 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op10 on(t.dpid = op10.dpid and op10.username '.$usernames.' and year(t.create_at) = op10.y_oo and month(t.create_at) = op10.m_oo and day(t.create_at) = op10.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxdd,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =12 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op12 on(t.dpid = op12.dpid and op12.username '.$usernames.' and year(t.create_at) = op12.y_oo and month(t.create_at) = op12.m_oo and day(t.create_at) = op12.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxwm,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =13 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op13 on(t.dpid = op13.dpid and op13.username '.$usernames.' and year(t.create_at) = op13.y_oo and month(t.create_at) = op13.m_oo and day(t.create_at) = op13.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_mtwm,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =14 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op14 on(t.dpid = op14.dpid and op14.username '.$usernames.' and year(t.create_at) = op14.y_oo and month(t.create_at) = op14.m_oo and day(t.create_at) = op14.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_elemewm,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =15 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op15 on(t.dpid = op15.dpid and op15.username '.$usernames.' and year(t.create_at) = op15.y_oo and month(t.create_at) = op15.m_oo and day(t.create_at) = op15.d_oo) '
				.' left join ('
				.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid,ot.username,year(ot.create_at) as y_ot,month(ot.create_at) as m_ot,day(ot.create_at) as d_ot '
				.' from nb_order ot '
				.' where ot.order_status in(3,4,8) and ot.lid in('.$ords.') and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
				.' group by '.$userots
				.' ) o on(t.dpid = o.dpid and o.username '.$usernames.' and year(t.create_at) = o.y_ot and month(t.create_at) = o.m_ot and day(t.create_at) = o.d_ot)'

				.' where '
				.' op.all_reality is not null and '
				.' t.order_status in(3,4,8) and t.lid in('.$ords.') and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and '
				.' t.dpid ='.$this->companyId
				.' group by '.$useros;
		$prices = Yii::app()->db->createCommand($sql)->queryAll();
		//echo $sql;exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//var_dump($model);exit;
		$this->render('paymentReportSql',array(
				//'models'=>$model,
				'prices'=>$prices,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'userid'=>$userid,
				'dpname'=>$dpname,
		));
	}
	
	

	public function actionRijieReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');
		$d = Yii::app()->request->getParam('d','0');
		if(empty($begin_time)){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time)){
			$end_time = date('Y-m-d',time());
		}
	
		if($userid == '0'){
			$username = ' != -1';
			$user = ' != -1';
		}elseif($userid == '-1'){
			$username = ' != -1';
			$user = ' = t.username ';
		}else{
			$username = ' = "'.$userid.'"';
			$user = ' = "'.$userid.'"';
		}
		
		if($text==1){

			$timesy = ' and year(t.create_at) = ';
			$timesm = ' and 0< ';
			$timesd = ' and 0<';
			
			if($userid != '0'){
				$users ='top.dpid,year(top.create_at),top.username';
				$useros ='t.dpid,year(t.create_at),t.username';
				$usernames = $username;
				
			}else{
				$users ='top.dpid,year(top.create_at)';
				$useros ='t.dpid,year(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at)';
				$usernames = $username;
				
			}
		}elseif($text == 2){
			
			$timesy = ' and year(t.create_at) = ';
			$timesm = ' and month(t.create_at) = ';
			$timesd = ' and 0< ';
			
			if($userid != '0'){
				$users ='top.dpid,year(top.create_at),month(top.create_at),top.username';
				$useros ='t.dpid,year(t.create_at),month(t.create_at),t.username';
				$usernames = $username;
			}else{
				$users ='top.dpid,year(top.create_at),month(top.create_at)';
				$useros ='t.dpid,year(t.create_at),month(t.create_at)';
				$usernames = $username;
			}
		}else{
			
			$timesy = ' and year(t.create_at) = ';
			$timesm = ' and month(t.create_at) = ';
			$timesd = ' and day(t.create_at) = ';
			
			if($userid != '0'){
				$users ='top.dpid,year(top.create_at),month(top.create_at),day(top.create_at),top.username';
				$useros ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at),t.username';
				$usernames = $username;
			}else{
				$users ='top.dpid,year(top.create_at),month(top.create_at),day(top.create_at)';
				$useros ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$usernames = $username;
			}
		}
		
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all, '
				.' t.dpid,t.username,t.create_at,t.poscode, '
				.' sum(t.pay_amount_total) as all_reality,sum(t.pay_order_num) as all_nums, '
				.' ifnull(op0.all_reality,"") as cash_money, '
				.' ifnull(op1.all_reality,"") as wx_money, '
				.' ifnull(op2.all_reality,"") as ali_money, '
				.' ifnull(op3.all_reality,"") as orther_money, '
				.' ifnull(op4.all_reality,"") as member_money, '
				.' ifnull(op5.all_reality,"") as visa_money, '
				.' ifnull(op8.all_reality,"") as jifen_money, '
				.' ifnull(op9.all_reality,"") as cupon_money, '
				.' ifnull(op10.all_reality,"") as wxyue_money, '
				.' ifnull(op12.all_reality,"") as wxord_money, '
				.' ifnull(op13.all_reality,"") as wxwm_money, '
				.' ifnull(op14.all_reality,"") as mt_money, '
				.' ifnull(op15.all_reality,"") as elem_money, '
				.' ifnull(op20.all_reality,"") as maoli_money,op20.all_nums as maoli_nums, '
				.' ifnull(op22.all_reality,"") as chunli_money,op22.all_nums as chunli_nums '
				.' from nb_order_paytype_total t '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =0 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op0 on(t.dpid = op0.dpid and op0.username '.$user.$timesy.'op0.y_oo'.$timesm.'op0.m_oo'.$timesd.'op0.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =1 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op1 on(t.dpid = op1.dpid and op1.username '.$user.$timesy.'op1.y_oo'.$timesm.'op1.m_oo'.$timesd.'op1.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =2 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op2 on(t.dpid = op2.dpid and op2.username '.$user.$timesy.'op2.y_oo'.$timesm.'op2.m_oo'.$timesd.'op2.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =3 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op3 on(t.dpid = op3.dpid and op3.username '.$user.$timesy.'op3.y_oo'.$timesm.'op3.m_oo'.$timesd.'op3.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =4 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op4 on(t.dpid = op4.dpid and op4.username '.$user.$timesy.'op4.y_oo'.$timesm.'op4.m_oo'.$timesd.'op4.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =5 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op5 on(t.dpid = op5.dpid and op5.username '.$user.$timesy.'op5.y_oo'.$timesm.'op5.m_oo'.$timesd.'op5.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =8 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op8 on(t.dpid = op8.dpid and op8.username '.$user.$timesy.'op8.y_oo'.$timesm.'op8.m_oo'.$timesd.'op8.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =9 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op9 on(t.dpid = op9.dpid and op9.username '.$user.$timesy.'op9.y_oo'.$timesm.'op9.m_oo'.$timesd.'op9.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =10 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op10 on(t.dpid = op10.dpid and op10.username '.$user.$timesy.'op10.y_oo'.$timesm.'op10.m_oo'.$timesd.'op10.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =12 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op12 on(t.dpid = op12.dpid and op12.username '.$user.$timesy.'op12.y_oo'.$timesm.'op12.m_oo'.$timesd.'op12.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =13 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op13 on(t.dpid = op13.dpid and op13.username '.$user.$timesy.'op13.y_oo'.$timesm.'op13.m_oo'.$timesd.'op13.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =14 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op14 on(t.dpid = op1.dpid and op14.username '.$user.$timesy.'op14.y_oo'.$timesm.'op14.m_oo'.$timesd.'op14.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =15 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op15 on(t.dpid = op1.dpid and op15.username '.$user.$timesy.'op15.y_oo'.$timesm.'op15.m_oo'.$timesd.'op15.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =20 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op20 on(t.dpid = op20.dpid and op20.username '.$user.$timesy.'op20.y_oo'.$timesm.'op20.m_oo'.$timesd.'op20.d_oo ) '
				.' left join ('
					.' select sum(top.pay_amount_total) as all_reality,sum(top.pay_order_num) as all_nums,top.dpid,top.create_at,top.username,top.poscode,year(top.create_at) as y_oo,month(top.create_at) as m_oo,day(top.create_at) as d_oo '
					.' from nb_order_paytype_total top '
					.' where top.dpid = '.$this->companyId.' and top.paytype =22 and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59" and top.username '.$username.' and top.delete_flag=0'
					.' group by '.$users
				.' ) op22 on(t.dpid = op22.dpid and op22.username '.$user.$timesy.'op22.y_oo'.$timesm.'op22.m_oo'.$timesd.'op22.d_oo ) '
				.' where t.paytype in(0,1,2,3,4,5,6,8,9,10,12,13,14,15) and t.dpid = '.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.username '.$username.' and t.delete_flag=0'
				.' group by '.$useros		
		;
		
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		
		if($d){
			$models = Yii::app()->db->createCommand($sql)->queryAll();
			//var_dump($models);exit;
			$this->RijieReportExport($models,$payments,$text,$userid,$this->companyId,$begin_time,$end_time);
			exit;
		}
		//echo $sql ;exit;
		
		$prices = Yii::app()->db->createCommand($sql)->queryAll();
		
		//var_dump($model);exit;
		$this->render('rijieReport',array(
				//'models'=>$model,
				'prices'=>$prices,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'userid'=>$userid,
				'dpname'=>$dpname,
		));
	}
	
	

	public function actionRijieReportSp(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');
	
		$reg = 'call cf_opts_dbtet('.$this->companyId.',"'.$begin_time.'","'.$end_time.'",@s);';
		$cmd = Yii::app()->db->createCommand($reg);
		$res = $cmd->execute();
		$s = Yii::app()->db->createCommand("select * from nb_optypetotal;");
		$prices = $s->queryAll();
		
		//var_dump($models);exit;
		
			$payments = $this->getPayment($this->companyId);
			$username = $this->getUsername($this->companyId);
			$comName = $this->getComName();
			//var_dump($model);exit;
			$this->render('rijieReportSp',array(
					//'models'=>$model,
					'prices'=>$prices,
					'begin_time'=>$begin_time,
					'end_time'=>$end_time,
					'text'=>$text,
					'str'=>$str,
					'comName'=>$comName,
					'payments'=>$payments,
					'username'=>$username,
					'userid'=>$userid,
					'dpname'=>$dpname,
			));
	}

	public function actionComPaymentReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}
		if(!empty($dpname)){
			$dpnames = ' like "%'.$dpname.'%"';
			$sql = 'select k.lid from nb_order k left join nb_company c on(k.dpid = c.dpid) where k.order_status in(3,4,8) and c.company_name like "%'.$dpname.'%" and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}else{
			$dpnames = ' is not null and t.comp_dpid = '.$this->companyId.' or t.dpid ='.$this->companyId;
			$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid in (select c.dpid from nb_company c where (c.comp_dpid = '.$this->companyId.' or c.dpid = '.$this->companyId.') and c.delete_flag =0 and c.type =1) and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$sql = 'select year(o.create_at) as y_all,month(o.create_at) as m_all,day(o.create_at) as d_all, '
				.' t.dpid,t.company_name,o.create_at,op.all_reality ,o.all_should, '
				.' op.all_nums,o.all_num,op0.all_cash,op1.all_wxpay,op2.all_alipay,op3.all_htpay,op4.all_member,op5.all_bankpay,op8.all_point,op9.all_cupon,op10.all_wxmember,op12.all_wxdd,op13.all_wxwm '
				.' from nb_company t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype !=11 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op on(t.dpid = op.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cash,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =0 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op0 on(t.dpid = op0.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =1 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op1 on(t.dpid = op1.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_alipay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =2 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op2 on(t.dpid = op2.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_htpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =3 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op3 on(t.dpid = op3.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_member,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =4 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op4 on(t.dpid = op4.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_bankpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =5 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op5 on(t.dpid = op5.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_point,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =8 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op8 on(t.dpid = op8.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =9 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op9 on(t.dpid = op9.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =10 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op10 on(t.dpid = op10.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxdd,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =12 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op12 on(t.dpid = op12.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxwm,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =13 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op13 on(t.dpid = op13.dpid) '
				.' left join ('
					.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid'
					.' from nb_order ot '
					.' where ot.order_status in(3,4,8) and ot.lid in('.$ords.') and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
					.' group by ot.dpid'
				.' ) o on(t.dpid = o.dpid)'
				.' where op.all_reality is not null and t.delete_flag =0 and t.company_name '.$dpnames
				.' group by t.dpid';
		$prices = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($prices);
		//exit;

		//var_dump($model);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//var_dump($model);exit;
		$this->render('comPaymentReport',array(
				'prices'=>$prices,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'dpname'=>$dpname,
		));
	}
	public function actionComPayYueReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}
		if(!empty($dpname)){
			$dpnames = ' like "%'.$dpname.'%"';
			$sql = 'select k.lid from nb_order k left join nb_company c on(k.dpid = c.dpid) where k.order_status in(3,4,8) and c.company_name like "%'.$dpname.'%" and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}else{
			$dpnames = ' is not null and t.comp_dpid = '.$this->companyId.' or t.dpid ='.$this->companyId;
			$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid in (select c.dpid from nb_company c where (c.comp_dpid = '.$this->companyId.' or c.dpid = '.$this->companyId.') and c.delete_flag =0 and c.type =1) and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$sql = 'select year(o.create_at) as y_all,month(o.create_at) as m_all,day(o.create_at) as d_all, '
				.' t.dpid,t.company_name,o.create_at,o.all_should, '
				.' o.all_num,op9.all_cupon,op9.all_nums as nums_cupon,op10.all_wxmember,op10.all_nums as nums_yue '
				.' from nb_company t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
						.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype !=11 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op on(t.dpid = op.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype =9 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op9 on(t.dpid = op9.dpid) '
				
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype =10 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op10 on(t.dpid = op10.dpid) '
				
				.' left join ('
					.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid'
					.' from nb_order ot '
					.' where ot.order_status in(3,4,8) and ot.lid in('.$ords.') and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
					.' group by ot.dpid'
				.' ) o on(t.dpid = o.dpid)'
				.' where op.all_reality is not null and t.delete_flag =0 and t.company_name '.$dpnames
				.' group by t.dpid';
		$prices = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($prices);
		//exit;

		//var_dump($model);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//var_dump($model);exit;
		$this->render('comPayYueReport',array(
				'prices'=>$prices,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'payments'=>$payments,
				'username'=>$username,
				'dpname'=>$dpname,
		));
	}


	public function actionComPaymentExport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}
		if(!empty($dpname)){
			$dpnames = ' like "%'.$dpname.'%"';
			$sql = 'select k.lid from nb_order k left join nb_company c on(k.dpid = c.dpid) where k.order_status in(3,4,8) and c.company_name like "%'.$dpname.'%" and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}else{
			$dpnames = ' is not null and t.comp_dpid = '.$this->companyId.' or t.dpid ='.$this->companyId;
			$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid in (select c.dpid from nb_company c where (c.comp_dpid = '.$this->companyId.' or c.dpid = '.$this->companyId.') and c.delete_flag =0 and c.type =1) and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$sql = 'select year(o.create_at) as y_all,month(o.create_at) as m_all,day(o.create_at) as d_all, '
				.' t.dpid,t.company_name,o.create_at,op.all_reality ,o.all_should, '
				.' op.all_nums,o.all_num,op0.all_cash,op1.all_wxpay,op2.all_alipay,op3.all_htpay,op4.all_member,op5.all_bankpay,op8.all_point,op9.all_cupon,op10.all_wxmember,op12.all_wxdd,op13.all_wxwm '
				.' from nb_company t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype !=11 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op on(t.dpid = op.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cash,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =0 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op0 on(t.dpid = op0.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =1 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op1 on(t.dpid = op1.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_alipay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =2 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op2 on(t.dpid = op2.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_htpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =3 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op3 on(t.dpid = op3.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_member,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =4 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op4 on(t.dpid = op4.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_bankpay,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =5 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op5 on(t.dpid = op5.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_point,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =8 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op8 on(t.dpid = op8.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =9 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op9 on(t.dpid = op9.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =10 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op10 on(t.dpid = op10.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxdd,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =12 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op12 on(t.dpid = op12.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxwm,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' where top.paytype =13 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op13 on(t.dpid = op13.dpid) '
				.' left join ('
					.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid'
					.' from nb_order ot '
					.' where ot.order_status in(3,4,8) and ot.lid in('.$ords.') and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
					.' group by ot.dpid'
				.' ) o on(t.dpid = o.dpid)'
				.' where op.all_reality is not null and t.delete_flag =0 and t.company_name '.$dpnames
				.' group by t.dpid';
		$models = Yii::app()->db->createCommand($sql)->queryAll();


        $objPHPExcel = new PHPExcel();
        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        //设置第2行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
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
        if($text==1){
			$str1 = '年';
		}elseif($text==2){
			$str1 = '月';
		}else{
			$str1 = '日';
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃微信会员信息表'))
        ->setCellValue('A2',yii::t('app','查询：(').$str1.')'.$begin_time.'~'.$end_time)
        ->setCellValue('A3',yii::t('app','店铺'))
        ->setCellValue('B3',yii::t('app','时间'))
        ->setCellValue('C3',yii::t('app','总单数'))
        ->setCellValue('D3',yii::t('app','毛利润'))
        ->setCellValue('E3',yii::t('app','优惠'))
        ->setCellValue('F3',yii::t('app','实收款'))
        ->setCellValue('G3',yii::t('app','现金'))
        ->setCellValue('H3',yii::t('app','微信'))
        ->setCellValue('I3',yii::t('app','微点单'))
        ->setCellValue('J3',yii::t('app','微外卖'))
        ->setCellValue('K3',yii::t('app','支付宝'))
        ->setCellValue('L3',yii::t('app','银联'))
        ->setCellValue('M3',yii::t('app','会员卡'))
        ->setCellValue('N3',yii::t('app','后台支付'))
        ->setCellValue('O3',yii::t('app','系统卷'))
        ->setCellValue('P3',yii::t('app','积分'))
        ->setCellValue('Q3',yii::t('app','微信储值消费'))
        ->setCellValue('R3',yii::t('app','退款'));
        $j=4;
        if($models){

                foreach ($models as $v) {
                	//日月年
					if($text==1){
						$str = $v['y_all'];
					}elseif($text==2){
						$str = $v['y_all'].-$v['m_all'];
					}else{
						$str = $v['y_all'].-$v['m_all'].-$v['d_all'];
					}

					//优惠
					$retreat = $this->getComPaymentRetreat($v['dpid'],$begin_time,$end_time,$text,$v['y_all'],$v['m_all'],$v['d_all']);
					$discount=sprintf("%.2f",$v['all_should']-$v['all_reality']+$retreat);


                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$j,$v['company_name'])
                    ->setCellValue('B'.$j,$str)
                    ->setCellValue('C'.$j,$v['all_nums'])
                    ->setCellValue('D'.$j,$v['all_should'])
                    ->setCellValue('E'.$j,$discount)
                    ->setCellValue('F'.$j,$v['all_reality'])
                    ->setCellValue('G'.$j,$v['all_cash'])
                    ->setCellValue('H'.$j,$v['all_wxpay'])
                    ->setCellValue('I'.$j,$v['all_wxdd'])
                    ->setCellValue('J'.$j,$v['all_wxwm'])
                    ->setCellValue('K'.$j,$v['all_alipay'])
                    ->setCellValue('L'.$j,$v['all_bankpay'])
                    ->setCellValue('M'.$j,$v['all_member'])
                    ->setCellValue('N'.$j,$v['all_htpay'])
                    ->setCellValue('O'.$j,$v['all_cupon'])
                    ->setCellValue('P'.$j,$v['all_point'])
                    ->setCellValue('Q'.$j,$v['all_wxmember'])
                    ->setCellValue('R'.$j,$retreat);
                    $j++;
                }
            }

        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:R1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:R2');
        //单元格加粗，居中：
        $objPHPExcel->getActiveSheet()->getStyle('A1:R'.$j)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="支付方式统计表（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');

	}
	
	public function actionComPayYueExport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}
		if(!empty($dpname)){
			$dpnames = ' like "%'.$dpname.'%"';
			$sql = 'select k.lid from nb_order k left join nb_company c on(k.dpid = c.dpid) where k.order_status in(3,4,8) and c.company_name like "%'.$dpname.'%" and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}else{
			$dpnames = ' is not null and t.comp_dpid = '.$this->companyId.' or t.dpid ='.$this->companyId;
			$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid in (select c.dpid from nb_company c where (c.comp_dpid = '.$this->companyId.' or c.dpid = '.$this->companyId.') and c.delete_flag =0 and c.type =1) and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		}
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		$sql = 'select year(o.create_at) as y_all,month(o.create_at) as m_all,day(o.create_at) as d_all, '
				.' t.dpid,t.company_name,o.create_at,o.all_should, '
				.' o.all_num,op9.all_cupon,op9.all_nums as nums_cupon,op10.all_wxmember,op10.all_nums as nums_yue '
				.' from nb_company t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
						.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype !=11 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op on(t.dpid = op.dpid) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype =9 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op9 on(t.dpid = op9.dpid) '
				
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid '
					.' from nb_order_pay top '
					.' left join nb_order topo on(topo.lid = top.order_id and topo.dpid = top.dpid)'
					.' where top.paytype =10 and topo.order_status in(3,4,8) and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by top.dpid'
				.' ) op10 on(t.dpid = op10.dpid) '
				
				.' left join ('
					.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid'
					.' from nb_order ot '
					.' where ot.order_status in(3,4,8) and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
					.' group by ot.dpid'
				.' ) o on(t.dpid = o.dpid)'
				.' where op.all_reality is not null and t.delete_flag =0 and t.company_name '.$dpnames
				.' group by t.dpid';
		$models = Yii::app()->db->createCommand($sql)->queryAll();


        $objPHPExcel = new PHPExcel();
        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        //设置第2行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
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
        if($text==1){
			$str1 = '年';
		}elseif($text==2){
			$str1 = '月';
		}else{
			$str1 = '日';
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃微信会员信息表'))
        ->setCellValue('A2',yii::t('app','查询：(').$str1.')'.$begin_time.'~'.$end_time)
        ->setCellValue('A3',yii::t('app','店铺'))
        ->setCellValue('B3',yii::t('app','时间'))
        ->setCellValue('C3',yii::t('app','单数'))
        ->setCellValue('D3',yii::t('app','系统卷'))
        ->setCellValue('E3',yii::t('app','单数'))
        ->setCellValue('F3',yii::t('app','微信储值消费'));
        $j=4;
        if($models){

                foreach ($models as $v) {
                	//日月年
					if($text==1){
						$str = $v['y_all'];
					}elseif($text==2){
						$str = $v['y_all'].-$v['m_all'];
					}else{
						$str = $v['y_all'].-$v['m_all'].-$v['d_all'];
					}


                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$j,$v['company_name'])
                    ->setCellValue('B'.$j,$str)
                    ->setCellValue('C'.$j,$v['nums_cupon'])
                    ->setCellValue('D'.$j,$v['all_cupon'])
                    ->setCellValue('E'.$j,$v['nums_yue'])
                    ->setCellValue('F'.$j,$v['all_wxmember']);
                    $j++;
                }
            }

        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:R1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:R2');
        //单元格加粗，居中：
        $objPHPExcel->getActiveSheet()->getStyle('A1:R'.$j)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="支付方式(储值)统计表（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');

	}
	//gross profit 毛利润计算
	public function getGrossProfit($dpid,$begin_time,$end_time,$text,$y_all,$m_all,$d_all,$usertype,$userid){

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
// 		if($usertype != '0'){
// 			$userid = ' ="'.$userid.'"';
// 		}else{

// 		}

// 		$sqls = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,'
// 				.' t.dpid,t.create_at,sum(t.should_total) as should_all,sum(t.reality_total) as reality_all,count(*) as all_num '
// 				.' from nb_order t where '
// 				.' t.paytype != "11" and t.dpid ='.$dpid
// 				.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59'
// 				.' and t.lid in('.$ords.')'
// 				.' and t.username '.$userid
// 				.' and '.$times
// 				.' ';

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.should_total) as should_all,sum(t.reality_total) as reality_all,count(*) as all_num';//array_count_values()
		//$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$dpid ;
		$criteria->addCondition ('t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"');
		$criteria->addCondition('t.lid in('.$ords.')');
		if($usertype != '0'){
			$criteria->addCondition ('t.username ="'.$userid.'"');
		}
		if($text==1){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
		}elseif($text==2){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
			$criteria->addCondition("month(t.create_at) ='$m_all'");
		}elseif($text==3){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
			$criteria->addCondition("month(t.create_at) ='$m_all'");
			$criteria->addCondition("day(t.create_at) ='$d_all'");
		}
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


	//gross profit 毛利润计算
	public function getComGrossProfit($dpid,$begin_time,$end_time,$text,$y_all,$m_all,$d_all){

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		// 		if($usertype != '0'){
		// 			$userid = ' ="'.$userid.'"';
		// 		}else{

		// 		}

		// 		$sqls = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,'
		// 				.' t.dpid,t.create_at,sum(t.should_total) as should_all,sum(t.reality_total) as reality_all,count(*) as all_num '
		// 				.' from nb_order t where '
		// 				.' t.paytype != "11" and t.dpid ='.$dpid
		// 				.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59'
		// 				.' and t.lid in('.$ords.')'
		// 				.' and t.username '.$userid
		// 				.' and '.$times
		// 				.' ';

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.should_total) as should_all,sum(t.reality_total) as reality_all,count(*) as all_num';//array_count_values()
		//$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$dpid ;
		$criteria->addCondition ('t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"');
		$criteria->addCondition('t.lid in('.$ords.')');

		if($text==1){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
		}elseif($text==2){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
			$criteria->addCondition("month(t.create_at) ='$m_all'");
		}elseif($text==3){
			$criteria->addCondition("year(t.create_at) ='$y_all'");
			$criteria->addCondition("month(t.create_at) ='$m_all'");
			$criteria->addCondition("day(t.create_at) ='$d_all'");
		}
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

	public function getPaymentPrice($dpid,$begin_time,$end_time,$type,$num,$text,$y_all,$m_all,$d_all,$usertype,$userid){
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$dpid ;
		$criteria->addCondition ('t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"');
		$criteria->addCondition('t.order_id in('.$ords.')');
		if($usertype != '0'){
			$criteria->addCondition ('order4.username ="'.$userid.'"');
		}
		if($text==1){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
		}elseif($text==2){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
			$criteria->addCondition("month(order4.create_at) ='$m_all'");
		}elseif($text==3){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
			$criteria->addCondition("month(order4.create_at) ='$m_all'");
			$criteria->addCondition("day(order4.create_at) ='$d_all'");
		}
		if($type==3){
			$criteria->addCondition("t.paytype =3 and t.payment_method_id ='$num'");
		}else{
			$criteria->addCondition("t.paytype ='$num'");
		}
		$model = OrderPay::model()->findAll($criteria);
		$price = '';
		if(!empty($model)){
			foreach ($model as $models){
				$price = $models->all_reality?$models->all_reality:0;
			}
		}
		return $price;
	}

	public function getPaymentSqlPrice($dpid,$begin_time,$end_time,$num,$text,$y_all,$m_all,$d_all,$usertype,$userid){

		if($usertype != '0'){
			$usern = 'o.username ="'.$userid.'"';
		}else{
			$usern = 'o.username != "-1"';
		}
		if($text==1){
			$times = ' and year(o.create_at) ="'.$y_all.'"';
		}elseif($text==2){
			$times = ' and year(o.create_at) ="'.$y_all.'" and month(o.create_at) ="'.$m_all.'"';
		}elseif($text==3){
			$times = ' and year(o.create_at) ="'.$y_all.'" and month(o.create_at) ="'.$m_all.'" and day(o.create_at) ="'.$d_all.'"';
		}

		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.pay_amount) as all_reality,count(*) as all_num, '
				.' t.dpid,t.create_at,t.paytype,t.payment_method_id '
				.' from nb_order_pay t'
				.' left join nb_order o on(t.dpid = o.dpid and t.order_id = o.lid) '
				.' where t.paytype ="3" and t.dpid ='.$dpid
				.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" '
				.' and t.order_id in( '
					.' select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid ='.$dpid
					.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" '
					.' group by k.user_id,k.account_no,k.create_at '
				.' ) '
				.' and t.payment_method_id ='.$num
				.' and '.$usern.' '.$times;
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		//$model = OrderPay::model()->findAll($criteria);
		$price = '';
		if(!empty($model)){

			$price = $model['all_reality']?$model['all_reality']:'';

		}
		return $price;
	}
	
	public function getRijiePrice($dpid,$begin_time,$end_time,$num,$text,$y_all,$m_all,$d_all,$usertype,$userid){
	
		if($usertype != '0'){
			$usern = 't.username ="'.$userid.'"';
		}else{
			$usern = 't.username != "-1"';
		}
		if($text==1){
			$times = ' and year(t.create_at) ="'.$y_all.'"';
		}elseif($text==2){
			$times = ' and year(t.create_at) ="'.$y_all.'" and month(t.create_at) ="'.$m_all.'"';
		}elseif($text==3){
			$times = ' and year(t.create_at) ="'.$y_all.'" and month(t.create_at) ="'.$m_all.'" and day(t.create_at) ="'.$d_all.'"';
		}
	
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.pay_amount_total) as all_reality,count(*) as all_num, '
				.' t.dpid,t.create_at,t.paytype,t.payment_id '
				.' from nb_order_paytype_total t'
				.' where t.delete_flag =0 and t.paytype ="3" and t.dpid ='.$dpid
				.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" '
				.' and t.payment_id ='.$num
				.' and '.$usern.' '.$times;
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		//$model = OrderPay::model()->findAll($criteria);
		$price = '';
		if(!empty($model)){
	
			$price = $model['all_reality']?$model['all_reality']:'';
	
		}
		return $price;
	}


	public function getComPaymentPrice($dpid,$begin_time,$end_time,$type,$num,$text,$y_all,$m_all,$d_all){
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$dpid.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order4');
		$criteria->condition = 't.paytype != "11" and t.dpid='.$dpid ;
		$criteria->addCondition ('t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59"');
		$criteria->addCondition('t.order_id in('.$ords.')');

		if($text==1){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
		}elseif($text==2){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
			$criteria->addCondition("month(order4.create_at) ='$m_all'");
		}elseif($text==3){
			$criteria->addCondition("year(order4.create_at) ='$y_all'");
			$criteria->addCondition("month(order4.create_at) ='$m_all'");
			$criteria->addCondition("day(order4.create_at) ='$d_all'");
		}
		if($type==3){
			$criteria->addCondition("t.paytype =3 and t.payment_method_id ='$num'");
		}else{
			$criteria->addCondition("t.paytype ='$num'");
		}
		$model = OrderPay::model()->findAll($criteria);
		$price = '';
		if(!empty($model)){
			foreach ($model as $models){
				$price = $models->all_reality?$models->all_reality:0;
			}
		}
		return $price;
	}

	public function getRetreatPrice($begin_time,$end_time,$str,$text,$y_all,$m_all,$d_all,$setid,$categoryId){
		if($setid == 0){
			$setids = '=0';
		}elseif ($setid == 2){
			$setids = '>0';
		}else{
			$setids = '>=0';
		}
		if(empty($categoryId)){
			return '0.0000';exit;
		}
		$db = Yii::app()->db;
		if ($text==1) {
				$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,t1.category_id,t2.category_name,t3.company_name,
						ifnull (sum(t.price*t5.retreat_amount),0) as retreat_all
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						left join nb_order_retreat t5 on(t.lid = t5.order_detail_id and t.dpid = t5.dpid)
						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
						and t1.category_id ='.$categoryId.' and year(t.create_at) ='.$y_all;
			}elseif ($text==2){
					$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,t1.category_id,t2.category_name,t3.company_name,
						ifnull (sum(t.price*t5.retreat_amount),0) as retreat_all
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						left join nb_order_retreat t5 on(t.lid = t5.order_detail_id and t.dpid = t5.dpid)
						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
						and t1.category_id ='.$categoryId.' and year(t.create_at) ='.$y_all.' and month(t.create_at) ='.$m_all;
			}elseif ($text==3){
				$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,t1.category_id,t2.category_name,t3.company_name,
						ifnull (sum(t.price*t5.retreat_amount),0) as retreat_all
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						left join nb_order_retreat t5 on(t.lid = t5.order_detail_id and t.dpid = t5.dpid)
						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
						and t1.category_id ='.$categoryId.' and year(t.create_at) ='.$y_all.' and month(t.create_at) ='.$m_all.' and day(t.create_at) ='.$d_all;
			}
		$models = $db->createCommand($sql)->queryRow();
			return $models['retreat_all'];
	}
	/*
	 * 充值记录报表
	 */
	public function actionRecharge(){

		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = Yii::app()->request->getParam('text');
                $companyId = Yii::app()->request->getParam('companyId',"0000000000");
		$money = "";
		$recharge = "";

		$db = Yii::app()->db;
                $com_sql = 'select type,comp_dpid ,company_name from nb_company where dpid ='.$companyId;
                $com = Yii::app()->db->createCommand($com_sql)->queryRow();
                $branch_sql = 'select dpid,company_name from nb_company where type= 1 and comp_dpid ='.$companyId;
                $branch = Yii::app()->db->createCommand($branch_sql)->queryAll();

		if($text==1){
			$sql = 'select k.* from(select t1.selfcode,t1.name,t.reality_money,t.give_money from nb_member_recharge t left join nb_member_card t1 on(t.member_card_id = t1.selfcode || t.member_card_id = t1.rfid and t1.delete_flag = 0) where t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.')) k';

		}else if($text==2){

                     if($com['type']==0){
			$sql = 'select k.* from(select t1.card_id,t1.user_name,t1.nickname,t1.weixin_group,t.recharge_money,t.cashback_num  from nb_recharge_record t ,nb_brand_user t1'
                                . '  where t.brand_user_lid = t1.lid and  t1.dpid =  t.dpid  and '
                                . '  t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid ='.$companyId.') k';

                     }else{

                     $sql = 'select k.* from(select t1.card_id,t1.user_name,t1.nickname,t.recharge_money,t.cashback_num ,com.company_name from nb_recharge_record t,nb_brand_user t1 ,nb_company com'
                             . '   where  t.brand_user_lid = t1.lid and t1.dpid='.$com['comp_dpid'].' and t1.weixin_group = '.$companyId.' and com.dpid = t1.weixin_group and  '
                             . '  t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid = '.$com['comp_dpid'].') k';
                     }
		}else{
			//$money = "0";
			//传统卡充值
			$sql = 'select k.* from(select sum(t.reality_money) as all_money,sum(t.give_money) as all_give from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ) k';
			$money = Yii::app()->db->createCommand($sql)->queryRow();
			//微信会员卡充值
			$sql = 'select k.* from(select sum(t.recharge_money) as all_recharge,sum(t.cashback_num) as all_cashback from nb_recharge_record t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59") k ';
			$recharge = Yii::app()->db->createCommand($sql)->queryRow();

		}

		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();


		//var_dump($model);exit;
		$this->render('recharge',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'moneys'=>$money,
				'recharge'=>$recharge,
				'text'=>$text,
                                'com'=>$com,
                                'branch'=>$branch
		));
	}
	/*
	 * 办卡记录报表
	*/
	public function actionMembercard(){
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = Yii::app()->request->getParam('text');
		$membercard = "";
		$branduser = "";
		$db = Yii::app()->db;
		if($text==1){
			$sql = 'select k.* from nb_member_card k where k.delete_flag = 0 and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" and k.dpid in('.$this->companyId.')';
			//var_dump($sql);exit;
		}
		if($text==2){
			$sql = 'select k.* from nb_brand_user k where k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" and k.dpid in('.$this->companyId.') ';
			//var_dump($sql);exit;
		}
		if($text==3){
			//$money = "0";
			//传统卡充值
			$sql = 'select k.* from(select count(t.lid) as card_num from nb_member_card t where t.dpid = '.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" ) k';
			$membercard = Yii::app()->db->createCommand($sql)->queryRow();
			//微信会员卡充值
			$sql = 'select k.* from(select count(t.lid) as brand_num from nb_brand_user t where t.dpid = '.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59") k ';
			$branduser = Yii::app()->db->createCommand($sql)->queryRow();

		}

		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		$this->render('membercard',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'membercard'=>$membercard,
				'branduser'=>$branduser,
				'text'=>$text,
		));
	}
	//var sql = '';
	/*
	 * 时段报表
	*/
	public function actionTimedataReport(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = $db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}


		$sql = 'select k.* from(select DATE_FORMAT(create_at,"%H") as h_all,sum(pay_amount) as pay_amount,count(distinct order_id) as all_account from nb_order_pay where  order_id in('.$ords.') and paytype !=11 and dpid in('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" group by h_all) k';
		$models = $db->createCommand($sql)->queryAll();

		$timeprice = array();
		$timesum = array();
		$hour = array();
		for ($i =0;$i<24;$i++){
			$timeprice[$i]='0';
			$timesum[$i]='0';
			foreach ($models as $model){
				if($model['h_all'] == $i ){
					$timeprice[$i]=$model['pay_amount'];
					$timesum[$i]=$model['all_account'];
				}else{

				}
			}
				array_push($hour,$i);
		}
		$maxp = array_search(max($timeprice), $timeprice);
		$maxp = $timeprice[$maxp];

		$maxs = array_search(max($timesum), $timesum);
		$maxs = $timesum[$maxs];

		$timeprice = json_encode($timeprice);
		$hour = json_encode($hour);
		$timesum = json_encode($timesum);
		//var_dump($timeprice);exit;
		$comName = $this->getComName();
		$this->render('timedataReport',array(
				'models'=>$models,
				//'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'timeprice'=>$timeprice,
				'timesum'=>$timesum,
				'hour'=>$hour,
				'maxp'=>$maxp,
                                'maxs'=>$maxs
		));
	}
	/*
	 * 营业数据报表
	 */
	public function actionBusinessdataReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = $db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		if($text==1){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc) k';
		}elseif($text==2){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc) k';
		}elseif($text==3){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc,day(create_at) asc) k';
		}
			//统计实付价格，客流、单数

		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$comName = $this->getComName();
		$this->render('businessdataReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
		));
	}
	/*
	 * 营业数据报表的退款查询
	*/
	public function getBusinessRetreat($dpid,$text,$y_all,$m_all,$d_all,$begin_time,$end_time){

		$db = Yii::app()->db;
		if($text==1){
			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'") where t.pay_amount < 0 and t.dpid='.$dpid;

		}elseif($text==2){
			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;
		}elseif($text==3){
			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" and day(t2.create_at) = "'.$d_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;
		}
		//var_dump($sql2);exit;
		$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		return $retreat['retreat_allprice'];
	}
	/*
	 * 支付方式报表的退款查询
	*/
	public function getPaymentRetreat($dpid,$begin_time,$end_time,$text,$y_all,$m_all,$d_all,$usertype,$userid){
		$begin_time = $begin_time.' 00:00:00';
		$end_time = $end_time.' 23:59:59';
		$db = Yii::app()->db;
		if($text==1){
			if($usertype != '0'){
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and t2.username = "'.$userid.'") where t.pay_amount < 0 and t.dpid='.$dpid;
			}else{
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'") where t.pay_amount < 0 and t.dpid='.$dpid;
			}
		}elseif($text==2){
			if($usertype != '0'){
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" and t2.username = "'.$userid.'") where t.pay_amount < 0 and t.dpid='.$dpid;
			}else{
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;
			}
		}elseif($text==3){
			if($usertype){
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" and day(t2.create_at) = "'.$d_all.'" and t2.username = "'.$userid.'") where t.pay_amount < 0 and t.dpid='.$dpid;
			}else{
				$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" and day(t2.create_at) = "'.$d_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;
			}
		}
		//var_dump($sql2);exit;
		$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		return $retreat['retreat_allprice'];
	}
	
	/*
	 * 日结统计报表的退款查询
	*/
	public function getRijieRetreat($dpid,$begin_time,$end_time,$text,$y_all,$m_all,$d_all,$usertype,$userid){
		$begin_time = $begin_time.' 00:00:00';
		$end_time = $end_time.' 23:59:59';
		$db = Yii::app()->db;
		
		if($usertype != '0'){
			$user = ' and t.username = "'.$userid.'" ';
		}else{
			$user = ' and t.username != -1';
		}
		if($text==1){
			$time = ' and year(t.create_at) = "'.$y_all.'" ';
		}elseif($text==2){
			$time = ' and year(t.create_at) = "'.$y_all.'" and month(t.create_at) ="'.$m_all.'" ';
		}elseif($text==3){
			$time = ' and year(t.create_at) = "'.$y_all.'" and month(t.create_at) ="'.$m_all.'" and day(t.create_at) ="'.$d_all.'" ';
		}
		$sql2 = 'select sum(t.pay_amount_total) as retreat_allprice,sum(t.pay_order_num) as retreat_num from nb_order_paytype_total t where t.delete_flag=0 and t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" '.$time.$user.' and t.pay_amount_total < 0 and t.dpid='.$dpid;
		//var_dump($sql2);exit;
		$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		return $retreat['retreat_allprice'];
	}
	/*
	 * 支付方式报表的退款查询
	*/
	public function getComPaymentRetreat($dpid,$begin_time,$end_time,$text,$y_all,$m_all,$d_all){
		$begin_time = $begin_time.' 00:00:00';
		$end_time = $end_time.' 23:59:59';
		$db = Yii::app()->db;
		if($text==1){

			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'") where t.pay_amount < 0 and t.dpid='.$dpid;

		}elseif($text==2){

			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;

		}elseif($text==3){

			$sql2 = 'select sum(t.pay_amount) as retreat_allprice,count(distinct t.order_id) as retreat_num from nb_order_pay t right join nb_order t2 on(t.dpid = t2.dpid and t.order_id = t2.lid and t2.create_at >="'.$begin_time.'" and t2.create_at <="'.$end_time.'" and year(t2.create_at) = "'.$y_all.'" and month(t2.create_at) = "'.$m_all.'" and day(t2.create_at) = "'.$d_all.'" ) where t.pay_amount < 0 and t.dpid='.$dpid;

		}
		//var_dump($sql2);exit;
		$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		return $retreat['retreat_allprice'];
	}
	public function actionAccountDetail(){

		$type = Yii::app()->request->getParam('type',"0");

		$orderid = Yii::app()->request->getParam('orderid',"0");
		$db = Yii::app()->db;
		if($type == 0){
			$sql = 'select sum(t.zhiamount*t.amount) as all_amount,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
		}else{
			//$sql = 'select sum(t.zhiamount*t.amount) as all_amount,count(t.zhiamount) as all_zhiamount,sum(t2.retreat_amount) as retreat_num,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t.lid = t2.order_detail_id) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
			$sql = 'select sum(t.amount) as all_amount,1 as all_zhiamount,sum(ifnull(t2.retreat_amount,t.amount)) as retreat_num, 0 as set_name,t.* '
					. ' from nb_order_product t '
					. ' left join nb_order_retreat t2 on(t.dpid = t2.dpid and t.lid = t2.order_detail_id) '
					. ' where t.set_id = 0 and t.order_id='.$orderid.' group by t.lid '
					. ' union select sum(t.zhiamount*t.amount) as all_amount,count(t.zhiamount) as all_zhiamount,sum(t2.retreat_amount) as retreat_num,t1.set_name,t.* '
					. ' from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) '
					. ' left join nb_order_retreat t2 on(t.dpid =t2.dpid and t.lid = t2.order_detail_id) '
					. ' where t.set_id >0 and t.order_id='.$orderid.' group by t.lid ';
		}//var_dump($sql);exit;
		$allmoney = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($allmoney);exit;
		$sql1 = 'select t.pay_amount from nb_order_pay t where t.paytype =11 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
		$model = Yii::app()->db->createCommand($sql1)->queryRow();
		$change = $model['pay_amount']?$model['pay_amount']:0;
		//var_dump($models);exit;
		$sql2 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.paytype in(0,11) and t.pay_amount >0 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
		$models = Yii::app()->db->createCommand($sql2)->queryRow();
		$money = $models['all_money']?$models['all_money']:0;

		$sql4 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.pay_amount <0 and t.dpid ='.$this->companyId.' and t.order_id ='.$orderid;
		$models = Yii::app()->db->createCommand($sql4)->queryRow();
		$retreat = $models['all_money']?$models['all_money']:0;

		$sql3 = 'select t1.name,t.* from nb_order_pay t left join nb_payment_method t1 on(t.dpid = t1.dpid and t.payment_method_id = t1.lid) where t.paytype not in (0,11) and t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.payment_method_id,t.paytype';
		$allpayment = Yii::app()->db->createCommand($sql3)->queryAll();
		if(empty($allpayment)){
			$allpayment = false;
		}
		Yii::app()->end(json_encode(array('status'=>true,'msg'=>$allmoney,'change'=>$change,'money'=>$money,'allpayment'=>$allpayment,'retreat'=>$retreat)));

	}

	/*
	 * 退菜明细报表
	 */
	public function actionRetreatdetailReport(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.* from(select t1.create_at as ordertime,t1.should_total,t1.reality_total,t1.username,sum(t.pay_amount) as pay_all,t.* from nb_order_pay t left join nb_order t1 on(t.dpid = t1.dpid and t1.lid = t.order_id) where t.paytype != 11 and t.pay_amount <0 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$str.') group by t.order_id)k';
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;


		$comName = $this->getComName();
		$this->render('retreatdetailReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
		));
	}
	public function actionRetreatdetailReport2(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t.create_at,t.amount,t.price,t.update_at,t.is_retreat,t.order_id,t.set_id,t.product_name from nb_order_product t left join nb_order t1 on(t.dpid = t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product t4 on(t.dpid = t4.dpid and t.product_id = t4.lid and t4.delete_flag = 0) where t.delete_flag = 0 and t.set_id = 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.')
				union select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t.create_at,t.amount,sum(t.price*t.amount*t2.retreat_amount/t.zhiamount) as price,t.update_at,t.is_retreat,t.order_id,t.set_id,t4.set_name as product_name from nb_order_product t left join nb_order t1 on(t.dpid = t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product_set t4 on(t.dpid = t4.dpid and t.set_id = t4.lid and t4.delete_flag = 0) where t.delete_flag = 0 and t.set_id > 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.') group by t.order_id,t.set_id
				) k where 1 order by k.create_at';
		//$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t4.product_name as product_name_p,t.* from nb_order_product t left join nb_order t1 on(t.dpid= t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product t4 on(t.dpid = t4.dpid and t.product_id = t4.lid and t4.delete_flag = 0)  where t.delete_flag = 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.')) k';
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;


		$comName = $this->getComName();
		$this->render('retreatdetailReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
		));
	}
	/*
	 * 台桌区域报表
	 *
	 */
	public function actionTableareaReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		//$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$db = Yii::app()->db;
		$sql = 'select k.* from(select sum(t.number) as all_number, count(distinct t.account_no) as all_account, sum(t2.pay_amount) as all_paymoney, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype != 11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) group by t1.type_id) k';//区域名称报表
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($model);exit;
		$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_money, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype != 11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';//区域名称报表
		$allmoney = Yii::app()->db->createCommand($sql)->queryRow();
		//echo $sql;exit;

		$comName = $this->getComName();
		$this->render('tableareaReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'allmoney'=>$allmoney,
		));
	}
	/*
	 * 退菜原因统计表
	 */

	public function actionRetreatreasonReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		if($text==1){
		$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.dpid in('.$this->companyId.') and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" group by year(t.create_at),t1.retreat_id) k';
		//echo $sql;exit;
		}elseif($text==2){
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.dpid in('.$this->companyId.') and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" group by month(t.create_at),t1.retreat_id) k';
			//echo $sql;exit;
		}elseif($text==3){
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.dpid in('.$this->companyId.') and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" group by day(t.create_at),t1.retreat_id) k';
			//echo $sql;exit;
		}
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;


		$comName = $this->getComName();
		$this->render('retreatreasonReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				//'money'=>$money,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/**
	 * 产品销售报表
	 *
	 **/
	public function actionCeshiproductReport(){
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		if($setid == 0){
			$setids = '=0';
		}elseif ($setid == 2){
			$setids = '>0';
		}else{
			$setids = '>=0';
		}
		$ordertype = Yii::app()->request->getParam('ordertype');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//echo ($ords);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.product_name,t.create_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,t.product_type,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount*(-(t.is_giving-1))) as all_price, sum(t.original_price*t.amount) as all_jiage';
		$criteria->with = array('company','product','order');

		$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2,8,9) and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.set_id '.$setids.' ';
		if($str){
			$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2,8,9) and t.delete_flag=0 and t.dpid in('.$str.')';
		}
		if($ordertype==1){
			$criteria->addCondition("order.order_type =0");
		}
		if($ordertype==2){
			$criteria->addCondition("order.order_type =1");
		}
		if($ordertype==3){
			$criteria->addCondition("order.order_type =2");
		}
		if($ordertype==4){
			$criteria->addCondition("order.order_type =3");
		}
		if($ordertype==5){
			$criteria->addCondition("t.set_id !=0");
		}
		//$criteria->addCondition("t.order_id in('.$ords.')");
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		$criteria->addCondition("t.order_id in(".$ords.")");

		if($text==1){
			$criteria->group =' t.product_type,t.product_id,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group =' t.product_type,t.product_id,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}else{
			$criteria->group =' t.product_type,t.product_id,day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}

		//$criteria->order = 't.create_at asc,t.dpid asc';

		$pages = new CPagination(OrderProduct::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();
		$comName = $this->getComName();

		$this->render('ceshiproductReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'setid'=>$setid,
				'comName'=>$comName,
				'ordertype'=>$ordertype,
				//'catId'=>$catId
		));
	}
	/**
	 * 套餐销售报表
	 *
	 **/
	public function actionCeshiproductsetReport(){
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		$db = Yii::app()->db;
		$setids = '>0';

		$ordertype = Yii::app()->request->getParam('ordertype');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//var_dump($catId);exit;
		//$criteria = new CDbCriteria;
		if($ordertype=='-1'){
			$ordertypes = '>=0';
		}else{
			$ordertypes = '='.$ordertype;
		}
		if($str){
			$strs = $str;
		}else{
			$strs = $this->companyId;
		}
		if($text==1){
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.y_all,k.set_id order by k.y_all,all_setnum desc,all_setprice desc)c';

		}elseif($text==2){
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.m_all,k.set_id order by k.y_all,k.m_all,all_setnum desc,all_setprice desc)c';

		}else{
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc)c';

		}
		//$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.zhiamount) as all_num,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid = '.$this->companyId.' group by t.order_id,t.set_id) k where 1 group by k.set_id )c';
		$count = $db->createCommand(str_replace('c.*','count(*)',$sql))->queryScalar();
		//echo $sql;exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$comName = $this->getComName();

		$this->render('ceshiproductsetReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'setid'=>$setid,
				'comName'=>$comName,
				'ordertype'=>$ordertype,
				//'catId'=>$catId
		));
	}

	/**
	 *
	 * 订单统计报表
	 *
	 * **/
	public function actionceshiOrderReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.reality_total) as all_total,count(t.order_status) as all_status,t.paytype,t.payment_method_id,t.order_status';
		$criteria->with = array('company','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.dpid,t.order_status,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.dpid,t.order_status,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,t.dpid asc';
		}else{
			$criteria->group ='t.dpid,t.order_status,day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,t.dpid asc';
		}
		//$criteria->order = 't.create_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';

		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
		$model = Order::model()->findAll($criteria);
		$comName = $this->getComName();
		$this->render('ceshiorderReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/**
	 *
	 * 订单统计报表
	 *
	 * **/
	public function actionOrderReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.reality_total) as all_total,count(t.order_status) as all_status,t.paytype,t.payment_method_id,t.order_status';
		$criteria->with = array('company','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.dpid,t.order_status,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.dpid,t.order_status,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}else{
			$criteria->group ='t.dpid,t.order_status,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}
		//$criteria->order = 't.update_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';

		$pages = new CPagination(Order::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
		$model = Order::model()->findAll($criteria);
		$comName = $this->getComName();
		$this->render('orderReport',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/**
	 *
	 * 就餐人数统计
	 *
	 */
	public function actionCuponReport(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$download = Yii::app()->request->getParam('d',0);
		$beginTime = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$endTime = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59" and is_used=0';
		$read = Yii::app()->db->createCommand($sql)->queryRow();
		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59" and is_used=1';
		$receive = Yii::app()->db->createCommand($sql)->queryRow();
		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59" and is_used=2';
		$used = Yii::app()->db->createCommand($sql)->queryRow();

		//$model = Yii::app()->db->createCommand($sql)->queryRow();
		$comName = $this->getComName();
		$this->render('cuponReport',array(
				//'model'=>$model,
				'begin_time'=>$beginTime,
				'end_time'=>$endTime,
				'comName'=>$comName,
				'str'=>$str,
				'read'=>$read,
				'receive'=>$receive,
				'used'=>$used,
		));
	}

	/**
	 *
	 * 就餐人数统计
	 *
	 */
	public function actionDiningNum(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$download = Yii::app()->request->getParam('d',0);
		$beginTime = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$endTime = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select sum(number) as total from nb_order where order_status in (3,4,8) and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59"';
		if($download){
			$model = Yii::app()->db->createCommand($sql)->queryRow();
			//var_dump($models);exit;
			$this->exportDiningNum($model);
			exit;
		}
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		$comName = $this->getComName();
		$this->render('diningNum',array(
				'model'=>$model,
				'begin_time'=>$beginTime,
				'end_time'=>$endTime,
				'comName'=>$comName,
				'str'=>$str,
		));
	}
	/**
	 *
	 * 员工营业额统计
	 *
	 */
	public function actionTurnOver(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$download = Yii::app()->request->getParam('d',0);
		$beginTime = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$endTime = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.* from (select t.username,sum(t.should_total) as total,t1.staff_no,t1.role from nb_order t left join nb_user t1 on(t1.dpid = t.dpid and t1.username = t.username ) where t.order_status in (3,4,8) and t.dpid in ('.$str.') and t.create_at >="'.$beginTime.' 00:00:00" and t.create_at <="'.$endTime.' 23:59:59" group by t.username order by t.lid desc)k';
		if($download){
			$models = $db->createCommand($sql)->queryAll();
			$this->exportTurnOver($models);
			exit;
		}
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$comName = $this->getComName();
		$this->render('turnover',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$beginTime,
				'end_time'=>$endTime,
				'comName'=>$comName,
				'str'=>$str,
		));
	}
	/**
	 *
	 * 送餐员营业额统计
	 *
	 */
	public function actionTakeaway(){
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$download = Yii::app()->request->getParam('d',0);
		$beginTime = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$endTime = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select t.* from (select t1.member_name,t1.cardId,sum(t.should_total) as total,count(t.lid) as sum from nb_order t left join nb_takeaway_member t1 on(t.callno = t1.lid and t.dpid = t1.dpid) where t.order_status in (3,4,8) and t.order_type in(2,4) and t.dpid in ('.$str.') and t.create_at >="'.$beginTime.' 00:00:00" and t.create_at <="'.$endTime.' 23:59:59" group by t.callno order by sum desc)t';
		if($download){
			$models = $db->createCommand($sql)->queryAll();
			$this->exportTurnOver($models);
			exit;
		}
		$count = $db->createCommand(str_replace('t.*','count(*)',$sql))->queryScalar();
		//var_dump($sql);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$comName = $this->getComName();
		$this->render('takeaway',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$beginTime,
				'end_time'=>$endTime,
				'comName'=>$comName,
				'str'=>$str,
		));
	}
	/*
	 * 账单详情报表
	 */
	public function actionOrderdetail(){
		$criteria = new CDbCriteria;
		$accountno = '';
		$otype = Yii::app()->request->getParam('otype','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.create_at >=0 and t.dpid= '.$this->companyId;
		$criteria->select = 'sum(t.number) as all_number,t.*';
		if($otype>=0){
			$criteria->addCondition("t.order_type= ".$otype);
		}
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.order_status in(3,4,8) ");//只要付款了的账单都进行统计
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		//$criteria->addCondition("t.dpid= ".$this->companyId);

		if(Yii::app()->request->isPostRequest){
			$accountno = Yii::app()->request->getPost('accountno1',0);
			if($accountno){
				$criteria->addSearchCondition('account_no',$accountno);
			}
		}
		$criteria->with = array("orderpay","channel");

		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
		$criteria->group = 't.account_no,t.order_status' ;
		$criteria->order = 't.lid ASC' ;
		$criteria->distinct = TRUE;
		$pages = new CPagination(Order::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);

		$model=  Order::model()->findAll($criteria);
		//var_dump($model);exit;
		$this->render('orderdetail',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'accountno'=>$accountno,
				'ordertype'=>$otype,
				'paymentid'=>1,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/*
	 * 账单支付方式
	*/
	public function actionOrderpaytype(){
		$criteria = new CDbCriteria;
		$accountno = '';
		$paymentid = Yii::app()->request->getParam('paymentid',1);
		$paytype = Yii::app()->request->getParam('paytype','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$criteria->select = 't.*,nb_micro_pay.transaction_id as transactionId';
		$criteria->with = array("paymentMethod","order4");
		$criteria->join='LEFT JOIN nb_micro_pay ON t.remark = nb_micro_pay.out_trade_no and t.dpid = nb_micro_pay.dpid';
		$criteria->addCondition("t.dpid= ".$this->companyId." and t.order_id in (".$ords.")");

		if($paymentid==1){
			$criteria->addCondition('t.paytype = '.$paytype);
		}elseif($paymentid==3){
			$criteria->addCondition('t.paytype = "3" and t.payment_method_id = "'.$paytype.'"');
		}else{
			$criteria->addCondition("t.paytype !='11' ");
		}
		//var_dump($criteria);
		//$criteria->addCondition("order4.order_status in(3,4,8) ");//只要付款了的账单都进行统计
		if(Yii::app()->request->isPostRequest){
			$accountno = Yii::app()->request->getPost('accountno1',0);
			if($accountno){
				$criteria->addSearchCondition('t.account_no',$accountno);
			}
		}else{
			$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
			$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		}

		//$criteria->group = 't.payment_method_id,t.paytype,t.pay_amount' ;
		$criteria->order = 't.order_id ASC,t.create_at ASC' ;
		//$criteria->distinct = TRUE;

		$pages = new CPagination(OrderPay::model()->count($criteria));
		//$pages->PageSize = 10;
		$pages->applyLimit($criteria);

		$model=  OrderPay::model()->findAll($criteria);
		//var_dump($model);
		//var_dump($paymentid,$paytype);exit;
		$payments = $this->getPayments($this->companyId);
		$this->render('orderpaytype',array(
				'models'=>$model,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'accountno'=>$accountno,
				'payments'=>$payments,
				'paymentid'=>$paymentid,
				'paytype'=>$paytype,
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
/*
 * 会员卡消费报表
 */
    public function actionMemberConsume(){
        $companyId = Yii::app()->request->getParam('companyId',"0000000000");
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $criteria = new CDbCriteria;
        $criteria->with = array("card");
        $criteria->addCondition("t.dpid= ".$companyId." and t.paytype = 4 ");
        $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
        $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
        $criteria->order = 't.order_id ASC,t.create_at ASC' ;
        $pages = new CPagination(OrderPay::model()->count($criteria));
        $pages->applyLimit($criteria);
        $models=  OrderPay::model()->findAll($criteria);
        $this->render('memberconsume',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,

		));
    }
/*
 * 渠道占比报表
 */
	public function actionChannelsproportion(){
		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$db = Yii::app()->db;
		$sql = 'select k.* from(select count(distinct t.account_no) as all_account ,count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id and t1.paytype != 11) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) k';
		//var_dump($sql);exit;

		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();

		$sql = 'select sum(j.all_amount) as all_payall from(select count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id and t1.paytype !=11) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) j';
		$connect = Yii::app()->db->createCommand($sql);
		$allpay = $connect->queryRow();
// 		$model=  Order::model()->findAll($criteria);
		//var_dump($model);exit;
		$this->render('channelsproportion',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'allpay'=>$allpay,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}


	public function getAccountMoney($account_no){
		$accountMoney = '';
		if($account_no){
		$sql = 'select sum(t.pay_amount) as all_zhifu,t.* from nb_order_pay t where t.paytype not in(9,10,11) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
		$connect = Yii::app()->db->createCommand($sql);
		$money = $connect->queryRow();
		$accountMoney = $money['all_zhifu'];
		}
		//$accountMoney = '';
		//$sql = 'update nb_order_product set original_price=(select t.original_price from nb_product t where t.delete_flag=0 and t.lid =nb_order_product.product_id ) where 1';
		return $accountMoney;
	}
	public function getOriginalMoney($account_no){
		$originalMoney = '';
		if($account_no){
			$sql = 'select sum(t.original_price*t.amount) as all_original from nb_order_product t  where t.is_retreat = 0 and t.product_order_status in(1,2) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
			$connect = Yii::app()->db->createCommand($sql);
			$money = $connect->queryRow();
			//var_dump($sql);exit;
			$originalMoney = $money['all_original'];
		}
		//$accountMoney = '';
		//$sql = 'update nb_order_product set original_price=(select t.original_price from nb_product t where t.delete_flag=0 and t.lid =nb_order_product.product_id ) where 1';
		return $originalMoney;
	}
	public function getSiteName($orderId){
		$sitename="";
		$sitetype="";

		$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid and t.dpid = t2.dpid and t.lid ='. $orderId;
		//$conn = Yii::app()->db->createCommand($sql);
		//$result = $conn->queryRow();
		//$siteId = $result['lid'];
		$connect = Yii::app()->db->createCommand($sql);
		//	$connect->bindValue(':site_id',$siteId);
		//	$connect->bindValue(':dpid',$dpid);
		$site = $connect->queryRow();
		$retsite="";
		if($site['site_id'] && $site['dpid'] ){
			//	echo 'ABC';
			$sitelevel = $site['site_level'];
			$sitename = $site['name'];
			$sitetype = $site['serial'];
			$retsite=$sitelevel.":".$sitename.":".$sitetype;
		}
		return $retsite;
	}


	/**
	 *
	 * 就餐人数
	 *
	 */
	private function exportDiningNum($model,$type=0,$orderStatus = 0,$params=array(),$export = 'xml'){
 		$attributes = array(
			'id'=>'编号',
			'total'=>'就餐人数',
		);
 		$data[1] = array_values($attributes);
 		$fields = array_keys($attributes);

		$arr = array();
		foreach($fields as $f){
			if($f == 'id'){
				$arr[] = 1;
			}else{
				$arr[] = $model[$f];
			}
		}
		$data[] = $arr;
 		Until::exportFile($data,$export,$fileName=date('Y_m_d_H_i_s'));
	}


	private function exportIncomeReport($models,$text,$params=array(),$export = 'xml'){
		if($text == 1){
		$attributes = array(

				'id'=>'序号',
				'y_all'=>'年',
				'company_name'=>'店铺',
			'category_name'=>'菜品二级分类',
 				'all_price'=>'金额',
 				'all_num'=>'单数',

		);
		}
		elseif ($text==2){
			$attributes = array(

					'id'=>'序号',
					'y_all'=>'年',
					'm_all'=>'月',
					'company_name'=>'店铺',
					'category_name'=>'菜品二级分类',
					'all_price'=>'金额',
					'all_num'=>'单数',

			);
		}
		elseif ($text==3){
			$attributes = array(

					'id'=>'序号',
					'y_all'=>'年',
					'm_all'=>'月',
					'd_all'=>'日',
					'company_name'=>'店铺',
					'category_name'=>'菜品二级分类',
					'all_price'=>'金额',
					'all_num'=>'单数',
			);
		}
		$data[1] = array_values($attributes);
		$fields = array_keys($attributes);

	foreach($models as $k=>$model){
			$arr = array();
			foreach($fields as $f){
				if($f == 'id'){
					$arr[] = $k+1;
				}else{
					$arr[] = $model[$f];
				}
			}
			$data[] = $arr;
		}
		//$data[] = $arr;
		Until::exportFile($data,$export,$fileName=date('Y_m_d_H_i_s'));
	}
	
	/**
	 *
	 * 员工营业额
	 *
	 */
	private function exportTurnOver($models,$type=0,$orderStatus = 0,$params=array(),$export = 'xml'){
 		$attributes = array(
			'id'=>'编号',
			'username'=>'员工名',
			'total'=>'营业额',
		);
 		$data[1] = array_values($attributes);
 		$fields = array_keys($attributes);

		foreach($models as $k=>$model){
			$arr = array();
			foreach($fields as $f){
				if($f == 'id'){
					$arr[] = $k+1;
				}else{
					$arr[] = $model[$f];
				}
			}
			$data[] = $arr;
		}
 		Until::exportFile($data,$export,$fileName=date('Y_m_d_H_i_s'));
	}

	public function getPaymentName($paymentMethodId){
		$name='';
		$sql = 'select t.name from nb_payment_method t where t.delete_flag = 0 and t.lid='.$paymentMethodId;
		$connect = Yii::app()->db->createCommand($sql);
		$model = $connect->queryRow();
		if($model['name']){
			$name = $model['name'];
		}

		//var_dump($name);exit;
		return $name;
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
	public function getUserstaffno($dpid,$username){
		$name='XX';
		$sql = 'select t.staff_no from nb_user t where t.delete_flag = 0 and t.dpid='.$dpid.' and t.username="'.$username.'"';
		$connect = Yii::app()->db->createCommand($sql);
		$model = $connect->queryRow();
		if(!empty($model)){
			return $model['staff_no'];
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
	public function getPayment($dpid){
		$sql = 'select t.lid,t.dpid,t.name from nb_payment_method t where t.delete_flag = 0 and t.dpid ='.$dpid;
		$connect = Yii::app()->db->createCommand($sql);
		$models = $connect->queryAll();
		return $models;
	}
 		public function getCatName($CategoryId){
		$Catname = "";
		$sql = 'select t.lid,t.category_name from nb_product_category t where t.lid='.$CategoryId;
 		$connect = Yii::app()->db->createCommand($sql);
 		$model = $connect->queryRow();
 		if($model['category_name']){
 			$Catname = $model['category_name'];
 		}
		return $Catname;
	}


	public function getOrderDetails($orderId){
	    //$sql = 'select t1.product_id from nb_order t, nb_order_product t1, nb_product t2 where t.lid = t1.order_id and t.dpid = t1.dpid ';
		$sql = 'select t2.product_name  from nb_order_product t1, nb_product t2 where t1.dpid = t2.dpid and t1.product_id = t2.lid and t1.order_id='.$orderId;
		//$sql = 'select t1.product_id, t2.product_name from nb_order t, nb_order_product t1, nb_product t2 where t.lid = t1.order_id and t.dpid = t1.dpid = t2.dpid and t1.product_id = t2.lid';

 		$connect = Yii::app()->db->createCommand($sql);
// 		//	$connect->bindValue(':site_id',$siteId);
// 		//	$connect->bindValue(':dpid',$dpid);
 		$name = $connect->queryAll();
 		//var_dump($name);exit;
 		$ret="";
 		foreach($name as $key=>$val){
 			$ret.=$val['product_name']."/";
 		}
		echo $ret;
	}


	public function actionProductsalesExport(){
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select ='year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.update_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount) as all_price';
		$criteria->with = array('company','product');

		$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid='.$this->companyId;
		if($str){
			$criteria->condition = 't.is_retreat=0 and t.product_order_status=1 and t.delete_flag=0 and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");

		if($text==1){
		$criteria->group ='t.product_id,year(t.update_at)';
		$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.product_id,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}else{
			$criteria->group ='t.product_id,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}

	//	$criteria->order = 't.update_at asc';

		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1','产品销售报表')
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','店铺名称')
		->setCellValue('C3','单品名称')
		->setCellValue('D3','售出数量')
		->setCellValue('E3','总销售额')
		->setCellValue('F3','备注');
		$j=4;
		foreach($models as $v){
			//print_r($v);
			if ($text==1){

				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v->y_all)
				->setCellValue('B'.$j,$v->company->company_name)
				->setCellValue('C'.$j,$v->product->product_name)
				->setCellValue('D'.$j,$v->all_total)
				->setCellValue('E'.$j,$v->all_price)
				->setCellValue('F'.$j);
				}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
				->setCellValue('B'.$j,$v->company->company_name)
				->setCellValue('C'.$j,$v->product->product_name)
				->setCellValue('D'.$j,$v->all_total)
				->setCellValue('E'.$j,$v->all_price)
				->setCellValue('F'.$j);
				}elseif ($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
				->setCellValue('B'.$j,$v->company->company_name)
				->setCellValue('C'.$j,$v->product->product_name)
				->setCellValue('D'.$j,$v->all_total)
				->setCellValue('E'.$j,$v->all_price)
				->setCellValue('F'.$j);

				}
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="产品销售报表.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}
		public function actionOrderExport(){
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.reality_total) as all_total,count(t.order_status) as all_status,t.paytype,t.payment_method_id,t.order_status';
		$criteria->with = array('company','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.dpid,t.order_status,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.dpid,t.order_status,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}elseif ($text==3){
			$criteria->group ='t.dpid,t.order_status,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}

		//$criteria->group = 't.paytype,t.payment_method_id';
		//var_dump($criteria);exit;
		$model = Order::model()->findAll($criteria);
			//var_dump($model);exit;

			//设置第1行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			//设置第2行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
			$styleArray3 = array(
					'font' => array(
							'bold' => true,
							'color'=>array(
									'rgb' => '000000',
							),
							'size' => '12',
					),
					'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
							'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
					),
			);

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',yii::t('app','订单统计报表'))
			->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
			->setCellValue('A3',yii::t('app','时间'))
			->setCellValue('B3',yii::t('app','店铺名称'))
			->setCellValue('C3',yii::t('app','订单状态'))
			->setCellValue('D3',yii::t('app','数量统计'))
			->setCellValue('E3',yii::t('app','金额统计'))
			->setCellValue('F3',yii::t('app','备注'));
			$j=4;
			foreach($model as $v){
				//print_r($v);
				if ($text==1){
					switch ($v->order_status){
						case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','未下单'))
						->setCellValue('D'.$j,$v->all_status)
						->setCellValue('E'.$j,$v->all_total)
						->setCellValue('F'.$j);
						break;
						case 2:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','下单未支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 3:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','已支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 4:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app','已结单'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 5:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被并台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 6:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被换台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 7:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被撤台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 8:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','日结'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						default:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app',''))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
					}

				}elseif ($text==2){
				switch ($v->order_status){
						case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','未下单'))
						->setCellValue('D'.$j,$v->all_status)
						->setCellValue('E'.$j,$v->all_total)
						->setCellValue('F'.$j);
						break;
						case 2:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','下单未支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 3:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','已支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 4:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app','已结单'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 5:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被并台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 6:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被换台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 7:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app','被撤台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 8:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','日结'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						default:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app',''))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
					}
				}elseif ($text==3){
				switch ($v->order_status){
						case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','未下单'))
						->setCellValue('D'.$j,$v->all_status)
						->setCellValue('E'.$j,$v->all_total)
						->setCellValue('F'.$j);
						break;
						case 2:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','下单未支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 3:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','已支付'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 4:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app','已结单'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 5:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被并台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 6:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被换台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 7:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','被撤台'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						case 8:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','日结'))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
						default:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j, yii::t('app',''))
							->setCellValue('D'.$j,$v->all_status)
							->setCellValue('E'.$j,$v->all_total)
							->setCellValue('F'.$j);
						break;
					}

				}
				//设置填充颜色
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
				//设置字体靠左
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

				$j++;
			}
			//冻结窗格
			$objPHPExcel->getActiveSheet()->freezePane('A4');
			//合并单元格
			$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
			$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
			//单元格加粗，居中：

			// 将A1单元格设置为加粗，居中
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//加粗字体
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
			//设置字体垂直居中
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			//设置字体水平居中
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//字体靠左
			//$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
			//设置每列宽度
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

			//输出
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$filename="订单统计报表.xls";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
		}


		//导出营业额报表
		public function actionSalesExport(){
			$objPHPExcel = new PHPExcel();
			$str = Yii::app()->request->getParam('str');
			$text = Yii::app()->request->getParam('text');
			$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
			$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

			$db = Yii::app()->db;
			if($text==1){
				if($str){
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$str.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}else{
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}
			}elseif ($text==2){
				if($str){
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$str.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}else{
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}
			}elseif ($text==3){
				if($str){
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$str.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}else{
					$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id,t1.company_name from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" group by t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
					$money = Yii::app()->db->createCommand($sql)->queryAll();
				}
			}

			$criteria = new CDbCriteria;
			$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id';
			$criteria->with = array('company','order8','paymentMethod');
			$criteria->condition = 't.paytype !=4 and t.dpid='.$this->companyId ;
			if($str){
				$criteria->condition = 't.paytype !=4 and t.dpid in('.$str.')';
			}
			$criteria->addCondition("order8.update_at >='$begin_time 00:00:00'");
			$criteria->addCondition("order8.update_at <='$end_time 23:59:59'");
			if($text==1){
				$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.update_at)';
				$criteria->order = 'year(t.update_at) asc,t.dpid asc';
			}elseif($text==2){
				$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.update_at)';
				$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
			}elseif($text==3){
				$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.update_at)';
				$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
			}
			$model = OrderPay::model()->findAll($criteria);
			//设置第1行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			//设置第2行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
// 			//大边框样式 边框加粗
// 			$lineBORDER = array(
// 					'borders' => array(
// 							'outline' => array(
// 									'style' => PHPExcel_Style_Border::BORDER_THICK,
// 									'color' => array('argb' => '000000'),
// 							),
// 					),
// 			);
			//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
			//细边框样式
// 			$linestyle = array(
// 					'borders' => array(
// 							'outline' => array(
// 									'style' => PHPExcel_Style_Border::BORDER_THIN,
// 									'color' => array('argb' => 'FF000000'),
// 							),
// 					),
// 			);

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',yii::t('app','营业额报表'))
			->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
			->setCellValue('A3',yii::t('app','时间'))
			->setCellValue('B3',yii::t('app','店铺名称'))
			->setCellValue('C3',yii::t('app','支付方式'))
			->setCellValue('D3',yii::t('app','金额统计'))
			->setCellValue('E3',yii::t('app','备注'));
			$j=4;
			foreach($model as $v){
				//print_r($v);
				if ($text==1){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);

				}}elseif ($text==2){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
				}}elseif ($text==3){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
					break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_reality)
						->setCellValue('E'.$j);
				}
				}
				//设置填充颜色
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
				//设置字体靠左
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				//细边框样式引用
				//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
			$j++;
		}
		foreach ($money as $w){
			//print_r($w);
			if($text==1){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$w['y_all'])
				->setCellValue('B'.$j,$w['company_name'])
				->setCellValue('C'.$j,yii::t('app','会员卡支付'))
				->setCellValue('D'.$j,$w['all_huiyuan'])
				->setCellValue('E'.$j);
			}
			elseif($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$w['y_all'].'-'.$w['m_all'])
				->setCellValue('B'.$j,$w['company_name'])
				->setCellValue('C'.$j,yii::t('app','会员卡支付'))
				->setCellValue('D'.$j,$w['all_huiyuan'])
				->setCellValue('E'.$j);
			}
			elseif($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$w['y_all'].'-'.$w['m_all'].'-'.$w['d_all'])
				->setCellValue('B'.$j,$w['company_name'])
				->setCellValue('C'.$j,yii::t('app','会员卡支付'))
				->setCellValue('D'.$j,$w['all_huiyuan'])
				->setCellValue('E'.$j);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('5ef130');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//大边框样式引用
		//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		//单元格加粗，居中：

		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);


		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="营业额报表.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}

	//导出营业额报表
	public function actionCgExport(){
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.update_at asc';
		//var_dump($sql);exit;
		$db = Yii::app()->db;
		if ($text==1) {
				if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==2){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==3){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
			}
		$model = Yii::app()->db->createCommand($sql)->queryAll();
			//var_dump($model);exit;
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1',yii::t('app','产品分类营业额报表'))
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','店铺名称'))
		->setCellValue('C3',yii::t('app','产品分类'))
		->setCellValue('D3',yii::t('app','金额统计'))
		->setCellValue('E3',yii::t('app','备注'));
		$j=4;
		foreach($model as $v){
			//var_dump($v);exit;
			//print_r($v);exit;
			if ($text==1){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$j,$v['y_all'])
					->setCellValue('B'.$j,$v['company_name'])
					->setCellValue('C'.$j,$v['category_name'])
					->setCellValue('D'.$j,$v['all_price'])
					->setCellValue('E'.$j);
				}elseif ($text==2){
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'])
						->setCellValue('B'.$j,$v['company_name'])
						->setCellValue('C'.$j,$v['category_name'])
						->setCellValue('D'.$j,$v['all_price'])
						->setCellValue('E'.$j);
					}elseif ($text==3){
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
							->setCellValue('B'.$j,$v['company_name'])
							->setCellValue('C'.$j,$v['category_name'])
							->setCellValue('D'.$j,$v['all_price'])
							->setCellValue('E'.$j);
					}
					//设置填充颜色
					$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
					//设置字体靠左
					//$objPHPExcel->getActiveSheet()->getStyle('A3'.$j.':E3'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':C'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					//$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					//细边框样式引用
					//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
					$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="产品分类营业额报表.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}


	public function actionIncomeExport(){
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		if($setid == 0){
			$setids = '=0';
			$setname = '单品、';
		}elseif ($setid == 2){
			$setids = '>0';
			$setname = '套餐、';
		}else{
			$setids = '>=0';
			$setname = '综合、';
		}
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$comName = $this->getComName();
		$db = Yii::app()->db;

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = $db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

							where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
									group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

							where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
									group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name

						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)

						where t.delete_flag = 0 and t.is_retreat in(1,0) and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.' and t4.lid in('.$ords.')
								group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($model);exit;
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
		// 			//大边框样式 边框加粗
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
		->setCellValue('A1',yii::t('app','营业收入报表（产品分类）'))
		->setCellValue('A2',yii::t('app','查询条件：').$setname.yii::t('app','时间段：').$begin_time.yii::t('app','00:00:00 至 ').$end_time."23:59:59    ".yii::t('app','生成时间：').date('m-d H:i',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','店铺名称'))
		->setCellValue('C3',yii::t('app','产品分类'))
		->setCellValue('D3',yii::t('app','单数'))
		->setCellValue('E3',yii::t('app','金额统计'))
		->setCellValue('F3',yii::t('app','备注(退款)'));
		$j=4;
		foreach($model as $v){
			//var_dump($v);exit;
			//print_r($v);exit;
			if ($text==1){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v['y_all'])
				->setCellValue('B'.$j,$v['company_name'])
				->setCellValue('C'.$j,$v['category_name'])
				->setCellValue('D'.$j,$v['all_num'])
				->setCellValue('E'.$j,$v['all_price']-$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']))
				->setCellValue('F'.$j,$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']));
			}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'])
				->setCellValue('B'.$j,$v['company_name'])
				->setCellValue('C'.$j,$v['category_name'])
				->setCellValue('D'.$j,$v['all_num'])
				->setCellValue('E'.$j,$v['all_price']-$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']))
				->setCellValue('F'.$j,$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']));
			}elseif ($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
				->setCellValue('B'.$j,$v['company_name'])
				->setCellValue('C'.$j,$v['category_name'])
				->setCellValue('D'.$j,$v['all_num'])
				->setCellValue('E'.$j,$v['all_price']-$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']))
				->setCellValue('F'.$j,$this->getRetreatPrice($begin_time,$end_time,$str,$text,$v['y_all'],$v['m_all'],$v['d_all'],$setid,$v['category_id']));
			}
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A2:F'.$j)->applyFromArray($linestyle);//细边框样式引用
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			//细边框样式引用
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
			$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//大边框样式引用
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$j)->applyFromArray($lineBORDER);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="营业收入报表（产品分类）（".date('m-d H:i',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}
	//导出支付方式员工营业额的报表
	public function actionPaymentExport(){
		date_default_timezone_set('PRC');
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d ',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d ',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,t.username,sum(orderpay.pay_amount) as all_reality,orderpay.paytype,orderpay.payment_method_id,count(*) as all_num,count(distinct orderpay.order_id) as all_nums';//array_count_values()
		$criteria->with = array('company','orderpay');
		$criteria->condition = 'orderpay.paytype != "11" and t.dpid='.$this->companyId.' and t.order_status in (3,4,8)' ;
		$criteria->addCondition('t.lid in('.$ords.')');
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		if($userid != '0' && $userid != '-1'){
			$criteria->addCondition ('t.username ="'.$userid.'"');
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==2){
			if($userid != '0' ){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}elseif($text==3){
			if($userid != '0'){
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at),t.username';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}else{
				$criteria->group ='t.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(orderpay.pay_amount) desc,t.dpid asc';
			}
		}
		$model = Order::model()->findAll($criteria);
	   	//var_dump($model);exit;
	   	$payments = $this->getPayment($this->companyId);
	   	$username = $this->getUsername($this->companyId);
	    $comName = $this->getComName();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
		->setCellValue('A1',yii::t('app','支付方式（员工营业额）报表'))
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59   ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','总单数'))
		->setCellValue('C3',yii::t('app','毛利润'))
		->setCellValue('D3',yii::t('app','优惠'))
		->setCellValue('E3',yii::t('app','实收款'))
		->setCellValue('F3',yii::t('app','营业员'))
		->setCellValue('G3',yii::t('app','现金'))
		->setCellValue('H3',yii::t('app','微信'))
		->setCellValue('I3',yii::t('app','支付宝'))
		->setCellValue('J3',yii::t('app','银联'))
		->setCellValue('K3',yii::t('app','会员卡'))
		->setCellValue('L3',yii::t('app','微点单'))
		->setCellValue('M3',yii::t('app','微外卖'))
		->setCellValue('N3',yii::t('app','系统券'))
		->setCellValue('O3',yii::t('app','积分'))
		->setCellValue('P3',yii::t('app','微信余额'));
		$letternext= 'Q';
		if($payments){
			$let = '0';
			$letter='';

			foreach ($payments as $payment){
				$paymentname = $payment['name'];
				$let++;
				switch ($let){
					case 1: $letter = 'Q3';$letternext = 'R';break;
					case 2: $letter = 'R3';$letternext = 'S';break;
					case 3: $letter = 'S3';$letternext = 'T';break;
					case 4: $letter = 'T3';$letternext = 'U';break;
					case 5: $letter = 'U3';$letternext = 'V';break;
					case 6: $letter = 'V3';$letternext = 'W';break;
					case 7: $letter = 'W3';$letternext = 'X';break;
					case 8: $letter = 'X3';$letternext = 'Y';break;
					case 9: $letter = 'Y3';$letternext = 'Z';break;
					default:break;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter,yii::t('app',$paymentname));
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternext.'3',yii::t('app','退款'));
		$j=4;
		foreach($model as $v){
			//print_r($v);

			if ($text==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v->y_all);
			}elseif($text==2){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all);
			}
			if($userid !='0'){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j,$v->username.'('.$this->getUserstaffno($this->companyId,$v->username).')');
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j);
			}
			$retreats = $this->getPaymentRetreat($v->dpid,$begin_time,$end_time,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username);
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$j,$v->all_nums)
				->setCellValue('C'.$j,$reality_all = $this->getGrossProfit($v->dpid,$begin_time,$end_time,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('D'.$j,sprintf("%.2f",$reality_all-$v->all_reality+$retreats))
				->setCellValue('E'.$j,$v->all_reality)
				->setCellValue('G'.$j,$cash = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,0,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('H'.$j,$wechat = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,1,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('I'.$j,$alipay = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,2,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('J'.$j,$unionpay = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,5,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('K'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,4,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('L'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,12,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('M'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,13,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('N'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,9,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('O'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,8,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username))
				->setCellValue('P'.$j,$vipcard = $this->getPaymentPrice($v->dpid,$begin_time,$end_time,0,10,$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username));
				$letters='';
				$letternexts= 'Q';
				if($payments){
					$let = '0';


					foreach ($payments as $payment){
						$paymentname = $payment['name'];
						$let++;
						switch ($let){
							case 1: $letters = 'Q';$letternexts = 'R';break;
							case 2: $letters = 'R';$letternexts = 'S';break;
							case 3: $letters = 'S';$letternexts = 'T';break;
							case 4: $letters = 'T';$letternexts = 'U';break;
							case 5: $letters = 'U';$letternexts = 'V';break;
							case 6: $letters = 'V';$letternexts = 'W';break;
							case 7: $letters = 'W';$letternexts = 'X';break;
							case 8: $letters = 'X';$letternexts = 'Y';break;
							case 9: $letters = 'Y';$letternexts = 'Z';break;
							default:break;
						}
						$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letters.$j,$pay_item =  $this->getPaymentPrice($v->dpid,$begin_time,$end_time,3,$payment['lid'],$text,$v->y_all,$v->m_all,$v->d_all,$userid,$v->username));
					}
					$objPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.$letters.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternexts.$j,$retreats);
					//细边框引用
					$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->applyFromArray($linestyle);
					$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->applyFromArray($linestyle);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.$letternext.$j)->applyFromArray($linestyle);
					//设置填充颜色
					$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
					//设置字体靠左、靠右
					$objPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.$letternext.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					//细边框样式引用
					//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
					$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.$j)->applyFromArray($lineBORDER);
		//大边框样式引用
		//$objPHPExcel->getActiveSheet()->getStyle('A2:E'.$j)->applyFromArray($linestyle);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$letternext.'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$letternext.'2');
		//单元格加粗，居中：

		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="支付方式（员工营业额）报表（".date('y年m月d日  H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}

	//导出支付方式员工营业额的报表
	public function actionPaymentSqlExport(){
		date_default_timezone_set('PRC');
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$userid = Yii::app()->request->getParam('userid');
		$begin_time = Yii::app()->request->getParam('begin_time','');
		$end_time = Yii::app()->request->getParam('end_time','');
		$dpname = Yii::app()->request->getParam('dpname','');

		if(empty($begin_time) && Yii::app()->user->role >=11){
			$begin_time = date('Y-m-d',time());
		}
		if(empty($end_time) && Yii::app()->user->role >=11){
			$end_time = date('Y-m-d',time());
		}

		if($text==1){
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at)';
				$useros = 't.dpid,year(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at)';
				$usernames = ' != -1';
			}
		}elseif($text == 2){
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at)';
				$useros = 't.dpid,year(t.create_at),month(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at)';
				$usernames = ' != -1';
			}
		}else{
			if($userid != '0'){
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),day(oo.create_at),oo.username';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),day(t.create_at),t.username';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),day(ot.create_at),ot.username';
				$usernames = ' = t.username';
			}else{
				$users ='oo.dpid,year(oo.create_at),month(oo.create_at),day(oo.create_at)';
				$useros = 't.dpid,year(t.create_at),month(t.create_at),day(t.create_at)';
				$userots = 'ot.dpid,year(ot.create_at),month(ot.create_at),day(ot.create_at)';
				$usernames = ' != -1';
			}
		}

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';

		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all, '
				.' t.dpid,t.username,t.create_at '
				.' ,op.all_reality ,o.all_should, '
				.' op.all_nums,o.all_num,op0.all_cash,op1.all_wxpay,op2.all_alipay,op3.all_htpay,op4.all_member,op5.all_bankpay,op8.all_point,op9.all_cupon,op10.all_wxmember,op12.all_wxdd,op13.all_wxwm '
				.' from nb_order t '
				.' left join ('
					.' select sum(top.pay_amount) as all_reality,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype !=11 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op on(t.dpid = op.dpid and op.username '.$usernames.' and year(t.create_at) = op.y_oo and month(t.create_at) = op.m_oo and day(t.create_at) = op.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cash,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =0 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op0 on(t.dpid = op0.dpid and op0.username '.$usernames.' and year(t.create_at) = op0.y_oo and month(t.create_at) = op0.m_oo and day(t.create_at) = op0.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =1 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op1 on(t.dpid = op1.dpid and op1.username '.$usernames.' and year(t.create_at) = op1.y_oo and month(t.create_at) = op1.m_oo and day(t.create_at) = op1.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_alipay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =2 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op2 on(t.dpid = op2.dpid and op2.username '.$usernames.' and year(t.create_at) = op2.y_oo and month(t.create_at) = op2.m_oo and day(t.create_at) = op2.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_htpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =3 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op3 on(t.dpid = op3.dpid and op3.username '.$usernames.' and year(t.create_at) = op3.y_oo and month(t.create_at) = op3.m_oo and day(t.create_at) = op3.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_member,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =4 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op4 on(t.dpid = op4.dpid and op4.username '.$usernames.' and year(t.create_at) = op4.y_oo and month(t.create_at) = op4.m_oo and day(t.create_at) = op4.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_bankpay,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =5 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op5 on(t.dpid = op5.dpid and op5.username '.$usernames.' and year(t.create_at) = op5.y_oo and month(t.create_at) = op5.m_oo and day(t.create_at) = op5.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_point,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =8 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op8 on(t.dpid = op8.dpid and op8.username '.$usernames.' and year(t.create_at) = op8.y_oo and month(t.create_at) = op8.m_oo and day(t.create_at) = op8.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_cupon,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =9 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op9 on(t.dpid = op9.dpid and op9.username '.$usernames.' and year(t.create_at) = op9.y_oo and month(t.create_at) = op9.m_oo and day(t.create_at) = op9.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxmember,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =10 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op10 on(t.dpid = op10.dpid and op10.username '.$usernames.' and year(t.create_at) = op10.y_oo and month(t.create_at) = op10.m_oo and day(t.create_at) = op10.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxdd,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =12 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op12 on(t.dpid = op12.dpid and op12.username '.$usernames.' and year(t.create_at) = op12.y_oo and month(t.create_at) = op12.m_oo and day(t.create_at) = op12.d_oo) '
				.' left join ('
					.' select sum(top.pay_amount) as all_wxwm,count(distinct top.order_id) as all_nums,top.dpid,oo.dpid as gdpid,oo.create_at,oo.username,year(oo.create_at) as y_oo,month(oo.create_at) as m_oo,day(oo.create_at) as d_oo '
					.' from nb_order_pay top '
							.'left join nb_order oo on(oo.lid = top.order_id and oo.dpid = top.dpid)'
					.' where top.paytype =13 and top.order_id in('.$ords.') and top.create_at >="'.$begin_time.' 00:00:00" and top.create_at <="'.$end_time.' 23:59:59"'
					.' group by '.$users
				.' ) op13 on(t.dpid = op13.dpid and op13.username '.$usernames.' and year(t.create_at) = op13.y_oo and month(t.create_at) = op13.m_oo and day(t.create_at) = op13.d_oo) '
				.' left join ('
				.' select sum(ot.reality_total) as all_should,count(distinct ot.lid) as all_num,ot.create_at,ot.dpid,ot.username,year(ot.create_at) as y_ot,month(ot.create_at) as m_ot,day(ot.create_at) as d_ot '
				.' from nb_order ot '
				.' where ot.order_status in(3,4,8) and ot.lid in('.$ords.') and ot.create_at >="'.$begin_time.' 00:00:00" and ot.create_at <="'.$end_time.' 23:59:59"'
				.' group by '.$userots
				.' ) o on(t.dpid = o.dpid and o.username '.$usernames.' and year(t.create_at) = o.y_ot and month(t.create_at) = o.m_ot and day(t.create_at) = o.d_ot)'

				.' where '
				.' op.all_reality is not null and '
				.' t.order_status in(3,4,8) and t.lid in('.$ords.') and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and '
				.' t.dpid ='.$this->companyId
				.' group by '.$useros;
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($models);exit;
		$payments = $this->getPayment($this->companyId);
		$username = $this->getUsername($this->companyId);
		$comName = $this->getComName();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
		->setCellValue('A1',yii::t('app','支付方式（员工营业额）报表'))
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59   ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','总单数'))
		->setCellValue('C3',yii::t('app','毛利润'))
		->setCellValue('D3',yii::t('app','优惠'))
		->setCellValue('E3',yii::t('app','实收款'))
		->setCellValue('F3',yii::t('app','营业员'))
		->setCellValue('G3',yii::t('app','现金'))
		->setCellValue('H3',yii::t('app','微信'))
		->setCellValue('I3',yii::t('app','支付宝'))
		->setCellValue('J3',yii::t('app','银联'))
		->setCellValue('K3',yii::t('app','会员卡'))
		->setCellValue('L3',yii::t('app','微点单'))
		->setCellValue('M3',yii::t('app','微外卖'))
		->setCellValue('N3',yii::t('app','系统券'))
		->setCellValue('O3',yii::t('app','积分'))
		->setCellValue('P3',yii::t('app','微信余额'));
		$letternext= 'Q';
		if($payments){
			$let = '0';
			$letter='';

			foreach ($payments as $payment){
				$paymentname = $payment['name'];
				$let++;
				switch ($let){
					case 1: $letter = 'Q3';$letternext = 'R';break;
					case 2: $letter = 'R3';$letternext = 'S';break;
					case 3: $letter = 'S3';$letternext = 'T';break;
					case 4: $letter = 'T3';$letternext = 'U';break;
					case 5: $letter = 'U3';$letternext = 'V';break;
					case 6: $letter = 'V3';$letternext = 'W';break;
					case 7: $letter = 'W3';$letternext = 'X';break;
					case 8: $letter = 'X3';$letternext = 'Y';break;
					case 9: $letter = 'Y3';$letternext = 'Z';break;
					default:break;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter,yii::t('app',$paymentname));
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternext.'3',yii::t('app','退款'));
		$j=4;
		foreach($models as $v){
			//print_r($v);

			if ($text==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all']);
			}elseif($text==2){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all']);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all']);
			}
			if($userid !='0'){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j,$v['username'].'('.$this->getUserstaffno($this->companyId,$v['username']).')');
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j);
			}
			$retreats = $this->getPaymentRetreat($v['dpid'],$begin_time,$end_time,$text,$v['y_all'],$v['m_all'],$v['d_all'],$userid,$v['username']);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$j,$v['all_nums'])
			->setCellValue('C'.$j,$reality_all = $v['all_should'])
			->setCellValue('D'.$j,sprintf("%.2f",$reality_all-$v['all_reality']+$retreats))
			->setCellValue('E'.$j,$v['all_reality'])
			->setCellValue('G'.$j,$cash = $v['all_cash'])
			->setCellValue('H'.$j,$wechat = $v['all_wxpay'])
			->setCellValue('I'.$j,$alipay = $v['all_alipay'])
			->setCellValue('J'.$j,$unionpay = $v['all_bankpay'])
			->setCellValue('K'.$j,$vipcard = $v['all_member'])
			->setCellValue('L'.$j,$wxorderpay = $v['all_wxdd'])
			->setCellValue('M'.$j,$wxwaimaipay = $v['all_wxwm'])
			->setCellValue('N'.$j,$wxcard = $v['all_cupon'])
			->setCellValue('O'.$j,$wxpoint = $v['all_point'])
			->setCellValue('P'.$j,$wxcharge = $v['all_wxmember']);
			$letters='';
			$letternexts= 'Q';
			if($payments){
				$let = '0';


				foreach ($payments as $payment){
					$paymentname = $payment['name'];
					$let++;
					switch ($let){
						case 1: $letters = 'Q';$letternexts = 'R';break;
						case 2: $letters = 'R';$letternexts = 'S';break;
						case 3: $letters = 'S';$letternexts = 'T';break;
						case 4: $letters = 'T';$letternexts = 'U';break;
						case 5: $letters = 'U';$letternexts = 'V';break;
						case 6: $letters = 'V';$letternexts = 'W';break;
						case 7: $letters = 'W';$letternexts = 'X';break;
						case 8: $letters = 'X';$letternexts = 'Y';break;
						case 9: $letters = 'Y';$letternexts = 'Z';break;
						default:break;
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letters.$j,$pay_item =  $this->getPaymentPrice($v['dpid'],$begin_time,$end_time,3,$payment['lid'],$text,$v['y_all'],$v['m_all'],$v['d_all'],$userid,$v['username']));
				}
				$objPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.$letters.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternexts.$j,$retreats);
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.$letternext.$j)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左、靠右
			$objPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.$letternext.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//细边框样式引用
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
			$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.$j)->applyFromArray($lineBORDER);
		//大边框样式引用
		//$objPHPExcel->getActiveSheet()->getStyle('A2:E'.$j)->applyFromArray($linestyle);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$letternext.'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$letternext.'2');
		//单元格加粗，居中：

		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="支付方式（员工营业额）报表（".date('y年m月d日  H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}
	//导出支付方式的报表
	public function actionPayallExport(){
		$objPHPExcel = new PHPExcel();
	$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));


		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order8','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==3){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}
			//$criteria->order = 't.create_at asc';
			//var_dump($criteria);exit;
			$model = OrderPay::model()->findAll($criteria);
			//print_r($model);exit;
			//设置第1行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
			//设置第2行的行高
			$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
			->setCellValue('A1',yii::t('app','收款统计（支付方式）报表'))
			->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app','00:00:00 至 ').$end_time." 23:59:59   ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
			->setCellValue('A3',yii::t('app','时间'))
			->setCellValue('B3',yii::t('app','店铺名称'))
			->setCellValue('C3',yii::t('app','支付方式'))
			->setCellValue('D3',yii::t('app','单数'))
			->setCellValue('E3',yii::t('app','金额统计'))
			->setCellValue('F3',yii::t('app','备注'));
			$j=4;
			foreach($model as $v){
				//print_r($v);
				if ($text==1){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 4:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','会员卡支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 6:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 7:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 8:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 9:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信代金券'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 10:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信会员余额'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',""))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);

				}}elseif ($text==2){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 4:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','会员卡支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 6:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 7:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 8:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 9:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信代金券'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 10:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信会员余额'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',""))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
				}}elseif ($text==3){
					switch($v->paytype) {
					case 0:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','现金支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 1:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 2:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','支付宝支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 3:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,$v->paymentMethod->name)
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 4:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','会员卡支付'))
						->setCellValue('D'.$j,$v->all_num)
					->setCellValue('E'.$j,$v->all_reality)
					->setCellValue('F'.$j);
					break;
					case 5:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','银联卡支付'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
					break;
					case 6:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 7:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 8:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',''))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 9:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信代金券'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					case 10:
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app','微信会员余额'))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
						break;
					default :
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',""))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
				}
				}
				//细边框引用
				$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
				$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':F'.$j)->applyFromArray($linestyle);
				//$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
				//单元格高度自适应
				//$objPHPExcel->getActiveSheet()->getDefaultRowDimension('A'.$i.':N'.$j)->setRowHeight(-1);
				//单元格换行
				//            $objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setWrapText(true);
				//            $objPHPExcel->getActiveSheet()->getStyle('H'.$j.':I'.$j)->getAlignment()->setWrapText(true);
				//长度不够显示的时候换行
				//$objPHPExcel->getActiveSheet()->getStyle('B'.$j)->getAlignment()->setShrinkToFit(true);
				//$objPHPExcel->getActiveSheet()->getStyle('H'.$j.':I'.$j)->getAlignment()->setWrapText(true);
				//使用数组定义L列的样式
				//$objPHPExcel->getActiveSheet()->getStyle('L'.$j)->applyFromArray($styleArray2);
				//设置字体垂直居中
				//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':M'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				//设置字体水平居中
				//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				//$objPHPExcel->getActiveSheet()->getStyle('J'.$i.':M'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				//设置填充颜色
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
				//设置字体靠左、靠右
				$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				//$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('N'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				//$objPHPExcel->getActiveSheet()->setCellValueExplicit("D".$j,PHPExcel_Cell_DataType::TYPE_NUMERIC);
				//细边框样式引用
				//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
			$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$j)->applyFromArray($lineBORDER);
		//大边框样式引用
		//$objPHPExcel->getActiveSheet()->getStyle('A2:E'.$j)->applyFromArray($linestyle);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：

		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);


		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="收款统计（支付方式）报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}
	/*
	 *
	 * 产品销售报表
	 *
	 */

	public function actionCeshiproductReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		if($setid == 0){
			$setids = '=0';
			$setname = '单品、';
		}elseif ($setid == 2){
			$setids = '>0';
			$setname = '套餐单品、';
		}else{
			$setids = '>=0';
			$setname = '综合、';
		}
		$ordertype = Yii::app()->request->getParam('ordertype');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//echo ($ords);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.product_name,t.create_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,t.product_type,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount*(-(t.is_giving-1))) as all_price, sum(t.original_price*t.amount) as all_jiage';
		$criteria->with = array('company','product','order');
		
		$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2,8,9) and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.set_id '.$setids.' ';
		if($str){
			$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2,8,9) and t.delete_flag=0 and t.dpid in('.$str.')';
		}
		if($ordertype==1){
			$criteria->addCondition("order.order_type =0");
		}
		if($ordertype==2){
			$criteria->addCondition("order.order_type =1");
		}
		if($ordertype==3){
			$criteria->addCondition("order.order_type =2");
		}
		if($ordertype==4){
			$criteria->addCondition("order.order_type =3");
		}
		if($ordertype==5){
			$criteria->addCondition("t.set_id !=0");
		}
		//$criteria->addCondition("t.order_id in('.$ords.')");
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		$criteria->addCondition("t.order_id in(".$ords.")");
		
		if($text==1){
			$criteria->group =' t.product_type,t.product_id,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group =' t.product_type,t.product_id,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}else{
			$criteria->group =' t.product_type,t.product_id,day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','产品销售报表')
		->setCellValue('A2',yii::t('app','查询条件：').$setname.yii::t('app','时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59    ".yii::t('app','生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','店铺名称')
		->setCellValue('C3','单品名称')
		->setCellValue('D3','排名')
		->setCellValue('E3','销量')
		->setCellValue('F3','销售金额')
		->setCellValue('G3','折扣金额')
		->setCellValue('H3','实收金额')
		->setCellValue('I3','原始均价')
		->setCellValue('J3','折后均价');
		$i=4;
		foreach($models as $v){
			//print_r($v);exit;
			if($v->product_type !=2) { $name = $v->product_name;}else {$name = '打包费';}
			if ($text==1){

				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v->y_all)
				->setCellValue('B'.$i,$v->company->company_name)
				->setCellValue('C'.$i,$name)
				->setCellValue('D'.$i,$i-3)
				->setCellValue('E'.$i,$v->all_total)
				->setCellValue('F'.$i,$v->all_jiage)
				->setCellValue('G'.$i,$v->all_jiage-$v->all_price)
				->setCellValue('H'.$i,$v->all_price)
				->setCellValue('I'.$i,$v->all_jiage/$v->all_total)
				->setCellValue('J'.$i,$v->all_price/$v->all_total);
			}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v->y_all.'-'.$v->m_all)
				->setCellValue('B'.$i,$v->company->company_name)
				->setCellValue('C'.$i,$name)
				->setCellValue('D'.$i,$i-3)
				->setCellValue('E'.$i,$v->all_total)
				->setCellValue('F'.$i,$v->all_jiage)
				->setCellValue('G'.$i,$v->all_jiage-$v->all_price)
				->setCellValue('H'.$i,$v->all_price)
				->setCellValue('I'.$i,$v->all_jiage/$v->all_total)
				->setCellValue('J'.$i,$v->all_price/$v->all_total);
			}elseif ($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
				->setCellValue('B'.$i,$v->company->company_name)
				->setCellValue('C'.$i,$name)
				->setCellValue('D'.$i,$i-3)
				->setCellValue('E'.$i,$v->all_total)
				->setCellValue('F'.$i,$v->all_jiage)
				->setCellValue('G'.$i,$v->all_jiage-$v->all_price)
				->setCellValue('H'.$i,$v->all_price)
				->setCellValue('I'.$i,$v->all_jiage/$v->all_total)
				->setCellValue('J'.$i,$v->all_price/$v->all_total);

			}
			$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':J'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:J'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="产品销售报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}


	//套餐销售报表
	public function actionCeshiproductsetReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		//var_dump($str);exit();
		$text = Yii::app()->request->getParam('text');
		$setid = Yii::app()->request->getParam('setid');
		$db = Yii::app()->db;
		$setids = '>0';

		$ordertype = Yii::app()->request->getParam('ordertype');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		if($ordertype=='-1'){
			$ordertypes = '>=0';
		}else{
			$ordertypes = '='.$ordertype;
		}
		if($str){
			$strs = $str;
		}else{
			$strs = $this->companyId;
		}
		if($text==1){   //年
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.y_all,k.set_id order by k.y_all,all_setnum desc,all_setprice desc)c';

		}elseif($text==2){ //月
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.m_all,k.set_id order by k.y_all,k.m_all,all_setnum desc,all_setprice desc)c';

		}else{
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') and t.order_id in('.$ords.') group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc)c';

		}
		$models =  $db->createCommand($sql)->queryAll();
		// $models = OrderProduct::model()->findAllBySql($sql);
		//  exit();
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','套餐销售报表')
		->setCellValue('A2',yii::t('app','查询条件：').yii::t('app','时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59    ".yii::t('app','生成时间：').date('m-d H:i',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','店铺名称')
		->setCellValue('C3','套餐名称')
		->setCellValue('D3','排名')
		->setCellValue('E3','销量')
		->setCellValue('F3','销售金额')
		->setCellValue('G3','折扣金额')
		->setCellValue('H3','实收金额')
		->setCellValue('I3','原始均价')
		->setCellValue('J3','折后均价');
		$i=4;
		foreach($models as $v){
			//print_r($v);
			if ($text==1){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all']);
			}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all'].-$v['m_all']);
			}elseif ($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all'].-$v['m_all'].-$v['d_all']);
			}
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$i,$v['company_name'])
			->setCellValue('C'.$i,$v['set_name'])
			->setCellValue('D'.$i,$i-3)
			->setCellValue('E'.$i,$v['all_setnum'])
			->setCellValue('F'.$i,$v['all_orisetprice'])
			->setCellValue('G'.$i,$v['all_orisetprice']-$v['all_setprice'])
			->setCellValue('H'.$i,$v['all_setprice'])
			->setCellValue('I'.$i,$v['all_orisetprice']/$v['all_setnum'])
			->setCellValue('J'.$i,$v['all_setprice']/$v['all_setnum']);

			$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':J'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:J'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="产品销售报表（".date('m月d日 H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}
	/*
	 *
	 * 营业数据报表
	 *
	 */

	public function actionBusinessdataReportExport(){
		date_default_timezone_set('PRC');
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}
		//$criteria->addCondition('t.lid in('.$ords.')');

		$db = Yii::app()->db;
		if($text==1){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc) k';
		}elseif($text==2){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc) k';
		}elseif($text==3){
			$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc,day(create_at) asc) k';
		}
			//统计实付价格，客流、单数
		$models = Yii::app()->db->createCommand($sql)->queryAll();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','营业数据报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','客流')
		->setCellValue('C3','单数')
		->setCellValue('D3','销售额')
		->setCellValue('E3','实收')
		->setCellValue('F3','优惠')
		->setCellValue('G3','人均')
		->setCellValue('H3','单均');
		$i=4;
		foreach($models as $v){
			if($text==1){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all']);
			}elseif($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all']);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all']);
			}
			$retreatnum = $this->getBusinessRetreat($this->companyId,$text,$v['y_all'],$v['m_all'],$v['d_all'],$begin_time,$end_time);
			$retreatnum = $retreatnum?$retreatnum:'0.00';

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$i,$v['all_number'])
			->setCellValue('C'.$i,$v['all_account'])
			->setCellValue('D'.$i,sprintf("%.2f",$v['all_originalprice']))
			->setCellValue('E'.$i,sprintf("%.2f",$v['all_realprice']+$retreatnum).'('.$retreatnum.')')
			->setCellValue('F'.$i,sprintf("%.2f",$v['all_originalprice']-$v['all_realprice']));
			if($v['all_number']){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,sprintf("%.2f",($v['all_realprice']+$retreatnum)/$v['all_number']));
			}else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i,sprintf("%.2f",$model['all_realprice']));
			}
			if($v['all_account']){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,sprintf("%.2f",($v['all_realprice']+$retreatnum)/$v['all_number']));
			}else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i,sprintf("%.2f",$model['all_realprice']));
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$i);


			$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:H'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(22);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="营业数据报表（".date('m-d H:i',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}

	/*
	 *
	 * 账单详情报表
	 *
	 */
	public function actionOrderdetailExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$criteria = new CDbCriteria;
		$otype = Yii::app()->request->getParam('otype','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.create_at >=0 and t.dpid= '.$this->companyId;
		$criteria->select = 'sum(t.number) as all_number,t.*';
		if($otype>=0){
			$criteria->addCondition("t.order_type= ".$otype);
		}
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.order_status in(3,4,8) ");//只要付款了的账单都进行统计
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");

		if(Yii::app()->request->isPostRequest){
			$accountno = Yii::app()->request->getPost('accountno1',0);
			if($accountno){
				$criteria->addSearchCondition('account_no',$accountno);
			}
		}
		//$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->with = array("company","paymentMethod","channel");

		$criteria->group = 't.account_no,t.order_status' ;
		$criteria->order = 't.lid ASC' ;
		$criteria->distinct = TRUE;

		$model=  Order::model()->findAll($criteria);


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','账单详情报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','账单号')
		->setCellValue('B3','下单时间')
		->setCellValue('C3','人数')
		->setCellValue('D3','原价')
		->setCellValue('E3','优惠')
		->setCellValue('F3','实收')
		->setCellValue('G3','现金收款')
		->setCellValue('H3','找零')
		->setCellValue('I3','状态');
		$i=4;
		foreach($model as $v){
			if($v->is_temp=='1'){
				$n ='';
				if($v->order_type == 4){
					$n = $v->channel->channel_name;
				}else{
					switch ($v->order_type){
						case 0: $n ='堂食';break;
						case 1: $n ='微信堂食';break;
						case 2: $n ='微信外卖';break;
						case 3: $n ='堂食';break;
						case 5: $n ='自助点单';break;
						case 6: $n ='微信点单';break;
						case 7: $n ='美团外卖';break;
						case 8: $n ='饿了么外卖';break;
						default: $n ='其他';break;
					}
				}
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v->account_no,PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v->create_at)
				->setCellValue('C'.$i,$v->all_number)
				->setCellValue('D'.$i,sprintf("%.2f",$v->reality_total))
				->setCellValue('E'.$i,sprintf("%.2f",$v->reality_total-$v->should_total))
				->setCellValue('F'.$i,$v->should_total)
				->setCellValue('G'.$i,sprintf("%.2f",OrderProduct::getMoney($this->companyId,$v->lid)))
				->setCellValue('H'.$i,sprintf("%.2f",OrderProduct::getChange($this->companyId,$v->lid)))
				->setCellValue('I'.$i,$n);
			}
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':I'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="账单详情报表（".date('m月d日 H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');

	}

	/*
	 *
	* 账单支付方式报表
	*
	*/
	public function actionOrderpaytypeExport(){
		$objPHPExcel = new PHPExcel();
		$criteria = new CDbCriteria;
		$accountno = '';
		$paymentid = Yii::app()->request->getParam('paymentid',1);
		$paytype = Yii::app()->request->getParam('paytype','-1');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}

		$criteria->select = 't.*,nb_micro_pay.transaction_id as transactionId';
		$criteria->with = array("paymentMethod","order4");
		$criteria->join='LEFT JOIN nb_micro_pay ON t.remark = nb_micro_pay.out_trade_no and t.dpid = nb_micro_pay.dpid';
		$criteria->addCondition("t.dpid= ".$this->companyId." and t.order_id in (".$ords.")");

		if($paymentid==1){
			$criteria->addCondition('t.paytype = '.$paytype);
		}elseif($paymentid==3){
			$criteria->addCondition('t.paytype = "3" and t.payment_method_id = "'.$paytype.'"');
		}else{
			$criteria->addCondition("t.paytype !='11' ");
		}
		if(Yii::app()->request->isPostRequest){
			$accountno = Yii::app()->request->getPost('accountno1',0);
			if($accountno){
				$criteria->addSearchCondition('t.account_no',$accountno);
			}
		}else{
			$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
			$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		}
		//$criteria->group = 't.payment_method_id,t.paytype,t.pay_amount' ;
		$criteria->order = 't.order_id ASC,t.create_at ASC' ;
		//$criteria->distinct = TRUE;
		$model=  OrderPay::model()->findAll($criteria);


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','账单支付方式报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','账单号');
		if($paymentid=='1' && ($paytype == '1' || $paytype == '2')){
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B3','第三方账单号')
				->setCellValue('C3','下单时间')
				->setCellValue('D3','支付方式')
				->setCellValue('E3','金额')
				->setCellValue('F3','');
		}else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B3','下单时间')
			->setCellValue('C3','支付方式')
			->setCellValue('D3','金额')
			->setCellValue('E3','');
		}
		$i=4;
		foreach($model as $v){
			 if($v->paytype==3){
				$paytypename = $v->paymentMethod->name;
				}else switch($v->paytype){
					case 0: $paytypename = '现金';break;
					case 1: $paytypename = '微信';break;
					case 2: $paytypename = '支付宝';break;
					case 4: $paytypename = '会员卡';break;
					case 5: $paytypename = '银联';break;
					case 9: $paytypename = '微信代金券';break;
					case 10: $paytypename = '微信余额';break;
					case 11: $paytypename = '找零';break;
				} ;
				if($v->pay_amount<0) $paytypename = $paytypename.'(退款)';
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v->account_no,PHPExcel_Cell_DataType::TYPE_STRING);
				if($paymentid=='1' && ($paytype == '1' || $paytype == '2')){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueExplicit('B'.$i,$v->transactionId,PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('C'.$i,$v->create_at)
					->setCellValue('D'.$i,$paytypename)
					->setCellValue('E'.$i,sprintf("%.2f",$v->pay_amount))
					->setCellValue('F'.$i,'');
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B'.$i,$v->create_at)
					->setCellValue('C'.$i,$paytypename)
					->setCellValue('D'.$i,sprintf("%.2f",$v->pay_amount))
					->setCellValue('E'.$i,'');
				}

			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="账单支付方式报表（".date('m月d日 H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	 * 渠道占比报表
	 *
	 */

	public function actionChannelsproportionExport(){
		$objPHPExcel = new PHPExcel();
		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$db = Yii::app()->db;
		$sql = 'select k.* from(select count(distinct t.account_no) as all_account ,count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id and t1.paytype != 11) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) k';
        $models =  $db->createCommand($sql)->queryAll();
		$sql = 'select sum(j.all_amount) as all_payall from(select count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id and t1.paytype !=11) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) j';
		$connect = Yii::app()->db->createCommand($sql);
		$allpay = $connect->queryRow();
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
		->setCellValue('A1','渠道占比报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','渠道名称')
		->setCellValue('B3','单数')
		->setCellValue('C3','单均')
		->setCellValue('D3','金额')
		->setCellValue('E3','占比(%)')
                ->setCellValue('F3','备注');
		$i=4;
		foreach($models as $v){

                     $destion_string = '';
			switch($v['order_type']){
				case 0:
                     $destion_string = yii::t('app','到店堂食');
					break;
				case 1:
					$destion_string = yii::t('app','微信堂食');
					break;
				case 2:
					$destion_string =yii::t('app','微信外卖');
					break;
				case 3:
					$destion_string = yii::t('app','微信预约');
					break;
				case 4:
					$destion_string =yii::t('app','后台外卖');
					break;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i,$destion_string)
                        ->setCellValue('B'.$i,$v['all_account'])
                        ->setCellValue('C'.$i,$v['all_amount']/$v['all_account'])
                        ->setCellValue('D'.$i,$v['all_amount'])
                        ->setCellValue('E'.$i,sprintf("%.2f",$v['all_amount']*100/$allpay['all_payall']).'%')
                        ->setCellValue('F'.$i);



			$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体靠左

		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//A2字体水平居中

		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//A2字体垂直居中

		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                //设置填充颜色

		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);

		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);





		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="渠道占比报表（".date('m月d日 H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}


	/*
	 *
	 * 退菜明细报表
	 *
	 */
	public function actionRetreatdetailReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.* from(select t1.create_at as ordertime,t1.should_total,t1.reality_total,t1.username,sum(t.pay_amount) as pay_all,t.* from nb_order_pay t left join nb_order t1 on(t.dpid = t1.dpid and t1.lid = t.order_id) where t.paytype != 11 and t.pay_amount <0 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$str.') group by t.order_id)k';
		//echo $sql;exit;
		$models = Yii::app()->db->createCommand($sql)->queryAll();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','退菜明细报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','账单号')
		->setCellValue('B3','下单时间')
		->setCellValue('C3','总价')
		->setCellValue('D3','优惠')
		->setCellValue('E3','实付')
		->setCellValue('F3','退款')
		->setCellValue('G3','退款时间')
		->setCellValue('H3','退款员')
		->setCellValue('I3','退款原因')
		->setCellValue('J3','');
		$i=4;
		foreach($models as $v){

				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v['account_no'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v['ordertime'])
				->setCellValue('C'.$i,sprintf("%.2f",$v['reality_total']))
				->setCellValue('D'.$i,sprintf("%.2f",$v['reality_total']-$v['should_total']))
				->setCellValue('E'.$i,sprintf("%.2f",$v['should_total']))
				->setCellValue('F'.$i,$v['pay_all'])
				->setCellValue('G'.$i,$v['create_at'])
				->setCellValueExplicit('H'.$i,$v['username'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('I'.$i)
				->setCellValue('J'.$i);

			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:I2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(21);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(21);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="退菜明细报表（".date('m月d日  H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 退菜原因统计报表
	*
	*/
	public function actionRetreatreasonReportExport(){
		$objPHPExcel = new PHPExcel();
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		if($text==1){
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.') group by year(t.create_at),t1.retreat_id';
		//echo $sql;exit;
		}elseif($text==2){
			$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.') group by month(t.create_at),t1.retreat_id';
			//echo $sql;exit;
		}elseif($text==3){
			$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.retreat_id,t1.order_detail_id,t1.retreat_amount,t2.name,t.lid,t.is_retreat,t.price,t.is_print,sum(t.price*t1.retreat_amount) as all_retreatprice,count(t1.retreat_id) as all_num,sum(t1.retreat_amount) as all_amount from nb_order_product t left join nb_order_retreat t1 on(t.dpid = t1.dpid and t.lid = t1.order_detail_id and t1.delete_flag = 0) left join nb_retreat t2 on(t.dpid = t2.dpid and t1.retreat_id = t2.lid and t2.delete_flag = 0) where t.delete_flag = 0 and t.is_retreat = 1 and t.is_print = 1 and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.') group by day(t.create_at),t1.retreat_id';
			//echo $sql;exit;
		}

		$models = Yii::app()->db->createCommand($sql)->queryAll();


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','退菜原因统计报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3','时间')
		->setCellValue('B3','退菜原因')
		->setCellValue('C3','次数')
		->setCellValue('D3','退量')
		->setCellValue('E3','金额')
		->setCellValue('F3','');
		$i=4;
		foreach($models as $v){
		if($text==1){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueExplicit('A'.$i,$v['y_all'])
			->setCellValue('B'.$i,$v['name'])
			->setCellValue('C'.$i,$v['all_num'])
			->setCellValue('D'.$i,$v['all_amount'])
			->setCellValue('E'.$i,$v['all_retreatprice'])
			->setCellValue('F'.$i);
		}elseif ($text==2){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueExplicit('A'.$i,$v['y_all']."-".$v['m_all'])
			->setCellValue('B'.$i,$v['name'])
			->setCellValue('C'.$i,$v['all_num'])
			->setCellValue('D'.$i,$v['all_amount'])
			->setCellValue('E'.$i,$v['all_retreatprice'])
			->setCellValue('F'.$i);
		}elseif ($text==3){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValueExplicit('A'.$i,$v['y_all']."-".$v['m_all']."-".$v['d_all'])
			->setCellValue('B'.$i,$v['name'])
			->setCellValue('C'.$i,$v['all_num'])
			->setCellValue('D'.$i,$v['all_amount'])
			->setCellValue('E'.$i,$v['all_retreatprice'])
			->setCellValue('F'.$i);
		}
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="退菜原因统计报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 充值记录报表
	*
	*/
	public function actionRechargeReportExport(){
		$objPHPExcel = new PHPExcel();
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = Yii::app()->request->getParam('text');
		$money = "";
		$recharge = "";
		$db = Yii::app()->db;
		if($text==1){
			$sql = 'select k.* from(select t1.selfcode,t1.name,t.reality_money,t.give_money from nb_member_recharge t left join nb_member_card t1 on(t.member_card_id = t1.selfcode || t.member_card_id = t1.rfid and t1.delete_flag = 0) where t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.')) k';
			//var_dump($sql);exit;
		}
		//传统卡充值
		//$sql = 'select sum(t.reality_money) as all_money from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		//$money = Yii::app()->db->createCommand($sql)->queryRow();
		//var_dump($models);exit;
		if($text==2){
			$sql = 'select k.* from(select t1.card_id,t1.user_name,t.recharge_money,t.cashback_num from nb_recharge_record t left join nb_brand_user t1 on(t.brand_user_lid = t1.lid and t.dpid = t1.dpid ) where t.delete_flag = 0 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.')) k';
			//var_dump($sql);exit;
		}
		if($text==3){
			//$money = "0";
			//传统卡充值
			$sql = 'select k.* from(select sum(t.reality_money) as all_money,sum(t.give_money) as all_give from nb_member_recharge t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ) k';
			$money = Yii::app()->db->createCommand($sql)->queryRow();
			//微信会员卡充值
			$sql = 'select k.* from(select sum(t.recharge_money) as all_recharge,sum(t.cashback_num) as all_cashback from nb_recharge_record t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59") k ';
			$recharge = Yii::app()->db->createCommand($sql)->queryRow();

		}

		$models = Yii::app()->db->createCommand($sql)->queryAll();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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


		$objPHPExcel->setActiveSheetIndex(0);

		if($text==1){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',yii::t('app','充值记录报表'))
			->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
			->setCellValue('A3',yii::t('app','传统卡号'))
			->setCellValue('B3',yii::t('app','名称'))
			->setCellValue('C3',yii::t('app','充值金额'))
			->setCellValue('D3',yii::t('app','返现'))
			->setCellValue('E3','');
			$i=4;
		foreach($models as $v){

				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['selfcode'])
				->setCellValue('B'.$i,$v['name'])
				->setCellValue('C'.$i,$v['reality_money'])
				->setCellValue('D'.$i,$v['give_money'])
				->setCellValue('E'.$i);

			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		}elseif($text==2){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1','充值记录报表')
			->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
			->setCellValue('A3','会员卡号')
			->setCellValue('B3','名称')
			->setCellValue('C3','充值金额')
			->setCellValue('D3','返现')
			->setCellValue('E3','');
			$i=4;
		foreach($models as $v){

				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v['card_id'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v['user_name'])
				->setCellValue('C'.$i,$v['recharge_money'])
				->setCellValue('D'.$i,$v['cashback_num'])
				->setCellValue('E'.$i);

			$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':D'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		}elseif($text==3){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1',yii::t('app','充值记录报表'))
			->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
			->setCellValue('A3',yii::t('app','充值卡类型'))
			->setCellValue('B3',yii::t('app','充值金额'))
			->setCellValue('C3',yii::t('app','返现'))
			->setCellValue('D3','')
			->setCellValue('A4',yii::t('app','传统卡'))
			->setCellValue('B4',$money["all_money"])
			->setCellValue('C4',$money['all_give'])
			->setCellValue('D4')
			->setCellValue('A5',yii::t('app','会员卡'))
			->setCellValue('B5',$recharge["all_recharge"])
			->setCellValue('C5',$recharge["all_cashback"])
			->setCellValue('D5');
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->getStartColor()->setARGB('fae9e5');
			$objPHPExcel->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->setARGB('fae9e5');
			$objPHPExcel->getActiveSheet()->getStyle('B4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('B5')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('C4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('C5')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$i++;
			$i=5;
		}

		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="充值记录报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}


	/*
	 *
	* 员工营业额报表
	*
	*/
	public function actionTurnoverReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);

		$download = Yii::app()->request->getParam('d',0);
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select username,sum(reality_total) as total from nb_order where order_status in (3,4,8) and dpid in ('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" group by username order by lid desc';

		$models = Yii::app()->db->createCommand($sql)->queryAll();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','员工营业额报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','序号')
		->setCellValue('B3','店员名')
		->setCellValue('C3','营业额')
		->setCellValue('D3','');
		$i=4;
		foreach($models as $v){

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i,$i-3)
			->setCellValue('B'.$i,$v['username'])
			->setCellValue('C'.$i,$v['total']?$v['total']:0)
			->setCellValue('D'.$i);

			//细边框引用

			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="员工营业额报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 送餐员报表
	*
	*/
	public function actionTakeawayReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);

		$download = Yii::app()->request->getParam('d',0);
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select t1.member_name,t1.cardId,sum(t.reality_total) as total,count(t.lid) as sum from nb_order t left join nb_takeaway_member t1 on(t.callno = t1.lid and t.dpid = t1.dpid) where t.order_status in (3,4,8) and t.order_type in(2,4) and t.dpid in ('.$str.') and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" group by t.callno order by sum desc';
		$models = Yii::app()->db->createCommand($sql)->queryAll();


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','送餐员报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','序号')
		->setCellValue('B3','外卖员')
		->setCellValue('C3','营业额')
		->setCellValue('D3','单数');
		$i=4;
		foreach($models as $v){

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i,$i-3)
			->setCellValue('B'.$i,$v['member_name'].'('.$v['cardId'].')')
			->setCellValue('C'.$i,$v['total']?$v['total']:0)
			->setCellValue('D'.$i,$v['sum']?$v['sum']:0);

			//细边框引用

			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="送餐员报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 时段报表
	*
	*/
	public function actionTimedataReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$db = Yii::app()->db;
		$sql = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
		$orders = $db->createCommand($sql)->queryAll();
		$ords ='0000000000';
		foreach ($orders as $order){
			$ords = $ords .','.$order['lid'];
		}


		$sql = 'select k.* from(select DATE_FORMAT(create_at,"%H") as h_all,sum(pay_amount) as pay_amount,count(distinct order_id) as all_account from nb_order_pay where  order_id in('.$ords.') and paytype !=11 and dpid in('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" group by h_all) k';
		$models = $db->createCommand($sql)->queryAll();
		

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','时段报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','序号')
		->setCellValue('B3','时段')
		->setCellValue('C3','单数')
		->setCellValue('D3','营业额');
		$i=4;
		foreach($models as $v){

			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i,$i-3)
			->setCellValue('B'.$i,$v['h_all'])
			->setCellValue('C'.$i,$v['all_account']?$v['all_account']:0)
			->setCellValue('D'.$i,$v['pay_amount']?$v['pay_amount']:0);

			//细边框引用

			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="时段报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}


	/*
	 *
	* 就餐人数统计报表
	*
	*/
	public function actionDiningrReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);
		//$download = Yii::app()->request->getParam('d',0);
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select sum(number) as total from nb_order where order_status in (3,4,8) and dpid in ('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59"';

		$model = Yii::app()->db->createCommand($sql)->queryRow();


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','就餐人数统计报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
		->setCellValue('A3','序号')
		->setCellValue('B3','就餐人数')
		->setCellValue('C3','')
			->setCellValue('A4',1)
			->setCellValue('B4',$model['total']?$model['total']:0)
			->setCellValue('C4');

			//细边框引用

			$objPHPExcel->getActiveSheet()->getStyle('A2:B4')->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('fae9e5');
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('fae9e5');
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);

		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:B4')->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="就餐人数统计报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 台桌区域报表
	*
	*/

	public function actionTableareaReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		//$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$db = Yii::app()->db;
		$sql = 'select k.* from(select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_paymoney, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype !=11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) group by t1.type_id) k';//区域名称报表
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($model);exit;
		$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_money, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid and t2.paytype !=11) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';//区域名称报表
		$allmoney = Yii::app()->db->createCommand($sql)->queryRow();
		//$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();

		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1','台桌区域报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('y-m-d h:i',time()))
		->setCellValue('A3','区域名称')
		->setCellValue('B3','客流')
		->setCellValue('C3','单数')
		->setCellValue('D3','金额统计')
		->setCellValue('E3','占比(%)');
		$i=4;
		foreach($models as $v){

					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,$v['name'])
					->setCellValue('B'.$i,$v['all_number'])
					->setCellValue('C'.$i,$v['all_account'])
					->setCellValue('D'.$i,$v['all_paymoney'])
					->setCellValue('E'.$i,$v['all_paymoney']*100/$allmoney['all_money']);


			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="台桌区域报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}

	/*
	 *
	* 代金券使用情况报表
	*
	*/
	public function actionCuponReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str',$this->companyId);
		$download = Yii::app()->request->getParam('d',0);
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" and is_used=0';
		$read = Yii::app()->db->createCommand($sql)->queryRow();
		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" and is_used=1';
		$receive = Yii::app()->db->createCommand($sql)->queryRow();
		$sql = 'select count(is_used) as all_cupon from nb_cupon_branduser where delete_flag =0 and dpid in ('.$str.') and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" and is_used=2';
		$used = Yii::app()->db->createCommand($sql)->queryRow();


		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
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
		->setCellValue('A1',yii::t('app','代金券使用情况报表'))
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
		->setCellValue('A3',yii::t('app','序号'))
		->setCellValue('B3',yii::t('app','类型'))
		->setCellValue('C3',yii::t('app','数量'))
		->setCellValue('D3',yii::t('app','占比(%)'))
		->setCellValue('A4',1)
		->setCellValue('B4',yii::t('app',"发送数量"))
		->setCellValue('C4',$read['all_cupon']+$receive['all_cupon']+$used['all_cupon'])
		->setCellValue('D4',yii::t('app','100'))
		->setCellValue('A5',2)
		->setCellValue('B5',yii::t('app',"领取数量"))
		->setCellValue('C5',$receive['all_cupon']?$receive['all_cupon']:0)
		->setCellValue('D5',$receive['all_cupon']*100/($read['all_cupon']+$receive['all_cupon']+$used['all_cupon']))
		->setCellValue('A6',3)
		->setCellValue('B6',yii::t('app',"使用数量"))
		->setCellValue('C6',$used['all_cupon']?$used['all_cupon']:0)
		->setCellValue('D6',$used['all_cupon']*100/($read['all_cupon']+$receive['all_cupon']+$used['all_cupon']));

		//细边框引用
		$objPHPExcel->getActiveSheet()->getStyle('A2:D3')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D4')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D5')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D6')->applyFromArray($linestyle);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('fae9e5');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setARGB('fae9e5');
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFill()->getStartColor()->setARGB('fae9e5');
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A:B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('C:D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('D5')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('D6')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('D7')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

		//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);

		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:D7')->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);



		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="代金券使用情况报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}


	//办卡记录excel
	public function actionMembercardExport(){
		$objPHPExcel = new PHPExcel();
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$text = Yii::app()->request->getParam('text');
		$membercard = "";
		$branduser = "";
		$db = Yii::app()->db;
		if($text==1){
			$set_name="传统卡";
			$checked_name="传统卡号";
			$sql = 'select k.* from nb_member_card k where k.delete_flag = 0 and k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" and k.dpid in('.$this->companyId.')';
			//var_dump($sql);exit;
		}

		if($text==2){
			$set_name="微信会员卡";
			$checked_name="会员卡号";
			$sql = 'select k.* from nb_brand_user k where k.create_at >="'.$begin_time.' 00:00:00" and k.create_at <="'.$end_time.' 23:59:59" and k.dpid in('.$this->companyId.') ';
			//var_dump($sql);exit;
		}
		if($text==3){
			//$money = "0";
			//传统卡充值
			$set_name="统计";

			$sql = 'select k.* from(select count(t.lid) as card_num from nb_member_card t where t.dpid = '.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" ) k';
			$membercard = Yii::app()->db->createCommand($sql)->queryRow();

			//微信会员卡充值
			$sql = 'select k.* from(select count(t.lid) as brand_num from nb_brand_user t where t.dpid = '.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59") k';
			$branduser = Yii::app()->db->createCommand($sql)->queryRow();

		}


		$models =  $db->createCommand($sql)->queryAll();

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



		if ($text==1){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1','办卡记录报表')
			->setCellValue('A2',yii::t('app','查询条件：').$set_name.yii::t('app','时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59    ".yii::t('app','生成时间：').date('m-d h:i',time()))
			->setCellValue('A3',$checked_name)
			->setCellValue('B3','名称')
			->setCellValue('C3','手机号')
			->setCellValue('D3','卡状态')
			->setCellValue('E3','办卡时间')
			->setCellValue('F3','备注');

		}elseif ($text==2){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1','办卡记录报表')
			->setCellValue('A2',yii::t('app','查询条件：').$set_name.yii::t('app','时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59    ".yii::t('app','生成时间：').date('m-d h:i',time()))
			->setCellValue('A3',$checked_name)
			->setCellValue('B3','名称')
			->setCellValue('C3','手机号')
			->setCellValue('D3','办卡时间')
			->setCellValue('E3','备注');
		}elseif ($text==3){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1','办卡记录报表')
			->setCellValue('A2',yii::t('app','查询条件：').$set_name.yii::t('app','时间段：').$begin_time.yii::t('app',' 00:00:00 至 ').$end_time." 23:59:59    ".yii::t('app','生成时间：').date('m-d h:i',time()))
			->setCellValue('A3','会员卡类型')
			->setCellValue('B3','办卡数量')
			->setCellValue('C3','备注')
			->setCellValue('A4','传统卡')
			->setCellValue('B4',$membercard['card_num'])
			->setCellValue('C4','')
			->setCellValue('A5','会员卡')
			->setCellValue('B5',$branduser['brand_num'])
			->setCellValue('C5','');

		}

		$i=4;
		foreach($models as $v){
			//print_r($v);
			if ($text==1){
				$card_status ='';
				switch($v['card_status']){
					case 0:
						$card_status = '正常';
						break;
					case 1:
						$card_status = '挂失';
						break;
					case 2:
						$card_status = '注销';
						break;
					default:
						$card_status = '';
						break;
				}
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['selfcode'])
				->setCellValue('B'.$i,$v['name'])
				->setCellValue('C'.$i,$v['mobile'])
				->setCellValue('D'.$i,$card_status)
				->setCellValue('E'.$i,$v['create_at'])
				->setCellValue('F'.$i,"");

			}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['card_id'])
				->setCellValue('B'.$i,$v['user_name'].'('.$v['nickname'].')')
				->setCellValue('C'.$i,$v['mobile_num'])
				->setCellValue('D'.$i,$v['create_at'])
				->setCellValue('E'.$i,"");
			}
			$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$i++;
		}


		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//A2字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//A2字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置填充颜色

		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(18);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(23);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);




		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="办卡记录报表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');


	}
	
	

	//导出支付方式员工营业额的报表
	private function RijieReportExport($models,$payments,$text,$userid,$dpid,$begin_time,$end_time){
		date_default_timezone_set('PRC');
		$objPHPExcel = new PHPExcel();
	
		//设置第1行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		//设置第2行的行高
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
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
		->setCellValue('A1',yii::t('app','支付方式（员工营业额）报表'))
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d H:i:s',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','总单数'))
		->setCellValue('C3',yii::t('app','毛利润'))
		->setCellValue('D3',yii::t('app','优惠'))
		->setCellValue('E3',yii::t('app','实收款'))
		->setCellValue('F3',yii::t('app','营业员'))
		->setCellValue('G3',yii::t('app','现金'))
		->setCellValue('H3',yii::t('app','微信'))
		->setCellValue('I3',yii::t('app','微点单'))
		->setCellValue('J3',yii::t('app','微外卖'))
		->setCellValue('K3',yii::t('app','支付宝'))
		->setCellValue('L3',yii::t('app','银联'))
		->setCellValue('M3',yii::t('app','会员卡'))
		->setCellValue('N3',yii::t('app','美团·外卖'))
		->setCellValue('O3',yii::t('app','饿了么·外卖'))
		->setCellValue('P3',yii::t('app','系统券'))
		->setCellValue('Q3',yii::t('app','积分'))
		->setCellValue('R3',yii::t('app','微信余额'));
		$letternext= 'S';
		if($payments){
			$let = '0';
			$letter='';
	
			foreach ($payments as $payment){
				$paymentname = $payment['name'];
				$let++;
				switch ($let){
					case 1: $letter = 'S3';$letternext = 'T';break;
					case 2: $letter = 'T3';$letternext = 'U';break;
					case 3: $letter = 'U3';$letternext = 'V';break;
					case 4: $letter = 'V3';$letternext = 'W';break;
					case 5: $letter = 'W3';$letternext = 'X';break;
					case 6: $letter = 'X3';$letternext = 'Y';break;
					case 7: $letter = 'Y3';$letternext = 'Z';break;
					default:break;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letter,yii::t('app',$paymentname));
			}
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternext.'3',yii::t('app','退款'));
		$j=4;
		foreach($models as $v){
			//print_r($v);
	
			if ($text==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all']);
			}elseif($text==2){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all']);
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all']);
			}
			if($userid !='0'){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j,$v['username'].'('.$this->getUserstaffno($dpid,$v['username']).')');
			}else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$j);
			}
			$retreats = $this->getRijieRetreat($v['dpid'],$begin_time,$end_time,$text,$v['y_all'],$v['m_all'],$v['d_all'],$userid,$v['username']);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$j,$v['maoli_nums'])
			->setCellValue('C'.$j,$reality_all = $v['maoli_money'])
			->setCellValue('D'.$j,sprintf("%.2f",$reality_all-$v['chunli_money']))
			->setCellValue('E'.$j,sprintf("%.2f",$retreats+$v['chunli_money']))
			->setCellValue('G'.$j,$v['cash_money'])
			->setCellValue('H'.$j,$v['wx_money'])
			->setCellValue('I'.$j,$v['wxord_money'])
			->setCellValue('J'.$j,$v['wxwm_money'])
			->setCellValue('K'.$j,$v['ali_money'])
			->setCellValue('L'.$j,$v['visa_money'])
			->setCellValue('M'.$j,$v['member_money'])
			->setCellValue('N'.$j,$v['mt_money'])
			->setCellValue('O'.$j,$v['elem_money'])
			->setCellValue('P'.$j,$v['cupon_money'])
			->setCellValue('Q'.$j,$v['jifen_money'])
			->setCellValue('R'.$j,$v['wxyue_money']);
			$letters='';
			$letternexts= 'S';
			if($payments){
				$let = '0';
	
	
				foreach ($payments as $payment){
					$paymentname = $payment['name'];
					$let++;
					switch ($let){
						case 1: $letters = 'S';$letternexts = 'T';break;
						case 2: $letters = 'T';$letternexts = 'U';break;
						case 3: $letters = 'U';$letternexts = 'V';break;
						case 4: $letters = 'V';$letternexts = 'W';break;
						case 5: $letters = 'W';$letternexts = 'X';break;
						case 6: $letters = 'X';$letternexts = 'Y';break;
						case 7: $letters = 'Y';$letternexts = 'Z';break;
						default:break;
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letters.$j,$pay_item =  $this->getPaymentPrice($v['dpid'],$begin_time,$end_time,3,$payment['lid'],$text,$v['y_all'],$v['m_all'],$v['d_all'],$userid,$v['username']));
				}
				$objPHPExcel->getActiveSheet()->getStyle('C'.$j.':'.$letters.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letternexts.$j,$retreats);
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.$letternext.$j)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左、靠右
			$objPHPExcel->getActiveSheet()->getStyle('B'.$j.':'.$letternext.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			//细边框样式引用
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->applyFromArray($linestyle);
			$j++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.$j)->applyFromArray($lineBORDER);
		//大边框样式引用
		//$objPHPExcel->getActiveSheet()->getStyle('A2:E'.$j)->applyFromArray($linestyle);
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$letternext.'1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$letternext.'2');
		//单元格加粗，居中：
	
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A3:'.$letternext.'3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:'.$letternext.'1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		//$objPHPExcel->getActiveSheet()->getStyle('A2:'.$letternext.'2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(12);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="支付方式（员工营业额）报表（".date('y年m月d日  H时i分',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	
	}


}