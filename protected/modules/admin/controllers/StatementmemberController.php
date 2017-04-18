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