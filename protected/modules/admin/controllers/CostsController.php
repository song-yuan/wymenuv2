<?php
class CostsController extends BackendController
{
	public function actionCostsReport(){
		$cost_type = Yii::app()->request->getParam('cost_type',0);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));

		if ($cost_type==0) {//日
			$time =$time;
			$m = substr($time, 0,7);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where pay_type=0 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=1 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$m.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';


			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$time.' 00:00:00" and k.create_at <="'.$time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$time.' 00:00:00" and create_at <="'.$time.' 23:59:59" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc,day(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.logid,msl.material_id,msl.dpid';
		} elseif($cost_type==1){//月
			$time =$month;
			$m = substr($time, 5,2);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

			$begin_time =$month.'-01 00:00:00';
			$end_time =$month.'-'.$d.' 23:59:59';
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where  delete_flag=0 and  dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';
			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.user_id,k.account_no,k.create_at';
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';

		} elseif($cost_type==2){//年
			$time =$year;
			$begin_time =$year.'-01-01 00:00:00';
			$end_time =$year.'-12-31 23:59:59';
			$sql = 'select * from nb_costs where delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"';
			
			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.user_id,k.account_no,k.create_at';
			// p($sqll);
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';
		}
		$model1 =Yii::app()->db->createCommand($sql)->queryAll();

		$model2 =Yii::app()->db->createCommand($sql0)->queryAll();

		$model3 =Yii::app()->db->createCommand($sql1)->queryAll();


		// p($model3);
		$this->render('costsReport',array(
				'model1'=>$model1,
				'model2'=>$model2,
				'model3'=>$model3,
				'cost_type'=>$cost_type,
				'time'=>$time,
				'month'=>$month,
				'year'=>$year,
		));
	}

	public function actionCostsMaterialReport(){
		$cost_type = Yii::app()->request->getParam('cost_type',0);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));

		if ($cost_type==0) {//日
			$time =$time;
			$m = substr($time, 0,7);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where pay_type=0 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=1 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$m.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';


			$db = Yii::app()->db;
			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.logid,msl.material_id,msl.dpid';
		} elseif($cost_type==1){//月
			$time =$month;
			$m = substr($time, 5,2);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

			$begin_time =$month.'-01 00:00:00';
			$end_time =$month.'-'.$d.' 23:59:59';
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where  delete_flag=0 and  dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';
			$db = Yii::app()->db;


			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';

		} elseif($cost_type==2){//年
			$time =$year;
			$begin_time =$year.'-01-01 00:00:00';
			$end_time =$year.'-12-31 23:59:59';
			$sql = 'select * from nb_costs where delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"';
			
			$db = Yii::app()->db;

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';
		}
		$model1 =Yii::app()->db->createCommand($sql)->queryAll();

		$model2 =Yii::app()->db->createCommand($sql0)->queryAll();



		// p($model3);
		$this->render('costsMaterialReport',array(
				'model1'=>$model1,
				'model2'=>$model2,
				'cost_type'=>$cost_type,
				'time'=>$time,
				'month'=>$month,
				'year'=>$year,
		));
	}

	public function actionCostsDayReport(){
		$cost_type = Yii::app()->request->getParam('cost_type',0);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));

		if ($cost_type==0) {//日
			$time =$time;
			$m = substr($time, 0,7);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where pay_type=0 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=1 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$m.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';


			$db = Yii::app()->db;

		} elseif($cost_type==1){//月
			$time =$month;
			$m = substr($time, 5,2);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

			$begin_time =$month.'-01 00:00:00';
			$end_time =$month.'-'.$d.' 23:59:59';
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where  delete_flag=0 and  dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';
			$db = Yii::app()->db;



		} elseif($cost_type==2){//年
			$time =$year;
			$begin_time =$year.'-01-01 00:00:00';
			$end_time =$year.'-12-31 23:59:59';
			$sql = 'select * from nb_costs where delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"';
			
			$db = Yii::app()->db;

		}
		$model1 =Yii::app()->db->createCommand($sql)->queryAll();




		// p($model3);
		$this->render('costsDayReport',array(
				'model1'=>$model1,
				'cost_type'=>$cost_type,
				'time'=>$time,
				'month'=>$month,
				'year'=>$year,
		));
	}

	public function actionCostsReportExport(){
        $objPHPExcel = new PHPExcel();
		$cost_type = Yii::app()->request->getParam('cost_type',0);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));

		if ($cost_type==0) {//日
			$time =$time;
			$m = substr($time, 0,7);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where pay_type=0 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=1 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$m.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';


			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$time.' 00:00:00" and k.create_at <="'.$time.' 23:59:59" group by k.user_id,k.account_no,k.create_at';
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$time.' 00:00:00" and create_at <="'.$time.' 23:59:59" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc,day(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.logid,msl.material_id,msl.dpid';
		} elseif($cost_type==1){//月
			$time =$month;
			$m = substr($time, 5,2);//date('n')
			$y = substr($time, 0,4);//date('Y')
			$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);

			$begin_time =$month.'-01 00:00:00';
			$end_time =$month.'-'.$d.' 23:59:59';
			$y = substr($time, 0,4);//date('Y')
			$sql = 'select * from nb_costs where  delete_flag=0 and  dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"'
				.' union'
				.' select * from nb_costs where pay_type=2 and dpid = '.$this->companyId.' and happen_at like "%'.$y.'%"';
			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.user_id,k.account_no,k.create_at';
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc,month(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';

		} elseif($cost_type==2){//年
			$time =$year;
			$begin_time =$year.'-01-01 00:00:00';
			$end_time =$year.'-12-31 23:59:59';
			$sql = 'select * from nb_costs where delete_flag=0 and dpid = '.$this->companyId.' and happen_at like "%'.$time.'%"';
			
			$db = Yii::app()->db;
			$sqll = 'select k.lid from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$this->companyId.' and k.create_at >="'.$begin_time.'" and k.create_at <="'.$end_time.'" group by k.user_id,k.account_no,k.create_at';
			// p($sqll);
			$orders = $db->createCommand($sqll)->queryAll();
			$ords ='0000000000';
			foreach ($orders as $order){
				$ords = $ords .','.$order['lid'];
			}
			$sql1 = 'select k.* from(select year(create_at) as y_all,month(create_at) as m_all,day(create_at) as d_all, sum(number) as all_number,count(account_no) as all_account,sum(should_total) as all_realprice,sum(reality_total) as all_originalprice from nb_order where create_at >="'.$begin_time.'" and create_at <="'.$end_time.'" and order_status in(3,4,8) and dpid in('.$this->companyId.') and lid in('.$ords.') group by year(create_at) asc) k';

			$sql0 = 'select pm.material_name,msl.logid,msl.material_id,sum(msl.stock_num) as num,msl.unit_price from `nb_material_stock_log` msl left join nb_product_material pm on(msl.material_id=pm.lid) where  msl.type=1 and msl.dpid='.$this->companyId.' and msl.create_at like "%'.$time.'%" group by msl.material_id,msl.dpid';
		}
		$model1 =Yii::app()->db->createCommand($sql)->queryAll();

		$model2 =Yii::app()->db->createCommand($sql0)->queryAll();

		$model3 =Yii::app()->db->createCommand($sql1)->queryAll();



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


        if($cost_type==0){
			$fanshi = yii::t('app','日均/元(保留2位)');
		}elseif($cost_type==1){
			$fanshi = yii::t('app','单月/元(保留2位)');
		}elseif($cost_type==2){
			$fanshi = yii::t('app','整年/元(保留2位)');
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','店铺营业成本管控表'))
        ->setCellValue('A2',yii::t('app','查询：'))
        ->setCellValue('A3',yii::t('app','项目'))
        ->setCellValue('B3',yii::t('app','描述'))
        ->setCellValue('C3',yii::t('app','日期'))
        ->setCellValue('D3',yii::t('app','类型'))
        ->setCellValue('E3',yii::t('app','款项/元'))
        ->setCellValue('F3',$fanshi)
        ->setCellValue('G3',yii::t('app',''));
        $j=4;
        $pay = 0;
		$m = substr($time, 5,2);//date('n')
		$y = substr($time, 0,4);//date('Y')
		if($cost_type!=2):
			$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);
		endif;

		//额外支出
		if($model1):

		foreach($model1 as $v){

		if($v['pay_type']==0){
			$str = '当日支出';
		}elseif($v['pay_type']==1){
			$str = '单月支出';
		}elseif($v['pay_type']==2){
			$str = '整年支出';
		}
		if($cost_type==0){
			if($v['pay_type']==0){
				$price = $v['price'];
				$pay += $v['price'];
			}elseif($v['pay_type']==1){
				$price = sprintf("%.2f", $v['price']/$d);
				$pay += $v['price'];
			}elseif($v['pay_type']==2){
				$price = sprintf("%.2f", $v['price']/365);
				$pay += $v['price']/365;
			}
		}elseif($cost_type==1){
			if($v['pay_type']==0){
				$price = $v['price'];
				$pay += $v['price'];
			}elseif($v['pay_type']==1){
				$price = sprintf("%.2f", $v['price']);
				$pay += $v['price'];
			}elseif($v['pay_type']==2){
				$price = sprintf("%.2f", $v['price']/12);
				$pay += $v['price']/12;
			}
		}elseif($cost_type==2){
			if($v['pay_type']==0){
				$price = $v['price'];
				$pay += $v['price'];
			}elseif($v['pay_type']==1){
				$price = sprintf("%.2f", $v['price']);
				$pay += $v['price'];
			}elseif($v['pay_type']==2){
				$price = sprintf("%.2f", $v['price']);
				$pay += $v['price'];
			}
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$j,$v['item'])
        ->setCellValue('B'.$j,$v['description'])
        ->setCellValue('C'.$j,$v['happen_at'])
        ->setCellValue('D'.$j,$str)
        ->setCellValue('E'.$j,$v['price'])
        ->setCellValue('F'.$j,$price)
        ->setCellValue('G'.$j,'');
		//细边框引用
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->applyFromArray($linestyle);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$j++;
		}
		$a=$j;
		endif;

		// 原料
		if($model2):
		foreach($model2 as $v){
		if($cost_type==0){
			$material_name = $v['material_name'].'(批次-'.$v['logid'].')';
		}else{
			$material_name = $v['material_name'];
		}
		if($cost_type==0){
			$str = '当日支出';
		}elseif($cost_type==1){
			$str = '单月支出';
		}elseif($cost_type==2){
			$str = '整年支出';
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$j,$material_name)
        ->setCellValue('B'.$j,'原料消耗成本')
        ->setCellValue('C'.$j,$time)
        ->setCellValue('D'.$j,$str)
        ->setCellValue('E'.$j,sprintf("%.2f", $v['unit_price']*$v['num']))
        ->setCellValue('F'.$j,sprintf("%.2f", $v['unit_price']*$v['num']))
        ->setCellValue('G'.$j,'');

        $pay += $v['unit_price']*$v['num'];
		//细边框引用
		$objPHPExcel->getActiveSheet()->getStyle('A'.$a.':G'.$j)->applyFromArray($linestyle);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A'.$a.':G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$j++;
		}
		$b = $j;
		else:
		$b = $j;
		endif;
		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F'.$b,'成本(支出)')
        ->setCellValue('G'.$b,'-'.$pay.'元');



		//收入
		$in = 0;
		if($model3):
		foreach($model3 as $v){
		$j++;
		$c=$j;

		if($cost_type==0){
			$str = '日营业收入';
		}elseif($cost_type==1){
			$str = '月营业收入';
		}elseif($cost_type==2){
			$str = '年营业收入';
		}
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$j,'营业收入')
        ->setCellValue('B'.$j,'营业收入')
        ->setCellValue('C'.$j,$time)
        ->setCellValue('D'.$j,$str)
        ->setCellValue('E'.$j,$v['all_realprice'])
        ->setCellValue('F'.$j,$v['all_realprice'])
        ->setCellValue('G'.$j,'');

        $in += $v['all_realprice'];
		//细边框引用
		$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':G'.$j)->applyFromArray($linestyle);
		//设置字体靠左
		$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		}
		endif;
        $c=$j+1;
        $j=$j+1;

		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F'.$c,'收益(收入[含退款])')
        ->setCellValue('G'.$c,'+'.sprintf("%.2f", $in).'元');
        $income = $in-$pay;
        $j=$j+1;
		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('F'.$j,'利润')
        ->setCellValue('G'.$j,sprintf("%.2f", $income).'元');


		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$j)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($linestyle);
		//加粗字体
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':G'.$b)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$c.':G'.$c)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getFont()->setBold(true);
		//设置字体垂直居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':G'.$b)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$c.':G'.$c)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		//设置字体水平居中
		$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':G'.$b)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$c.':G'.$c)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="成本管控表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	public function actionCreate(){
		$type = Yii::app()->request->getParam('type',0);
		$cost_type = Yii::app()->request->getParam('cost_type',3);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));
		$model = new Costs ;
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('Costs');
				$se=new Sequence("costs");
	            $lid = $se->nextval();
	            $model->lid = $lid;
	            $model->dpid = $this->companyId;
	            $model->create_at = date('Y-m-d H:i:s');
	            $model->update_at = date('Y-m-d H:i:s');
	            $model->item = $formdata['item'];
	            $model->description = $formdata['description'];
	            $model->happen_at = $formdata['happen_at'];
	            $model->price = $formdata['price'];
	            $model->pay_type = $formdata['pay_type'];
	        	if ($model->save()) {
					Yii::app()->user->setFlash('success' ,yii::t('app', '添加成功'));
					if ($cost_type == 0) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}
					}else if ($cost_type == 1) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}
					}else if ($cost_type == 2) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'year'=>$year));
						}
					}
				}else{
	                Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败,请重试'));
	                if ($cost_type == 0) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}
					}else if ($cost_type == 1) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}
					}else if ($cost_type == 2) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'year'=>$year));
						}
					}
	        	}
		}
		$this->render('create',array(
			'model'=>$model,
			'cost_type'=>$cost_type,
			'type'=>$type,
			'time'=>$time,
			'month'=>$month,
			'year'=>$year,
		));
	}

	public function actionUpdate(){
		$type = Yii::app()->request->getParam('type',0);
		$cost_type = Yii::app()->request->getParam('cost_type',3);
		$time = Yii::app()->request->getParam('time',date('Y-m-d',time()));
		$month = Yii::app()->request->getParam('month',date('Y-m',time()));
		$year = Yii::app()->request->getParam('year',date('Y',time()));
		$lid = Yii::app()->request->getParam('lid');
		$model =Costs::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$lid,':dpid'=>$this->companyId));
		if(Yii::app()->request->isPostRequest) {
			$formdata = Yii::app()->request->getPost('Costs');
	            $model->update_at = date('Y-m-d H:i:s');
	            $model->item = $formdata['item'];
	            $model->description = $formdata['description'];
	            $model->happen_at = $formdata['happen_at'];
	            $model->price = $formdata['price'];
	            $model->pay_type = $formdata['pay_type'];
				if ($model->save()) {
					Yii::app()->user->setFlash('success' ,yii::t('app', '编辑成功'));
					if ($cost_type == 0) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}
					}else if ($cost_type == 1) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}
					}else if ($cost_type == 2) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'year'=>$year));
						}
					}
				}else{
	                Yii::app()->user->setFlash('error' ,yii::t('app', '编辑失败,请重试'));
	                if ($cost_type == 0) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'time'=>$time));
						}
					}else if ($cost_type == 1) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}
					}else if ($cost_type == 2) {
						if ($type == 1) {
							$this->redirect(array('costs/costsDayReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'month'=>$month));
						}else{
							$this->redirect(array('costs/costsReport' , 'companyId' => $this->companyId,'cost_type'=>$cost_type,'year'=>$year));
						}
					}
	        	}
		}
		$this->render('create',array(
			'model'=>$model,
			'cost_type'=>$cost_type,
			'type'=>$type,
			'time'=>$time,
			'month'=>$month,
			'year'=>$year,
		));
	}

	public function actionDelete(){
		$type = Yii::app()->request->getParam('type',0);
		$lid = Yii::app()->request->getParam('lid');
		$model =Costs::model()->find('lid=:lid and dpid=:dpid and delete_flag=0',array(':lid'=>$lid,':dpid'=>$this->companyId));
        $model->update_at = date('Y-m-d H:i:s');
        $model->delete_flag=1;
		if ($model->save()) {
			echo json_encode(1);
		}else{
			echo json_encode(0);
    	}
	}

}
