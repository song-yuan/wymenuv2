<?php
class PosController extends BackendController
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
    public function actionIndex(){
        $companyId = Yii::app()->request->getParam('companyId');
       
        $pos_type = Yii::app()->request->getParam('pos_type');
        $status = Yii::app()->request->getParam('status');

        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $sql = "select dpid from nb_company where  delete_flag = 0 and type = 1 and comp_dpid = ".$companyId;
        $dpid = Yii::app()->db->createCommand($sql)->queryAll();
        $str ='';
        if(!empty($dpid)){
            foreach ($dpid as $val){
                if($str == ''){
                    $str .=$val['dpid']; 
                }else{
                     $str .= ",".$val['dpid'];
                }                  
            }
        }
        if($str !=''){ 
            $sql2 = "select pad_setting_id from nb_pad_setting_detail where delete_flag = 0 and dpid in ( ".$str.")";
            $pos_detail = Yii::app()->db->createCommand($sql2)->queryAll();
        }
        $str2 ='';
        if(!empty($pos_detail)){
            foreach ($pos_detail as $val2){
                if($str2 == ''){
                    $str2 .=$val2['pad_setting_id']; 
                }else{
                     $str2 .= ",".$val2['pad_setting_id'];
                }                  
            }
        }
       
        $criteria = new CDbCriteria;
        $criteria->with = array('detail','company');
        $criteria->addCondition("t.delete_flag = 0 " );
        if($str !=''){ 
            $criteria->addCondition("t.dpid in (".$str.")");
        }else{
            $criteria->addCondition("t.dpid =".$companyId);
        }
        $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
        $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
        
        if($pos_type == 1){
            $criteria->addCondition("t.pad_sales_type = 0");
        }
        if($pos_type == 2){
            $criteria->addCondition("t.pad_sales_type = 1");
        }
        if($str2!=''){
            if($status == 1){
                $criteria->addCondition("t.lid not in (".$str2.")");
            }
            if($status == 2){
                $criteria->addCondition("t.lid  in (".$str2.")");
            }
        }
        $models = PadSetting::model()->findAll($criteria); 
        
        $this->render('index',array(
                                'models'=>$models,
                                'pos_type'=>$pos_type,
                                'status'=>$status,
                                'begin_time'=>$begin_time,
                                'end_time'=>$end_time,
                               
                ));
    }
    public function actionExport(){
        $objPHPExcel = new PHPExcel();
        $pos_name = '';
        $companyId = Yii::app()->request->getParam('companyId');
        $pos_type = Yii::app()->request->getParam('pos_type');
        $status = Yii::app()->request->getParam('status');

        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $sql = "select dpid from nb_company where  delete_flag = 0 and comp_dpid = ".$companyId;
        $dpid = Yii::app()->db->createCommand($sql)->queryAll();
        $str ='';
        if(!empty($dpid)){
            foreach ($dpid as $val){
                if($str == ''){
                    $str .=$val['dpid']; 
                }else{
                     $str .= ",".$val['dpid'];
                }
                  
            }
        }
         
        $sql2 = "select pad_setting_id from nb_pad_setting_detail where delete_flag = 0 and dpid in ( ".$str.")";
        $pos_detail = Yii::app()->db->createCommand($sql2)->queryAll();
        $str2 ='';
        if(!empty($pos_detail)){
            foreach ($pos_detail as $val2){
                if($str2 == ''){
                    $str2 .=$val2['pad_setting_id']; 
                }else{
                     $str2 .= ",".$val2['pad_setting_id'];
                }
                  
            }
        }
       
        $criteria = new CDbCriteria;
        $criteria->with = array('detail','company');
        $criteria->addCondition("t.delete_flag = 0 and t.dpid in (".$str.")" );
        
        if($pos_type == 1){
            $pos_name = '单屏、';
            $criteria->addCondition("t.pad_sales_type = 0");
        }
        if($pos_type == 2){
            $pos_name = '双屏、';
            $criteria->addCondition("t.pad_sales_type = 1");
        }
        if($status == 0){
            $status_name = '收银机、';
            $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
            $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
        }
        if($status == 1){
            $status_name = '未使用收银机、';
            $criteria->addCondition("t.lid not in (".$str2.")");
            $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
            $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
        }
        if($status == 2){
            $status_name = '已使用收银机、';
            $criteria->addCondition("t.lid  in (".$str2.")");
            $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
        $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
        }
        $pages = new CPagination(PadSetting::model()->count($criteria));
        $pages->applyLimit($criteria);
        
        $models = PadSetting::model()->findAll($criteria); 
        
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
        ->setCellValue('A1',yii::t('app','收银机统计').yii::t('app','生成时间：').date('m-d H:i',time()))
        ->setCellValue('A2',yii::t('app','查询条件：').$pos_name.$status_name.yii::t('app','时间段：').$begin_time.yii::t('app','  00:00:00 至 ').$end_time."  23:59:59")
        ->setCellValue('A3',yii::t('app','分店'))
        ->setCellValue('B3',yii::t('app','类型'))
        ->setCellValue('C3',yii::t('app','POS序列号'))
        ->setCellValue('D3',yii::t('app','创立时间'))        
        ->setCellValue('E3',yii::t('app','开始使用时间'))
        ->setCellValue('F3',yii::t('app','收银机地址'));
        $j=4;
		foreach($models as $v){
			
			
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$j,$v['company']['company_name'])
                        ->setCellValue('B'.$j,$v['pad_sales_type']==0?'单屏':'双屏')
                        ->setCellValue('C'.$j,$v['pad_code'])
                        ->setCellValue('D'.$j,$v['create_at'])        
                        ->setCellValue('E'.$j,empty($v['detail'])?'':$v->detail[0]->create_at)
                        ->setCellValue('F'.$j,empty($v['detail'])?'':$v->detail[0]->content)
                       ;

			//细边框引用
				
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':F'.$j)->applyFromArray($linestyle);
			//设置填充颜色
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j)->getFill()->getStartColor()->setARGB('fae9e5');
			//设置字体靠左
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':F'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
			$j++;
		}
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="收银机统计表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
        
    }
}
