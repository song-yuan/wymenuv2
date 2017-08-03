<?php
class PoscountController extends BackendController
{
    public function beforeAction($action) {
            parent::beforeAction($action);
            if(!$this->companyId) {
                    Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
                    $this->redirect(array('company/index'));
            }
            return true;
    }

    //总部pos机报表
    public function actionHqindex(){
        //查询总公司
        $cdpid = $this->companyId;
        $pos_count = Yii::app()->request->getParam('pos_count',2);
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $model = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
        // p($model);
        $models=0;
        $CompanyName=null;
        if($cdpid){

            //获取ajax数据,总公司的dpid-----子公司的comp_dpid
            //$cdpid = Yii::app()->request->getPost('cdpid');//$_POST['cdpid'];

            $CompanyName = Company::model()->findByPk($cdpid)->company_name;
            // p($CompanyName);
            //查询子公司POS机数据
                $time = $end_time.' 23:59:59';
                if ($pos_count==2) {
                    $status='';
                }else if($pos_count==1){
                    $status=' and pss.status=1';
                }else if($pos_count==0){
                    $status=' and pss.status=0';
                }
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.update_at as posupdate_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                        .' left join nb_company c on(c.dpid = t.dpid ) '
                        .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                        .' left join nb_pad_setting_detail psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid ) '
                        .' where t.delete_flag =0 and t.dpid in( '
                                .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                                .' and unix_timestamp(pss.update_at) < unix_timestamp("'.$time.'")'.$status
                        .' order by c.company_name asc';
                        // .' order by t.lid asc';
                        //echo $sql;exit;
                $models = Yii::app()->db->createCommand($sql)->queryALL();

        // p($models);
        }
            $this->render('hqindex',array(
                    'statu'=>'null',
                    'use_statu'=>'null',
                    'models'=>$models,
                    'pos_count'=>$pos_count,
                    'hqcompany'=>$model,
                    'begin_time'=>$begin_time,
                    'end_time'=>$end_time,
            ));
    }


    //pos机结算
    public function actionCounts(){
            $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
            $ids = Yii::app()->request->getPost('ids');
            $status = Yii::app()->request->getPost('status');
            // var_dump($ids);exit;
            if(!is_array($ids)){
                $ids = array($ids);
            }
            if(!empty($ids)) {
                    foreach ($ids as $id) {
                        $model = PadSettingStatus::model()->find('pad_setting_id=:id ' , array(':id' => (int)$id ,)) ;
                        // p($model_one);
                        // 如果状态表数据存在就更新,如果不存在就创建为结算状态
                        if(!empty($model)) {
                            $model->saveAttributes(array('status'=>$status,'update_at'=>$model->update_at));
                        }else{
                            echo 0;//结算失败
                        }

                    }
                    echo 1;//返回1用于结果提示
            } else {
                    Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要操作的项目'));
                    $this->redirect(array('poscode/index' , 'companyId' => $companyId)) ;
            }
    }
/**
 * 公司联系人,手机号未做导出,,,,,需要加排序,,,
 */


    public function actionPoscountExport(){
        $objPHPExcel = new PHPExcel();
        //查询总公司
        $cdpid = $this->companyId;
        $pos_count = Yii::app()->request->getParam('pos_count',2);
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $model = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
        // p($model);

        if($cdpid){

            //获取ajax数据,总公司的dpid-----子公司的comp_dpid
            //$cdpid = Yii::app()->request->getPost('cdpid');//$_POST['cdpid'];

            $CompanyName = Company::model()->findByPk($cdpid)->company_name;
            // p($CompanyName);
            //查询子公司POS机数据
            $time = $end_time.' 23:59:59';
                if ($pos_count==2) {
                    $status='';
                }else if($pos_count==1){
                    $status=' and pss.status=1';
                }else if($pos_count==0){
                    $status=' and pss.status=0';
                }
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.update_at as posupdate_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                        .' left join nb_company c on(c.dpid = t.dpid ) '
                        .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid ) '
                        .' left join nb_pad_setting_detail psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid ) '
                        .' where t.delete_flag =0 and t.dpid in( '
                                .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0)'
                                .' and unix_timestamp(pss.update_at) < unix_timestamp("'.$time.'")'.$status
                        .' order by c.company_name asc';
                        //echo $sql;exit;
            $models = Yii::app()->db->createCommand($sql)->queryALL();

        }
        // gp($models);
        //var_dump($models);exit;

        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        //设置第2行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
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
        ->setCellValue('A2',yii::t('app','店名'))
        ->setCellValue('B2',yii::t('app','联系人'))
        ->setCellValue('C2',yii::t('app','手机号'))
        ->setCellValue('D2',yii::t('app','店铺创建时间'))
        ->setCellValue('E2',yii::t('app','模式'))
        ->setCellValue('F2',yii::t('app','POS序列号'))
        ->setCellValue('G2',yii::t('app','收银机开始使用时间'))
        ->setCellValue('H2',yii::t('app','收银机MAC地址'))
        ->setCellValue('I2',yii::t('app','排序'))
        ->setCellValue('A3',yii::t('app','之前未结算'));
        $j=4;
        if($models){
            foreach ($models as $key => $v) {
                    if( (strtotime($v['posupdate_at'])<strtotime($begin_time)) && $v['status']==0){
                        if ($v['content']=='undefined') {
                            $v['content']=='';
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$j,$v['company_name'])
                        ->setCellValue('B'.$j,$v['contact_name'])
                        ->setCellValue('C'.$j,$v['mobile'])
                        ->setCellValue('D'.$j,$v['comp_create_time'])
                        ->setCellValue('E'.$j,$v['screen_type']==0?'单屏':'双屏')
                        ->setCellValue('F'.$j,'('.$v['pad_code'].')')
                        ->setCellValue('G'.$j,$v['posupdate_at'])
                        ->setCellValue('H'.$j,$v['content'])
                        ->setCellValue('I'.$j,$v['pad_no']);

                        //细边框引用

                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':I'.$j)->applyFromArray($linestyle);

                        //设置字体靠左
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':I'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                        $j++;
                    }
            }
        }
        $aa=$j;
        $jj=$j;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$aa,yii::t('app','查询：').yii::t('app','时间段：').$begin_time.yii::t('app','  00:00:00 至 ').$end_time."  23:59:59");
        if($models){
            foreach ($models as $key => $v) {
                    if( (strtotime($v['posupdate_at'])>strtotime($begin_time)) && ( $v['posupdate_at'] < date( "Y-m-d H:i:s", strtotime( $end_time." +1 day"))) ){
                        $jj++;
                        if ($v['content']=='undefined') {
                            $v['content']=='';
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$jj,$v['company_name'])
                        ->setCellValue('B'.$jj,$v['contact_name'])
                        ->setCellValue('C'.$jj,$v['mobile'])
                        ->setCellValue('D'.$jj,$v['comp_create_time'])
                        ->setCellValue('E'.$jj,$v['screen_type']==0?'单屏':'双屏')
                        ->setCellValue('F'.$jj,'('.$v['pad_code'].')')
                        ->setCellValue('G'.$jj,$v['posupdate_at'])
                        ->setCellValue('H'.$jj,$v['content'])
                        ->setCellValue('I'.$jj,$v['pad_no']);
                        //细边框引用

                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':I'.$jj)->applyFromArray($linestyle);

                        //设置字体靠左
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':I'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    }
            }
        }
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A3');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:I1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:I3');
        //单元格加粗，居中：
        $objPHPExcel->getActiveSheet()->getStyle('A1:I'.$jj)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$aa.':I'.$aa)->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$aa.':I'.$aa)->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="收银机统计表---".$CompanyName."（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');

    }
}