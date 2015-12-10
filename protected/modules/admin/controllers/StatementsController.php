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
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==2){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==3){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
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
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$comName = $this->getComName();
		$db = Yii::app()->db;
	
		//$sql = 'select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.update_at asc';
		//var_dump($sql);exit;
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
		}
		if($download){
			$models = $db->createCommand($sql)->queryAll();
			//var_dump($models);exit;
			$this->exportIncomeReport($models,$text);
			exit;
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
	
		$this->render('incomeReport',array(
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
	
	/*
	 * 收款统计报表（支付方式）
	 */
public function actionPayallReport(){
		$str = Yii::app()->request->getParam('str');
		$text = Yii::app()->request->getParam('text');
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		

		$criteria = new CDbCriteria;
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order8','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' and t.dpid in('.$str.')';
		}
		$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
// 		$criteria->addCondition("order8.update_at >='$begin_time 00:00:00'");
// 		$criteria->addCondition("order8.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==3){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}
		//$criteria->order = 't.update_at asc,t.dpid asc';
		//$criteria->group = 't.paytype,t.payment_method_id';
		if($download){
			//$model = OrderPay::model()->findAll($criteria);
			//var_dump($models);exit;
			$this->actionPayallExport($criteria,$text);
			exit;
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
				//'money'=>$money,
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
		$sql = 'select t.* from (select username,sum(reality_total) as total from nb_order where order_status in (3,4,8) and dpid in ('.$str.') and create_at >="'.$beginTime.' 00:00:00" and create_at <="'.$endTime.' 23:59:59" group by username order by lid desc)t';
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
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==2){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,t.dpid asc)k';
			}elseif ($text==3){
				if ($str){
					$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,t.dpid asc)k';
				}
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name 
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
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
		$download = Yii::app()->request->getParam('d');
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
		$comName = $this->getComName();
		$db = Yii::app()->db;
	
		//$sql = 'select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t1.dpid,t1.lid,t2.lid,t2.dpid,t2.category_name,t3.company_name from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid )where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid='.$this->companyId.' group by t1.category_id order by t.update_at asc';
		//var_dump($sql);exit;
		if ($text==1) {
			if($str){
				//var_dump($text);exit;
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,year(t.update_at) order by year(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==2){
			if ($str){
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,month(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
		}elseif ($text==3){
			if ($str){
				$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
							t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
							from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
							where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$str.') and t4.order_status = 8
									group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
			}
			$sql = 'select k.* from(select year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,sum(t.amount) as all_num,
						t.lid,t.dpid,t.update_at,t.product_id,t.price,sum(t.price) as all_price,t1.category_id,t2.category_name,t3.company_name
						from nb_order_product t left join nb_product t1 on(t.dpid = t1.dpid and t.product_id = t1.lid ) left join nb_product_category t2 on(t1.dpid = t2.dpid and t1.category_id = t2.lid) left join nb_company t3 on(t.dpid = t3.dpid ) left join nb_order t4 on(t.dpid = t4.dpid and t.order_id = t4.lid)
						where t.delete_flag = 0 and t.is_retreat = 0 and t.is_giving = 0 and t.product_order_status = 1 and t.update_at >="'.$begin_time.' 00:00:00" and t.update_at <="'.$end_time.' 23:59:59" and t.dpid in('.$this->companyId.') and t4.order_status = 8
								group by t1.category_id,t.dpid,day(t.update_at) order by year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.price) desc,t.dpid asc)k';
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
		->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
			$objPHPExcel->getActiveSheet()->getStyle('A2:F'.$j)->applyFromArray($linestyle);
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
		$filename="营业收入报表（产品分类）.xls";
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
		$criteria->select = 'year(t.update_at) as y_all,month(t.update_at) as m_all,day(t.update_at) as d_all,t.dpid,t.update_at,sum(t.pay_amount) as all_reality,t.paytype,t.payment_method_id,count(*) as all_num';//array_count_values()
		$criteria->with = array('company','order8','paymentMethod');
		$criteria->condition = ' t.dpid='.$this->companyId ;
		if($str){
			$criteria->condition = ' and t.dpid in('.$str.')';
		}
		$criteria->addCondition("order8.update_at >='$begin_time 00:00:00'");
		$criteria->addCondition("order8.update_at <='$end_time 23:59:59'");
		if($text==1){
			$criteria->group ='t.payment_method_id,t.paytype,t.dpid,year(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==2){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,month(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
		}elseif($text==3){
			$criteria->group ='t.paytype,t.payment_method_id,t.dpid,day(t.update_at)';
			$criteria->order = 'year(t.update_at) asc,month(t.update_at) asc,day(t.update_at) asc,sum(t.pay_amount) desc,t.dpid asc';
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
			->setCellValue('A1',yii::t('app','营业收入报表（支付方式）'))
			->setCellValue('A2',yii::t('app','报表生成时间：').date('Y-m-d h:i:s',time()))
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
						->setCellValue('A'.$j,$v->y_all)
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
						case 4:
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$j,$v->y_all)
							->setCellValue('B'.$j,$v->company->company_name)
							->setCellValue('C'.$j,yii::t('app','会员卡支付'))
							->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
							break;
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
						->setCellValue('A'.$j,$v->y_all.'-'.$v->m_all.'-'.$v->d_all)
						->setCellValue('B'.$j,$v->company->company_name)
						->setCellValue('C'.$j,yii::t('app',""))
						->setCellValue('D'.$j,$v->all_num)
						->setCellValue('E'.$j,$v->all_reality)
						->setCellValue('F'.$j);
				}
				}
				$objPHPExcel->getActiveSheet()->getStyle('A2:F'.$j)->applyFromArray($linestyle);
				$objPHPExcel->getActiveSheet()->getStyle('A3'.$j.':F3'.$j)->applyFromArray($linestyle);
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
		$filename="营业收入报表（支付方式）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		
	}
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
// 			$criteria->addCondition("t.update_at >='$begin_time 00:00:00'");
// 			$criteria->addCondition("t.update_at <='$end_time 23:59:59'");
// 			$criteria->order = 't.update_at asc';
		
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