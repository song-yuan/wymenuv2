<?php
class MobileMessageController extends BackendController
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
    public function actionIndex(){       
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));

        $criteria = new CDbCriteria;
        $criteria->condition =  't.dpid=:dpid  and t.create_at >="'.$begin_time.' 00:00:00" and t.create_at <="'.$end_time.' 23:59:59" ';
        $criteria->order = ' t.lid desc ';
        $criteria->params[':dpid']=$this->companyId;

        $pages = new CPagination(MobileMessage::model()->count($criteria));        
        $pages->applyLimit($criteria);

        $models = MobileMessage::model()->findAll($criteria);
        
        $success = 0;
        $sql = 'SELECT lid FROM nb_mobile_message WHERE dpid = ' .$this->companyId .' and status = 1 and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" ';
        $result = Yii::app()->db->createCommand($sql)->queryAll();  
        if(!empty($result)){
        $success=count($result);}
        
        $this->render('index',array(		
		'models'=> $models,
                'pages' => $pages,
		'begin_time'=>$begin_time,
		'end_time'=>$end_time,	
                'success' => $success
		));
    }
    
    public function actionExport(){
		$objPHPExcel = new PHPExcel();
		//$uid = Yii::app()->user->id;
		$begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
		$end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
                $dpid = Yii::app()->request->getParam('companyId');
	
		$sql = 'select * from nb_mobile_message where dpid='.$dpid.' and create_at >="'.$begin_time.' 00:00:00" and create_at <="'.$end_time.' 23:59:59" ';
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
		->setCellValue('A1','短信统计表')
		->setCellValue('A2',yii::t('app','报表查询时间段：').$begin_time.yii::t('app',' 至 ').$end_time."  ".yii::t('app','报表生成时间：').date('m-d h:i',time()))
                ->setCellValue('A3','序号')
                ->setCellValue('B3','时间')
		->setCellValue('C3','手机号')
		->setCellValue('D3','验证码')
		->setCellValue('E3','类型')
                ->setCellValue('F3','状态');
		$i=4;
                $a = 0; $b = 0; $c = 0;
		foreach($models as $v){
	
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i,$i-3)
			->setCellValue('B'.$i,$v['create_at'])
			->setCellValue('C'.$i,$v['mobile'])
			->setCellValue('D'.$i,$v['code'])
                        ->setCellValue('E'.$i,$v['type']=='0'?'新赠信息':'修改信息')
                        ->setCellValue('F'.$i,$v['status']=='0'?'失败':'成功');
                        
                       
                                   
			//细边框引用
				
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':F'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$i++;
                        $a++;
                        $v['status'] == "1" ? $b++ : $c++;
		}
                $j = $i + 1;
                $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$j,"总计短信：".$a."条")
                        ->setCellValue('B'.$j,"成功短信：".$b."条")
                        ->setCellValue('C'.$j,"失败短信：".$c."条");
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$j)->applyFromArray($lineBORDER);//大边框格式引用
		// 将A1单元格设置为加粗，居中
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
		$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($linestyle);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($linestyle);
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
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getFill()->getStartColor()->setARGB('fdfc8d');
		$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FFB848');
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A2:C2')->getFill()->getStartColor()->setARGB('FFB848');
		//设置每列宽度
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="短信统计表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	
	
	}
}
