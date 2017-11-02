<?php

class PoscountsController extends BackendController
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
        $sql = "select comp_dpid from nb_company where  delete_flag = 0 and type = 1 and dpid = ".$this->companyId;
        $cdpid = Yii::app()->db->createCommand($sql)->queryRow();
        if ($cdpid) {
            $cdpid = $cdpid['comp_dpid'];
        }else{
            $cdpid = $this->companyId;
        }
        $cdpid = Yii::app()->request->getParam('cdpid',$cdpid);
        $cname = Yii::app()->request->getParam('cname');
        $pos_count = Yii::app()->request->getParam('pos_count',2);
        $pos_used = Yii::app()->request->getParam('pos_used',1);
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $company = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
         //p($model);
        $models=0;
        $CompanyName=null;
        if($cdpid){

            

            // $CompanyName = Company::model()->findByPk($cdpid)->company_name;
            // p($CompanyName);
            //查询子公司POS机数据
                $time = $end_time.' 23:59:59';
                $use_time ='';
                if ($pos_count==2) {
                    $status='';
                }else if($pos_count==1){
                    $status=' and pss.status=1';
                }else if($pos_count==0){
                    $status=' and pss.status=0';
                }
                if ($pos_used==2) {
                    $use_status='';
                }else if($pos_used==1){
                    $use_status=' and pss.use_status=1';
                    $use_time =' and unix_timestamp(pss.used_at) < unix_timestamp("'.$time.'")';
                }else if($pos_used==0){
                    $use_status=' and pss.use_status=0';
                }
                if ($cname != '') {
                    $cname = ' and company_name like "%'.$cname.'%"';
                }
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.create_at as poscreate_at,pss.used_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                        .' left join nb_company c on(c.dpid = t.dpid ) '
                        .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid '.$use_time.$use_status.$status.') '
                        .' left join (select a.lid,a.content,a.dpid,a.pad_setting_id from nb_pad_setting_detail a where  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_pad_setting_detail b where b.dpid=a.dpid and b.pad_setting_id=a.pad_setting_id)) psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid) '
                        .' where t.delete_flag =0 and t.dpid in( '
                                .' select dpid from nb_company where comp_dpid ='.$cdpid.$cname.' and delete_flag = 0  and type = 1)'
                        .' order by c.company_name asc';
                     
                $models = Yii::app()->db->createCommand($sql)->queryALL();

        // p($models);
        }
            $this->render('hqindex',array(
                    'companys'=>$company,
                    'cdpid'=>$cdpid,
                    'models'=>$models,
                    'pos_count'=>$pos_count,
                    'pos_used'=>$pos_used,
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
                            $model->saveAttributes(array('status'=>$status));
                        }else{
                            echo 0;//结算失败
                        }

                    }
                    echo 1;//返回1用于结果提示
            } else {
                    Yii::app()->user->setFlash('error' ,yii::t('app', '请选择要操作的项目'));
                    $this->redirect(array('poscount/hqindex' , 'companyId' => $companyId)) ;
            }
    }



    public function actionPoscountExport(){
        $objPHPExcel = new PHPExcel();
        //查询总公司
        $sql = "select comp_dpid from nb_company where  delete_flag = 0 and type = 1 and dpid = ".$this->companyId;
        $cdpid = Yii::app()->db->createCommand($sql)->queryRow();
        if ($cdpid) {
            $cdpid = $cdpid['comp_dpid'];
        }else{
            $cdpid = $this->companyId;
        }
        $cdpid = Yii::app()->request->getParam('cdpid',$cdpid);
        $cname = Yii::app()->request->getParam('cname');
        $pos_count = Yii::app()->request->getParam('pos_count',2);
        $pos_used = Yii::app()->request->getParam('pos_used',1);
        $begin_time = Yii::app()->request->getParam('begin_time',date('Y-m-d',time()));
        $end_time = Yii::app()->request->getParam('end_time',date('Y-m-d',time()));
        $company = Yii::app()->db->createCommand("select * from nb_company where type=0 and delete_flag =0")->queryALL();
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
            $use_time ='';
            if ($pos_count==2) {
                $status='';
            }else if($pos_count==1){
                $status=' and pss.status=1';
            }else if($pos_count==0){
                $status=' and pss.status=0';
            }
            if ($pos_used==2) {
                $use_status='';
            }else if($pos_used==1){
                $use_status=' and pss.use_status=1';
                $use_time =' and unix_timestamp(pss.used_at) < unix_timestamp("'.$time.'")';
            }else if($pos_used==0){
                $use_status=' and pss.use_status=0';
            }
            if ($cname != '') {
                $cname = ' and company_name like "%'.$cname.'%"';
            }
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.create_at as poscreate_at,pss.used_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                        .' left join nb_company c on(c.dpid = t.dpid ) '
                        .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid '.$use_time.$use_status.$status.') '
                        .' left join (select a.lid,a.content,a.dpid,a.pad_setting_id from nb_pad_setting_detail a where  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_pad_setting_detail b where b.dpid=a.dpid and b.pad_setting_id=a.pad_setting_id)) psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid) '
                        .' where t.delete_flag =0 and t.dpid in( '
                                .' select dpid from nb_company where comp_dpid ='.$cdpid.$cname.' and delete_flag = 0  and type = 1)'
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
        if ($pos_used == 1) {
            $str4 = '之前已使用未结算';
        }else{
            $str4 = '';
        }
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃餐饮管理系统对账单'))
        ->setCellValue('A2',yii::t('app','店名'))
        ->setCellValue('B2',yii::t('app','联系人'))
        ->setCellValue('C2',yii::t('app','手机号'))
        ->setCellValue('D2',yii::t('app','店铺创建时间'))
        ->setCellValue('E2',yii::t('app','模式'))
        ->setCellValue('F2',yii::t('app','POS序列号'))
        ->setCellValue('G2',yii::t('app','POS序列号创建时间'))
        ->setCellValue('H2',yii::t('app','收银机开始使用时间'))
        ->setCellValue('I2',yii::t('app','收银机MAC地址'))
        ->setCellValue('J2',yii::t('app','排序'))
        ->setCellValue('A3',yii::t('app',$str4));
        $j=4;
        if($models){
            if ($pos_used == 1) {
                foreach ($models as $key => $v) {
                    if( (strtotime($v['used_at'])<strtotime($begin_time)) && $v['status']==0 && $v['use_status']==1 ){
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
                        ->setCellValue('G'.$j,$v['poscreate_at'])
                        ->setCellValue('H'.$j,$v['used_at'])
                        ->setCellValue('I'.$j,$v['content'])
                        ->setCellValue('J'.$j,$v['pad_no']);

                        //细边框引用

                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':J'.$j)->applyFromArray($linestyle);

                        //设置字体靠左
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':J'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                        $j++;
                    }
                }
            }
        }
        $aa=$j;
        $jj=$j;

        if($pos_count=='0'){
            $str1 = '未结算';
        }else if($pos_count=='1'){
            $str1 = '已结算';
        }else{
            $str1 = '全部';
        }
        if($pos_used=='0'){
            $str2 = '未使用)';
        }else if($pos_used=='1'){
            $str2 = '已使用'.yii::t('app',')时间段：').$begin_time.yii::t('app','  00:00:00 至 ').$end_time."  23:59:59";
        }else{
            $str2 = '全部)';
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$aa,yii::t('app','查询：(').$str1.$str2);


        if($models){
            foreach ($models as $key => $v) {
                    if($pos_count==2 && $pos_used==2){
                        $ss = true;
                    }else{
                        if((strtotime($v['used_at'])>strtotime($begin_time)) && ( $v['used_at'] < date( "Y-m-d H:i:s", strtotime( $end_time." +1 day")))){
                            $ss = true;
                        }else{
                            $ss = false;
                        }
                    }
                    if( $ss ){
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
                        ->setCellValue('G'.$jj,$v['poscreate_at'])
                        ->setCellValue('H'.$jj,$v['used_at'])
                        ->setCellValue('I'.$jj,$v['content']=='undefined')
                        ->setCellValue('J'.$jj,$v['pad_no']);

                        //细边框引用

                        $objPHPExcel->getActiveSheet()->getStyle('A'.$jj.':J'.$jj)->applyFromArray($linestyle);

                        //设置字体靠左
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$jj.':J'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    }
            }
        }
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A3');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
        //单元格加粗，居中：
        // $objPHPExcel->getActiveSheet()->getStyle('A1:J'.$jj)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$aa.':J'.$aa)->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$aa.':J'.$aa)->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="收银机统计表---".$CompanyName."（".date('m-d',time())."）.xls";
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

        $sql = "select comp_dpid from nb_company where  delete_flag = 0 and type = 1 and dpid = ".$companyId;
        $cdpid = Yii::app()->db->createCommand($sql)->queryRow();
        if ($cdpid) {
            $cdpid = $cdpid['comp_dpid'];
        }else{
            $cdpid = $this->companyId;
        }
        // p($cdpid);
        $models = Array();
        // $pos_type = 0;
            if($pos_type == 1){
                $status =  ' and screen_type = 0';
            }elseif($pos_type == 2){
                $status =  ' and screen_type = 1';
            }else{
                $status =  '';
            }
            // p($status);
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.create_at as poscreate_at,pss.used_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                    .' left join nb_company c on(c.dpid = t.dpid ) '
                    .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid and unix_timestamp(pss.used_at) < unix_timestamp("'.$end_time.' 23:59:59") and unix_timestamp(pss.used_at) > unix_timestamp("'.$begin_time.' 00:00:00")) '
                    .' left join (select a.lid,a.content,a.dpid,a.pad_setting_id from nb_pad_setting_detail a where  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_pad_setting_detail b where b.dpid=a.dpid and b.pad_setting_id=a.pad_setting_id)) psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid) '
                    .' where t.delete_flag =0 and t.dpid in( '
                            .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0 and type = 1)'
                            .'  and pss.use_status = 1'
                            .$status
                    .' order by c.company_name asc';




            $models = Yii::app()->db->createCommand($sql)->queryAll();




        $this->render('used',array(
                                'models'=>$models,
                                'pos_type'=>$pos_type,
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
        $sql = "select comp_dpid from nb_company where  delete_flag = 0 and type = 1 and dpid = ".$companyId;
        $cdpid = Yii::app()->db->createCommand($sql)->queryRow();
        if ($cdpid) {
            $cdpid = $cdpid['comp_dpid'];
        }else{
            $cdpid = $this->companyId;
        }
        // p($cdpid);
        $models = Array();
        // $pos_type = 0;
            if($pos_type == 1){
                $status =  ' and screen_type = 0';
            }elseif($pos_type == 2){
                $status =  ' and screen_type = 1';
            }else{
                $status =  '';
            }
            // p($status);
                $sql ='select  DISTINCT(t.lid),pss.status,pss.use_status,pss.pad_no,pss.create_at as poscreate_at,pss.used_at,psd.content,c.company_name,c.contact_name,c.mobile,c.create_at as comp_create_time,t.* from nb_pad_setting t '
                    .' left join nb_company c on(c.dpid = t.dpid ) '
                    .' left join nb_pad_setting_status pss ON(pss.dpid = t.dpid and pss.pad_setting_id = t.lid and unix_timestamp(pss.used_at) < unix_timestamp("'.$end_time.' 23:59:59") and unix_timestamp(pss.used_at) > unix_timestamp("'.$begin_time.' 00:00:00")) '
                    .' left join (select a.lid,a.content,a.dpid,a.pad_setting_id from nb_pad_setting_detail a where  UNIX_TIMESTAMP(a.create_at) in(select max(UNIX_TIMESTAMP(b.create_at)) from nb_pad_setting_detail b where b.dpid=a.dpid and b.pad_setting_id=a.pad_setting_id)) psd ON(psd.dpid = t.dpid and psd.pad_setting_id = t.lid) '
                    .' where t.delete_flag =0 and t.dpid in( '
                            .' select dpid from nb_company where comp_dpid ='.$cdpid.' and delete_flag = 0 and type = 1)'
                            .'  and pss.use_status = 1'
                            .$status
                    .' order by c.company_name asc';




            $models = Yii::app()->db->createCommand($sql)->queryAll();

        //var_dump($models);exit;

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
        ->setCellValue('F3',yii::t('app','收银机mac地址'))
        ->setCellValue('G3',yii::t('app','排序'));
        $j=4;
        if($models){

                foreach ($models as $v) {

                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$j,$v['company_name'])
                    ->setCellValue('B'.$j,$v['comp_create_time'])
                    ->setCellValue('C'.$j,$v['screen_type']==0?'单屏':'双屏')
                    ->setCellValue('D'.$j,'('.$v['pad_code'].')')
                    ->setCellValue('E'.$j,$v['used_at'])
                    ->setCellValue('F'.$j,$v['content'])
                    ->setCellValue('G'.$j,$v['pad_no']);

                    //细边框引用

                    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->applyFromArray($linestyle);

                    //设置字体靠左
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':G'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $j++;


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