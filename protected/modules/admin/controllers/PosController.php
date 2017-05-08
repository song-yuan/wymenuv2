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
        $criteria->order = 't.dpid asc';
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
        $criteria->addCondition("t.delete_flag = 0" );
        $criteria->order = 't.dpid asc';
        if($str !=''){ 
            $criteria->addCondition("t.dpid in (".$str.")");
        }else{
            $criteria->addCondition("t.dpid =".$companyId);
        }
        $criteria->addCondition("t.create_at >='$begin_time 00:00:00'");
        $criteria->addCondition("t.create_at <='$end_time 23:59:59'");
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
        }
        if($status == 1){
            $status_name = '未使用收银机、';
            $criteria->addCondition("t.lid not in (".$str2.")");            
        }
        if($status == 2){
            $status_name = '已使用收银机、';
            $criteria->addCondition("t.lid  in (".$str2.")");
        }
        
        $models = PadSetting::model()->findAll($criteria); 
        
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
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃餐饮管理系统对账单'))
        ->setCellValue('A2',yii::t('app','查询：').$pos_name.$status_name.yii::t('app','时间段：').$begin_time.yii::t('app','  00:00:00 至 ').$end_time."  23:59:59")
        ->setCellValue('A3',yii::t('app','店名'))
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
    public function actionUsed(){
        $companyId = Yii::app()->request->getParam('companyId');     
        $pos_type = Yii::app()->request->getParam('pos_type');        
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        
        $sql = "select dpid from nb_company where  delete_flag = 0 and type = 1 and comp_dpid = ".$companyId;
        $dpid = Yii::app()->db->createCommand($sql)->queryAll();
        $models = Array();
        if(!empty($dpid)){
            foreach ($dpid as $val){
               
                if($pos_type == 1){
                   
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0 and t1.pad_sales_type = 0"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid = ".$val['dpid']." group by t.pad_setting_id ORDER BY  poscreate_at ASC";

                }elseif($pos_type == 2){
                   
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0 and t1.pad_sales_type = 1"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid = ".$val['dpid']." group by t.pad_setting_id ORDER BY poscreate_at ASC";

                }else{
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid =".$val['dpid']." group by t.pad_setting_id ORDER BY poscreate_at ASC";

                }
            $models[$val['dpid']] = Yii::app()->db->createCommand($all_sql)->queryAll();
            }
        
        }

     
        $this->render('used',array(
                                'models'=>$models,
                                'pos_type'=>$pos_type,
                                'dpid'=>$dpid,
                                'begin_time'=>$begin_time,
                                'end_time'=>$end_time,
                               
                ));
    }
    public function actionUsedExport(){
        $objPHPExcel = new PHPExcel();
        $pos_name = '';
        $companyId = Yii::app()->request->getParam('companyId');
        $pos_type = Yii::app()->request->getParam('pos_type');        
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        
        $sql = "select dpid from nb_company where  delete_flag = 0 and type = 1 and comp_dpid = ".$companyId;
        $dpid = Yii::app()->db->createCommand($sql)->queryAll();
        $models = Array();
        if(!empty($dpid)){
            foreach ($dpid as $val){
               
                if($pos_type == 1){
                   
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0 and t1.pad_sales_type = 0"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid = ".$val['dpid']." group by t.pad_setting_id ORDER BY  poscreate_at ASC";

                }elseif($pos_type == 2){
                   
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0 and t1.pad_sales_type = 1"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid = ".$val['dpid']." group by t.pad_setting_id ORDER BY poscreate_at ASC";

                }else{
                    $all_sql = "select t.create_at as poscreate_at,t.content,t1.pad_sales_type, t1.pad_code, com.company_name,com.create_at as comcreate_at from nb_pad_setting_detail t,nb_pad_setting  t1,nb_company com  "
                        . " where t.pad_setting_id = t1.lid and t1.delete_flag = 0"
                        . " and  com.dpid = t.dpid"
                        . " and  t.delete_flag = 0 and  t.dpid =".$val['dpid']." group by t.pad_setting_id ORDER BY poscreate_at ASC";

                }
            $models[$val['dpid']] = Yii::app()->db->createCommand($all_sql)->queryAll();
            }
        
        }
        
       
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
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃餐饮管理系统对账单'))
        ->setCellValue('A2',yii::t('app','查询：').$pos_name.yii::t('app','时间段：').$begin_time.yii::t('app','  00:00:00 至 ').$end_time."  23:59:59")
        ->setCellValue('A3',yii::t('app','店名'))
        ->setCellValue('B3',yii::t('app','店铺创立时间'))       
        ->setCellValue('C3',yii::t('app','类型'))
        ->setCellValue('D3',yii::t('app','POS序列号'))      
        ->setCellValue('E3',yii::t('app','收银机开始使用时间'))
        ->setCellValue('F3',yii::t('app','收银机地址'))
        ->setCellValue('G3',yii::t('app','排序'));
        $j=4;
        if($models){
            foreach ($models as $key => $val) {
                $k=1;
                foreach ($models[$key] as $v) { 
                if( strtotime($v['poscreate_at'])>strtotime($begin_time) && strtotime($v['poscreate_at'])<strtotime($end_time+" 23 hours 59 m 59 s") ){

                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$j,$v['company_name'])
                    ->setCellValue('B'.$j,$v['comcreate_at'])        
                    ->setCellValue('C'.$j,$v['pad_sales_type']==0?'单屏':'双屏')
                    ->setCellValue('D'.$j,$v['pad_code'])
                    ->setCellValue('E'.$j,$v['poscreate_at'])        
                    ->setCellValue('F'.$j,$v['content']) 
                    ->setCellValue('G'.$j,$k);  

                    //细边框引用

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->applyFromArray($linestyle);

                    //设置字体靠左
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $j++;
                }
                $k++;
                
                }
            }
        }
		//冻结窗格
		$objPHPExcel->getActiveSheet()->freezePane('A4');
		//合并单元格
		$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
		//单元格加粗，居中：
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$j)->applyFromArray($lineBORDER);//大边框格式引用
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		//输出
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$filename="收银机统计表（".date('m-d',time())."）.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
        
    }
}
