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
		
		//$criteria->order = 't.update_at asc,t.dpid asc';

		$pages = new CPagination(OrderProduct::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();
		$comName = $this->getComName();
		//$a=array_keys($comName);
		//var_dump($a);exit;
        // var_dump($comName);exit;       


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
		//$sql='select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_huiyuan,t.paytype,t.payment_method_id from nb_order_pay t left join nb_company t1 on (t.dpid = t1.dpid) where t.paytype = 4 and t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		//$money = Yii::app()->db->createCommand($sql)->queryRow();
		//var_dump($money);exit;
		$criteria = new CDbCriteria;
//		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.reality_total) as all_reality,t.paytype,t.payment_method_id,t.order_status';
//		$criteria->with = array('company','paymentMethod');
//		$criteria->condition = 't.order_status in(3,4,8) and t.dpid='.$this->companyId ;
//		if($str){
//			$criteria->condition = ' t.order_status in(3,4,8) and t.dpid in('.$str.')';
//		}
//		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
//		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
//		if($text==1){
//			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,year(t.update_at)';
//			$criteria->order = 'year(t.update_at) asc,t.dpid asc';
//		}elseif($text==2){
//			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,month(t.update_at)';
//			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
//		}else{
//			$criteria->group ='t.paytype,t.dpid,t.payment_method_id,day(t.update_at)';
//			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
//		}
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
		//$criteria->order = 't.update_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';
		
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
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
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
		
		//$sql = 'select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.update_at asc';
		//var_dump($sql);exit;
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
		//$models = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($models);exit;
		//$comName = $this->getComName();
		/*$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price';
		$criteria->with = array('product','productcg');
		$criteria->condition = 't.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.dpid='.$this->companyId;
		if($str){
			$criteria->condition = ' t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.dpid,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.dpid,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,t.dpid asc';
		}else{
			$criteria->group ='t.dpid,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc';
		}
		*/
		/*$pages = new CPagination(OrderProduct::model()->count($criteria));
		$pages->applyLimit($criteria);
		//var_dump($criteria);exit;
		$model = OrderProduct::model()->findAll($criteria);
		//$comName = $this->getComName();
		$Catname = $this->getCatName($CategoryId);*/
		$this->render('cgReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				//'catname'=>$Catname,
				'comName'=>$comName,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	
	public function actionIncomeReport(){
		$str = Yii::app()->request->getParam('str');
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
		
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.create_at asc';
		//var_dump($sql);exit;
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
									group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
									group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}else{
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
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
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//$models = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($models);exit;
		//$comName = $this->getComName();
	
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
		//'categories'=>$categories,
		//'categoryId'=>$categoryId
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
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = 't.dpid in('.$str.')';
		}
		$criteria->addCondition("order4.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order4.create_at <='$end_time 23:59:59'");
// 		$criteria->addCondition("order8.create_at >='$begin_time 00:00:00'");
// 		$criteria->addCondition("order8.create_at <='$end_time 23:59:59'");
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
		//$criteria->order = 't.create_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';
// 		if($download){
// 			//$model = OrderPay::model()->findAll($criteria);
// 			//var_dump($models);exit;
// 			$this->actionPayallExport($criteria,$text);
// 			exit;
// 		}
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
				//'money'=>$money,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/*
	 * 充值记录报表
	 */
	public function actionRecharge(){
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
		//var_dump($money);exit;
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
		//微信会员卡充值
		//$sql = 'select sum(t.recharge_money) as all_recharge,sum(t.cashback_num) as all_cashback from nb_recharge_record t where t.dpid = '.$this->companyId.' and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" ';
		//$recharge = Yii::app()->db->createCommand($sql)->queryRow();
	
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		
// 		$criteria = new CDbCriteria;
// 		//$sql ='select t.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all,t1.name from nb_order_pay t left join nb_payment_method t1 on(t.dpid = t1.dpid and t.payment_method_id = t1.lid) where (t.dpid = t1.dpid ) group by t.paytype,t.payment_method_id';
// 		$criteria->select = 't.paytype, t.payment_method_id,t.dpid, t.update_at,sum(t.pay_amount) as should_all';
// 	    //利用Yii框架CDB语句时，聚合函数要在model的类里面进行公共变量定义，如：变量should_all在order的class里面定义为public $should_all;
// 		//$criteria->select = 'sum(t.should_total) as should_all'; //代表了要查询的字段，默认select='*';
// 		$criteria->with = array("order","paymentMethod"); //连接表
	
// 		$criteria->addCondition("t.dpid= ".$this->companyId);
// 		$criteria->addCondition("order.update_at >='$begin_time 00:00:00'");
// 		$criteria->addCondition("order.update_at <='$end_time 23:59:59'");
// 		$criteria->group = "t.paytype,t.payment_method_id";
	
// 		$pages = new CPagination(OrderPay::model()->count($criteria));
// 			$pages->PageSize = 20;
// 		$pages->applyLimit($criteria);
	
// 		$model=  OrderPay::model()->findAll($criteria);
		//var_dump($model);exit;
		$this->render('recharge',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'moneys'=>$money,
				'recharge'=>$recharge,
				'text'=>$text,
							//'categories'=>$categories,
							//'categoryId'=>$categoryId
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
		//if($text==1){
		//$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid)  where t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status =8 group by year(t.create_at) asc) k';
		//$models = $db->createCommand($sql)->queryAll();
		//echo $sql;exit;
		//}elseif ($text==2){
		//	$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid)  where t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status =8 group by month(t.create_at) asc) k';
			
		//}elseif ($text==3){
		//	$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid)  where t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status =8 group by day(t.create_at) asc) k';
			
		//}else{
			//$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid and t2.paytype not in(9,10))  where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) ) k';
			//$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,t2.pay_amount,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid and t2.paytype not in(9,10)) where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') ) k';	
		$sql = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') ) k';
			//统计实付价格，客流、单数
		//echo $sql;exit;
		//}
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.')';
		//$sql = 'select sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.')';
		
		//统计订单原价
		//echo $sql;exit;
		$money = Yii::app()->db->createCommand($sql)->queryRow();
	 	$sql2 = 'select sum(t1.price*t.retreat_amount) as retreat_allprice,count(distinct t1.order_id) as retreat_num from nb_order_retreat t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t.order_detail_id = t1.lid) where t.delete_flag =0 and t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.dpid='.$this->companyId;
	 	$retreat = Yii::app()->db->createCommand($sql2)->queryRow();
		//var_dump($money);exit;
// 		$criteria = new CDbCriteria;
// 		$criteria->select = 'year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.dpid,t.create_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
// 		$criteria->with = array('company','order8','paymentMethod');
// 		$criteria->condition = ' t.dpid='.$this->companyId ;
// 		if($str){
// 			$criteria->condition = ' and t.dpid in('.$str.')';
// 		}
// 		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
// 		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
// 		if($text==1){
// 			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.create_at)';
// 			$criteria->order = 'year(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
// 		}elseif($text==2){
// 			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.create_at)';
// 			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
// 		}elseif($text==3){
// 			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.create_at)';
// 			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.pay_amount) desc,t.dpid asc';
// 		}
		//$criteria->order = 't.create_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';

// 		$pages = new CPagination(OrderPay::model()->count($criteria));
// 		//	    $pages->setPageSize(1);
// 		$pages->applyLimit($criteria);
// 		//var_dump($criteria);exit;
// 		$model = OrderPay::model()->findAll($criteria);
		//var_dump($model);exit;
		$comName = $this->getComName();
		$this->render('businessdataReport',array(
				'models'=>$models,
				'pages'=>$pages,
				'begin_time'=>$begin_time,
				'end_time'=>$end_time,
				'text'=>$text,
				'str'=>$str,
				'comName'=>$comName,
				'moneys'=>$money,
				'retreat'=>$retreat,
				//'money'=>$money,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
		));
	}
	/*
	 * 退菜明细报表
	 */
	
	public function actionRetreatdetailReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		$db = Yii::app()->db;

		$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t4.product_name as product_name_p,t.* from nb_order_product t left join nb_order t1 on(t.dpid= t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product t4 on(t.dpid = t4.dpid and t.product_id = t4.lid and t4.delete_flag = 0)  where t.delete_flag = 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.')) k';
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
				//'money'=>$money,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
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
		$sql = 'select k.* from(select sum(t.number) as all_number, count(distinct t.account_no) as all_account, sum(t2.pay_amount) as all_paymoney, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) group by t1.type_id) k';//区域名称报表
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($model);exit;
		$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_money, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';//区域名称报表
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
				//'money'=>$money,
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
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
		//$catId = Yii::app()->request->getParam('cid',0);
		//var_dump($catId);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.product_name,t.create_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount*(-(t.is_giving-1))) as all_price, sum(t.original_price*t.amount) as all_jiage';
		$criteria->with = array('company','product','order');
	
		$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid='.$this->companyId.' and t.set_id '.$setids.' ';
		if($str){
			$criteria->condition = 'order.order_status in(3,4,8) and t.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid in('.$str.')';
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
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
	
		if($text==1){
			$criteria->group ='t.product_id,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.product_id,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}else{
			$criteria->group ='t.product_id,day(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}
	
		//$criteria->order = 't.create_at asc,t.dpid asc';
	
		$pages = new CPagination(OrderProduct::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = OrderProduct::model()->findAll($criteria);
		//var_dump($models);exit();
		$comName = $this->getComName();
		//$a=array_keys($comName);
		//var_dump($a);exit;
		 //var_dump($comName);
		 //exit;
	
	
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
		//$catId = Yii::app()->request->getParam('cid',0);
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
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') group by t.order_id,t.set_id) k where 1 group by k.y_all,k.set_id order by k.y_all,all_setnum desc,all_setprice desc)c';
			
		}elseif($text==2){
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') group by t.order_id,t.set_id) k where 1 group by k.m_all,k.set_id order by k.y_all,k.m_all,all_setnum desc,all_setprice desc)c';
			
		}else{
			$sql = 'select c.* from(select k.*,sum(k.zhiamount) as all_setnum,sum(k.zhiamount*k.all_price) as all_setprice,sum(k.zhiamount*k.all_oriprice) as all_orisetprice  from (select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.set_name,t.price,t.amount,t.zhiamount,t.order_id,t.set_id,t2.company_name,sum(t.price*t.amount) as all_price,sum(t.original_price*t.amount) as all_oriprice,count(distinct t.order_id,set_id) from nb_order_product t left join nb_product_set t1 on(t1.lid = t.set_id and t.dpid = t1.dpid ) left join nb_company t2 on(t2.dpid = t.dpid ) right join nb_order t3 on(t3.dpid = t.dpid and t3.lid = t.order_id and t3.order_type '.$ordertypes.' ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=2 and t.set_id >0 and t.create_at >="'.$begin_time.' 00:00:00 " and t.create_at <= "'.$end_time.' 23:59:59" and t.dpid in('.$strs.') group by t.order_id,t.set_id) k where 1 group by k.d_all,k.set_id order by k.y_all,m_all,k.d_all,all_setnum desc,all_setprice desc)c';
			
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
		//echo $read;
		//echo $receive;
		//echo $used;exit;
		//var_dump($receive);exit;
		
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
		$sql = 'select t.* from (select username,sum(should_total) as total from nb_order where order_status in (3,4,8) and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59" group by username order by lid desc)t';
		if($download){
			$models = $db->createCommand($sql)->queryAll();
			$this->exportTurnOver($models);
			exit;
		}
		$count = $db->createCommand(str_replace('t.*','count(*)',$sql))->queryScalar();
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
	/*
	 * 账单详情报表
	 */
	public function actionOrderdetail(){
		$criteria = new CDbCriteria;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.create_at >=0 and t.dpid= '.$this->companyId;
		$criteria->select = 'sum(t.number) as all_number,t.*';
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.order_status in(3,4,8) ");//只要付款了的账单都进行统计
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		//$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->with = array("company","paymentMethod");
	
		//$connect = Yii::app()->db->createCommand($sql);
		//$model = $connect->queryAll();
		$criteria->group = 't.account_no,t.order_status' ;
		$criteria->order = 't.lid ASC' ;
		$criteria->distinct = TRUE;
	
		//$categoryId = Yii::app()->request->getParam('cid',0);
	
	
	
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
	
				//'categories'=>$categories,
				//'categoryId'=>$categoryId
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
		$sql = 'select k.* from(select count(distinct t.account_no) as all_account ,count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id ) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) k';
		//var_dump($sql);exit;
		
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		
		$sql = 'select sum(j.all_amount) as all_payall from(select count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id ) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) j';
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
		$sql = 'select sum(t.pay_amount) as all_zhifu,t.* from nb_order_pay t where t.paytype not in(9,10) and t.order_id in(select t1.lid from nb_order t1 where t1.account_no = '.$account_no.')';
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
		//if($siteId && $dpid){
		//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
		//$conn = Yii::app()->db->createCommand($sql);
	
		//}
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
	

/*	private function getCategoryList(){
		$categories = user::model()->findAll('delete_flag=0 ' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'username');
	}
*/
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
 		public function getCatName($CategoryId){
		$Catname = "";
		$sql = 'select t.lid,t.category_name from nb_product_category t where t.lid='.$CategoryId;
		//$sql = 'select t.site_id, t.dpid, t1.site_level, t1.type_id, t1.serial, t2.name from nb_order t, nb_site t1, nb_site_type t2 where t.site_id = t1.lid and t.dpid = t1.dpid and t1.type_id = t2.lid and t.dpid = t2.dpid and t.lid ='. $orderId;
		//$conn = Yii::app()->db->createCommand($sql);
		//$result = $conn->queryRow();
		//$siteId = $result['lid'];
 		$connect = Yii::app()->db->createCommand($sql);
 	//	$connect->bindValue(':site_id',$siteId);
 	//	$connect->bindValue(':dpid',$dpid);
 		$model = $connect->queryRow();
 		if($model['category_name']){
 			$Catname = $model['category_name'];
 		}
        
 		/*if($site['site_id'] && $site['dpid'] ){
		//	echo 'ABC';
                    $sitelevel = $site['site_level'];
                    $sitename = $site['name'];
                    $sitetype = $site['serial'];
                    $retsite=$sitelevel.":".$sitename.":".$sitetype;
		}*/
		//if($siteId && $dpid){
			//$sql = 'select order.site_id, order.dpid,site.type_id, site.serial, site_type.name from nb_order, nb_site, nb_site_type where order.site_id = site.lid and order.dpid = site.dpid';
			//$conn = Yii::app()->db->createCommand($sql);

	      //}
		return $Catname;
	}
// 	private function getDepartments(){
// 		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
// 		return CHtml::listData($departments, 'department_id', 'name');
// 	}
	

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
		//$catId = Yii::app()->request->getParam('cid',0);
		//var_dump($catId);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
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
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
			->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
			//$criteria->order = 't.update_at asc';
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
			->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
		->setCellValue('A1',yii::t('app','产品分类营业额报表'))
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
					//$objPHPExcel->getActiveSheet()->getStyle('A3'.$j.':E3'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
		//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
	
	
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
	
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.create_at asc';
		//var_dump($sql);exit;
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,year(t.create_at) order by year(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
									group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,month(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
									group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.create_at,t.product_id,t.price,sum(t.price*t.amount*(-(t.is_giving-1))) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status in(2) and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status in(3,4,8) and t.set_id '.$setids.'
								group by t1.category_id,t.dpid,day(t.create_at) order by year(t.create_at) asc,month(t.create_at) asc,day(t.create_at) asc,sum(t.price) desc,t.dpid asc)k';
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
		->setCellValue('A2',yii::t('app','查询条件：').$setname.yii::t('app','时间段：').$begin_time.yii::t('app','00:00:00 至 ').$end_time."23:59:59    ".yii::t('app','生成时间：').date('m-d h:i',time()))
		->setCellValue('A3',yii::t('app','时间'))
		->setCellValue('B3',yii::t('app','店铺名称'))
		->setCellValue('C3',yii::t('app','产品分类'))
		->setCellValue('D3',yii::t('app','单数'))
		->setCellValue('E3',yii::t('app','金额统计'))
		->setCellValue('F3',yii::t('app','备注'));
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
				->setCellValue('E'.$j,$v['all_price'])
				->setCellValue('F'.$j);
			}elseif ($text==2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'])
				->setCellValue('B'.$j,$v['company_name'])
				->setCellValue('C'.$j,$v['category_name'])
				->setCellValue('D'.$j,$v['all_num'])
				->setCellValue('E'.$j,$v['all_price'])
				->setCellValue('F'.$j);
			}elseif ($text==3){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$j,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
				->setCellValue('B'.$j,$v['company_name'])
				->setCellValue('C'.$j,$v['category_name'])
				->setCellValue('D'.$j,$v['all_num'])
				->setCellValue('E'.$j,$v['all_price'])
				->setCellValue('F'.$j);
			}
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
			//$objPHPExcel->getActiveSheet()->getStyle('A3'.$j.':E3'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A2:F'.$j)->applyFromArray($linestyle);//细边框样式引用
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':D'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	
	
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="营业收入报表（产品分类）（".date('m-d',time())."）.xls";
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
			->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app','00:00:00 至 ').$end_time." 23:59:59   ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
		//$catId = Yii::app()->request->getParam('cid',0);
		//var_dump($catId);exit;
		$criteria = new CDbCriteria;
		//$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t1.dpid,t.product_id,t1.lid,t1.product_name,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total from nb_order_product t left join nb_product t1 on(t1.lid = t.product_id and t.dpid = t1.dpid ) where t.delete_flag=0 and t1.delete_flag = 0 and t.product_order_status=1 group by t.product_id,t.amount,is_retreat,month(t.create_at)';
		//var_dump($sql);exit;
		$criteria->select ='year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t.create_at,t.lid,t.dpid,t.product_id,t.price,t.amount,t.is_retreat,sum(t.price) as all_money,sum(t.amount) as all_total, sum(t.price*t.amount*(-(t.is_giving-1))) as all_price, sum(t.original_price*t.amount) as all_jiage';
		$criteria->with = array('company','product','order');
	
		$criteria->condition = 't.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid='.$this->companyId .' and t.set_id '.$setids.' ';
		if($str){
			$criteria->condition = 't.is_retreat=0 and t.product_order_status in(1,2) and t.delete_flag=0 and t.dpid in('.$str.')';
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
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
	
		if($text==1){
			$criteria->group ='t.product_id,year(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.product_id,month(t.create_at)';
			$criteria->order = 'year(t.create_at) asc,month(t.create_at) asc,sum(t.amount) desc,sum(t.original_price*t.amount) desc,t.dpid asc';
		}else{
			$criteria->group ='t.product_id,day(t.create_at)';
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
			//print_r($v);
			if ($text==1){
	
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v->y_all)
				->setCellValue('B'.$i,$v->company->company_name)
				->setCellValue('C'.$i,$v->product->product_name)
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
				->setCellValue('C'.$i,$v->product->product_name)
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
				->setCellValue('C'.$i,$v->product->product_name)
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
	
	/*
	 * 
	 * 营业数据报表
	 * 
	 */
	
	public function actionBusinessdataReportExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		$db = Yii::app()->db;
		$sql = 'select k.* from(select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,t2.pay_amount,sum(t2.pay_amount) as all_realprice,t.* from nb_order t left join nb_order_pay t2 on(t.dpid = t2.dpid and t2.order_id = t.lid and t2.paytype not in(9,10)) where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') ) k';	
		
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,sum(t.number) as all_number,count(distinct(t.account_no)) as all_account,sum(t1.original_price*t1.amount) as all_originalprice,sum(t1.price*t1.amount*(-(t1.is_giving-1))) as all_price,t.* from nb_order t left join nb_order_product t1 on(t.dpid = t1.dpid and t1.delete_flag = 0 and t1.order_id = t.lid and t1.product_order_status in(1,2) and t1.is_retreat =0) where t.create_at >="'.$begin_time.'" and t.create_at <="'.$end_time.'" and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.')';
		//统计订单原价
		//echo $sql;exit;
		$money = Yii::app()->db->createCommand($sql)->queryRow();
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
		->setCellValue('A1','营业数据报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
			if($v['all_number']){
				if($v['all_account']){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
				->setCellValue('B'.$i,$v['all_number'])
				->setCellValue('C'.$i,$v['all_account'])
				->setCellValue('D'.$i,$money['all_originalprice'])
				->setCellValue('E'.$i,$v['all_realprice'])
				->setCellValue('F'.$i,$money['all_originalprice']-$v['all_realprice'])
				->setCellValue('G'.$i,$v['all_realprice']/$v['all_number'])
				->setCellValue('H'.$i,$v['all_realprice']/$v['all_account'])
				->setCellValue('I'.$i);
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
					->setCellValue('B'.$i,$v['all_number'])
					->setCellValue('C'.$i,$v['all_account'])
					->setCellValue('D'.$i,$money['all_originalprice'])
					->setCellValue('E'.$i,$v['all_realprice'])
					->setCellValue('F'.$i,$money['all_originalprice']-$v['all_realprice'])
					->setCellValue('G'.$i,$v['all_realprice']/$v['all_number'])
					->setCellValue('H'.$i,$v['all_realprice'])
					->setCellValue('I'.$i);
				}
			}else{
				if($v['all_account']){
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
					->setCellValue('B'.$i,$v['all_number'])
					->setCellValue('C'.$i,$v['all_account'])
					->setCellValue('D'.$i,$money['all_originalprice'])
					->setCellValue('E'.$i,$v['all_realprice'])
					->setCellValue('F'.$i,$money['all_originalprice']-$v['all_realprice'])
					->setCellValue('G'.$i,$v['all_realprice'])
					->setCellValue('H'.$i,$v['all_realprice']/$v['all_account'])
					->setCellValue('I'.$i);
				}else{
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,$v['y_all'].'-'.$v['m_all'].'-'.$v['d_all'])
					->setCellValue('B'.$i,$v['all_number'])
					->setCellValue('C'.$i,$v['all_account'])
					->setCellValue('D'.$i,$money['all_originalprice'])
					->setCellValue('E'.$i,$v['all_realprice'])
					->setCellValue('F'.$i,$money['all_originalprice']-$v['all_realprice'])
					->setCellValue('G'.$i,$v['all_realprice'])
					->setCellValue('H'.$i,$v['all_realprice'])
					->setCellValue('I'.$i);
				}
			}
			$objPHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
	
	
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="营业数据报表（".date('m-d',time())."）.xls";
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
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		//$sql = 'select t1.name, t.* from nb_order t left join  nb_payment_method t1 on( t.payment_method_id = t1.lid and t.dpid = t1.dpid ) where t.create_at >=0 and t.dpid= '.$this->companyId;
		$criteria->select = 'sum(t.number) as all_number,t.*';
		$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->addCondition("t.order_status in(3,4,8) ");//只要付款了的账单都进行统计
		$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
		//$criteria->addCondition("t.dpid= ".$this->companyId);
		$criteria->with = array("company","paymentMethod");
	
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
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
		->setCellValue('A3','账单号')
		->setCellValue('B3','账单更新时间')
		->setCellValue('C3','座位')
		->setCellValue('D3','人数')
		->setCellValue('E3','原价')
		->setCellValue('F3','优惠')
		->setCellValue('G3','实收')
		->setCellValue('H3','');
		$i=4;
		foreach($model as $v){
			if($v->is_temp=='1'){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v->account_no,PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v->update_at)
				->setCellValue('C'.$i,yii::t('app','临时坐').$v->site_id%1000)
				->setCellValue('D'.$i,$v->all_number)
				->setCellValue('E'.$i,$this->getOriginalMoney($v->account_no))
				->setCellValue('F'.$i,$this->getOriginalMoney($v->account_no)-$this->getAccountMoney($v->account_no))
				->setCellValue('G'.$i,$this->getAccountMoney($v->account_no))
				->setCellValue('H'.$i);
			}else {
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v->account_no,PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v->update_at)
				->setCellValue('C'.$i,$this->getSiteName($v->lid))
				->setCellValue('D'.$i,$v->all_number)
				->setCellValue('E'.$i,$this->getOriginalMoney($v->account_no))
				->setCellValue('F'.$i,$this->getOriginalMoney($v->account_no)-$this->getAccountMoney($v->account_no))
				->setCellValue('G'.$i,$this->getAccountMoney($v->account_no))
				->setCellValue('H'.$i);
			}
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$i.':G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
	
	
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="账单详情报表（".date('m-d',time())."）.xls";
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
		$sql = 'select k.* from(select count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id ) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) k';
		//var_dump($sql);exit;
		
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		
		$sql = 'select sum(j.all_amount) as all_payall from(select count(t.order_type) as all_ordertype,t.order_type,sum(t1.pay_amount) as all_amount from nb_order t left join nb_order_pay t1 on(t.dpid = t1.dpid and t.lid = t1.order_id ) where t.create_at>="'.$begin_time.' 00:00:00" and t.create_at<="'.$end_time.' 23:59:59"  and t.order_status in(3,4,8) and t.dpid in('.$this->companyId.') group by t.order_type order by t.create_at asc) j';
		$connect = Yii::app()->db->createCommand($sql);
		$allpay = $connect->queryRow();
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
		->setCellValue('A1','渠道占比报表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
		->setCellValue('A3','渠道名称')
		->setCellValue('B3','单数')
		->setCellValue('C3','单均')
		->setCellValue('D3','金额')
		->setCellValue('E3','占比(%)');
		$i=4;
		foreach($models as $v){
			switch($v['order_type']){
				case 0:
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,yii::t('app','到店堂食'))
					->setCellValue('B'.$i,$v['all_ordertype'])
					->setCellValue('C'.$i,$v['all_amount']/$v['all_ordertype'])
					->setCellValue('D'.$i,$v['all_amount'])
					->setCellValue('E'.$i,$v['all_amount']*100/$allpay['all_payall'])
					->setCellValue('F'.$i);
					break;
				case 1:
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,yii::t('app','微信堂食'))
					->setCellValue('B'.$i,$v['all_ordertype'])
					->setCellValue('C'.$i,$v['all_amount']/$v['all_ordertype'])
					->setCellValue('D'.$i,$v['all_amount'])
					->setCellValue('E'.$i,$v['all_amount']*100/$allpay['all_payall'])
					->setCellValue('F'.$i);
					break;
				case 2:
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,yii::t('app','微信外卖'))
					->setCellValue('B'.$i,$v['all_ordertype'])
					->setCellValue('C'.$i,$v['all_amount']/$v['all_ordertype'])
					->setCellValue('D'.$i,$v['all_amount'])
					->setCellValue('E'.$i,$v['all_amount']*100/$allpay['all_payall'])
					->setCellValue('F'.$i);
					break;
				case 3:
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,yii::t('app','后台外卖'))
					->setCellValue('B'.$i,$v['all_ordertype'])
					->setCellValue('C'.$i,$v['all_amount']/$v['all_ordertype'])
					->setCellValue('D'.$i,$v['all_amount'])
					->setCellValue('E'.$i,$v['all_amount']*100/$allpay['all_payall'])
					->setCellValue('F'.$i);
					break;
				default:
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i,yii::t('app',''))
					->setCellValue('B'.$i,$v['all_number'])
					->setCellValue('C'.$i,$v['all_account'])
					->setCellValue('D'.$i,$money['all_originalprice'])
					->setCellValue('E'.$i,$v['all_realprice'])
					->setCellValue('F'.$i);
					break;
			}	
			
			$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
	
	
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="渠道占比报表（".date('m-d',time())."）.xls";
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
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
	
		$db = Yii::app()->db;

		$sql = 'select year(t.create_at) as y_all,month(t.create_at) as m_all,day(t.create_at) as d_all,t1.account_no,t2.username,t2.retreat_amount,t3.name,t2.retreat_memo,t4.product_name,t.* from nb_order_product t left join nb_order t1 on(t.dpid= t1.dpid and t1.lid = t.order_id ) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t2.order_detail_id = t.lid and t2.delete_flag = 0) left join nb_retreat t3 on(t.dpid = t3.dpid and t3.lid = t2.retreat_id and t3.delete_flag = 0) left join nb_product t4 on(t.dpid = t4.dpid and t.product_id = t4.lid and t4.delete_flag = 0)  where t.delete_flag = 0 and t.is_retreat = 1 and t.product_order_status in(1,2) and t.create_at>="'.$begin_time.'" and t.create_at<="'.$end_time.'" and t.dpid in('.$this->companyId.')';
		//echo $sql;exit;	
		$models = Yii::app()->db->createCommand($sql)->queryAll();
		//$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		//$pages = new CPagination($count);
		//$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		//$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		//$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		//$models = $count->queryAll();
	
	
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
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
		->setCellValue('A3','账单号')
		->setCellValue('B3','菜品名称')
		->setCellValue('C3','价格')
		->setCellValue('D3','数量')
		->setCellValue('E3','退菜时间')
		->setCellValue('F3','退菜员')
		->setCellValue('G3','原因')
		->setCellValue('H3','');
		$i=4;
		foreach($models as $v){
			
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit('A'.$i,$v['account_no'],PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValue('B'.$i,$v['product_name'])
				->setCellValue('C'.$i,$v['price'])
				->setCellValue('D'.$i,$v['amount'])
				->setCellValue('E'.$i,$v['update_at'])
				->setCellValue('F'.$i,$v['username'])
				->setCellValue('G'.$i,$v['name']."(".$v['retreat_memo'].")")
				->setCellValue('H'.$i);
			
			//细边框引用
			$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($linestyle);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//$objPHPExcel->getActiveSheet()->freezePane('B4');
		//$objPHPExcel->getActiveSheet()->freezePane('C4');
		//$objPHPExcel->getActiveSheet()->freezePane('D4');
		//$objPHPExcel->getActiveSheet()->freezePane('E4');
		//$objPHPExcel->getActiveSheet()->freezePane('F4');
		//$objPHPExcel->getActiveSheet()->freezePane('G4');
		//$objPHPExcel->getActiveSheet()->freezePane('A1');
		//$objPHPExcel->getActiveSheet()->freezePane('A2:G2');
		//$objPHPExcel->getActiveSheet()->freezePane('A3:G3');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$i)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
	
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		//字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		//设置填充颜色
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
	
	
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="退菜明细报表（".date('m-d',time())."）.xls";
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
		//$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		//$pages = new CPagination($count);
		//$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		//$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		//$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		//$models = $count->queryAll();
	
	
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
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."    ".yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//$objPHPExcel->getActiveSheet()->freezePane('B4');
		//$objPHPExcel->getActiveSheet()->freezePane('C4');
		//$objPHPExcel->getActiveSheet()->freezePane('D4');
		//$objPHPExcel->getActiveSheet()->freezePane('E4');
		//$objPHPExcel->getActiveSheet()->freezePane('F4');
		//$objPHPExcel->getActiveSheet()->freezePane('G4');
		//$objPHPExcel->getActiveSheet()->freezePane('A1');
		//$objPHPExcel->getActiveSheet()->freezePane('A2:G2');
		//$objPHPExcel->getActiveSheet()->freezePane('A3:G3');
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
		//var_dump($money);exit;
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
		//$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		//$pages = new CPagination($count);
		//$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		//$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		//$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		//$models = $count->queryAll();
	
	
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
			//$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
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
			//$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
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
			//$objPHPExcel->getActiveSheet()->getStyle('A4')->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->getStyle('A5')->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			//$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('B4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('B5')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('C4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('C5')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			//$i++;
			$i=5;
		}
		
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//$objPHPExcel->getActiveSheet()->freezePane('B4');
		//$objPHPExcel->getActiveSheet()->freezePane('C4');
		//$objPHPExcel->getActiveSheet()->freezePane('D4');
		//$objPHPExcel->getActiveSheet()->freezePane('E4');
		//$objPHPExcel->getActiveSheet()->freezePane('F4');
		//$objPHPExcel->getActiveSheet()->freezePane('G4');
		//$objPHPExcel->getActiveSheet()->freezePane('A1');
		//$objPHPExcel->getActiveSheet()->freezePane('A2:G2');
		//$objPHPExcel->getActiveSheet()->freezePane('A3:G3');
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
		//$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		//$pages = new CPagination($count);
		//$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		//$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		//$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		//$models = $count->queryAll();
	
	
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
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			//$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
			//$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$i,1234567890987654321,PHPExcel_Cell_DataType::TYPE_STRING);
			$i++;
		}
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//$objPHPExcel->getActiveSheet()->freezePane('B4');
		//$objPHPExcel->getActiveSheet()->freezePane('C4');
		//$objPHPExcel->getActiveSheet()->freezePane('D4');
		//$objPHPExcel->getActiveSheet()->freezePane('E4');
		//$objPHPExcel->getActiveSheet()->freezePane('F4');
		//$objPHPExcel->getActiveSheet()->freezePane('G4');
		//$objPHPExcel->getActiveSheet()->freezePane('A1');
		//$objPHPExcel->getActiveSheet()->freezePane('A2:G2');
		//$objPHPExcel->getActiveSheet()->freezePane('A3:G3');
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
		$sql = 'select k.* from(select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_paymoney, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) group by t1.type_id) k';//区域名称报表
		//echo $sql;exit;
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($model);exit;
		$sql = 'select sum(t.number) as all_number, count(t.account_no) as all_account, sum(t2.pay_amount) as all_money, t3.name, t.* from nb_order t left join nb_site t1 on(t.site_id = t1.lid and t.dpid = t1.dpid and t1.delete_flag =0) left join nb_order_pay t2 on(t.lid = t2.order_id and t.dpid = t2.dpid) left join nb_site_type t3 on(t1.type_id = t3.lid and t3.dpid = t.dpid ) where t.dpid ='.$this->companyId.' and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" and t.order_status in(3,4,8) ';//区域名称报表
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
	
	//
// 		public function actionSalesReport(){
// 			$str = Yii::app()->request->getParam('str');
// 			$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
// 			$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
// 			$criteria = new CDbCriteria;
// 			$criteria->select = 't.*';
// 			$criteria->with = array('company','paymentMethod');
// 			$criteria->condition = ' t.dpid='.$this->companyId ;
// 			if($str){
// 				$criteria->condition = ' t.dpid in('.$str.')';
// 			}
// 			$criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
// 			$criteria->addCondition("t.create_at <='$end_time 23:59:59'");
// 			$criteria->order = 't.create_at asc';
		
// 			$pages = new CPagination(CloseAccountDetail::model()->count($criteria));
// 			//	    $pages->setPageSize(1);
// 			$pages->applyLimit($criteria);
// 			//var_dump($criteria);exit;
// 			$model = CloseAccountDetail::model()->findAll($criteria);
// 			$comName = $this->getComName();
// 			$this->render('salesReport',array(
// 					'models'=>$model,
// 					'pages'=>$pages,
// 					'begin_time'=>$begin_time,
// 					'end_time'=>$end_time,
// 					'comName'=>$comName,
// 					//'categories'=>$categories,
// 					//'categoryId'=>$categoryId
// 			));
// 		}
}