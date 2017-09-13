<?php

class WechatMemberController extends BackendController {
    public function actions() {
            return array(
                            'upload'=>array(
                                            'class'=>'application.extensions.swfupload.SWFUploadAction',
                                            //注意这里是绝对路径,.EXT是文件后缀名替代符号
                                            'filepath'=>Helper::IenFileName().'.EXT',
                                            //'onAfterUpload'=>array($this,'saveFile'),
                            )
            );
    }

    public function actionList() {

		$this->render('list');
    }
     public function actionSetting() {

		$this->render('setting');
    }
     public function actionMenu() {

       	$this->render('menu');
    }
    public function actionSearchDetail(){
        $num = Yii::app()->request->getParam('num');
        $card_id = Yii::app()->request->getParam('card_id');
        $brand_user_model = '';
        $cupon_model = '';

        $orderPay = '';
        $cashback = 0;


        $criteria = new CDbCriteria;
        $criteria->with = array('point','level','cupon_branduser');
        $criteria->addCondition("t.dpid=".$this->companyId ." or t.weixin_group = ".$this->companyId);
        $criteria->addCondition("t.lid=".$num);

        $brand_user_model = BrandUser::model()->find($criteria);

         $company = Company::model()->find('dpid='.$this->companyId);
         if($company->type==0){
         	$companys = Company::model()->findAll('comp_dpid='.$this->companyId);
         	$companyIds = '';
         	foreach ($companys as $com){
         		$companyIds .= $com->dpid.',';
         	}
         	$companyIds = trim($companyIds,',');
         }else{
         	$companyIds = $this->companyId;
         }
         $criteria1 = new CDbCriteria;
         $criteria1->with = array('order4','company');
         $criteria1->group = 't.order_id';
         $criteria1->addCondition("t.paytype in (8,9,10) and t.remark='".$card_id."' and t.dpid in (".$companyIds.")");

        $orderPay = OrderPay::model()->findAll($criteria1);

        $cupon_model =  Cupon::model()->findAll("t.delete_flag<1 and t.is_available<1 and t.dpid in (".$companyIds.")");

        $this->render('searchdetail',array( 'brand_user_model'=> $brand_user_model,

                                        'cupon_model'=> $cupon_model,
                                        'orderPay'=>$orderPay,
                    )
                    );
    }
    public function actionSearch(){
        $db=Yii::app()->db;
        $companyId = Yii::app()->request->getParam('companyId',"0000000000");
        $more = Yii::app()->request->getPost('more',"0");
        $findsex = Yii::app()->request->getPost('findsex',"%");//性别
        $agefrom = Yii::app()->request->getPost('agefrom',"0");//起始年龄
        $ageto = Yii::app()->request->getPost('ageto',"100");//终止年龄
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");//起始生日
        $birthto = Yii::app()->request->getPost('birthto',"12-31");//终止生日
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0000000000");//会员等级
        $findweixingroup=Yii::app()->request->getPost('findweixingroup',"0000000000");//会员来源店铺
        $noordertime=Yii::app()->request->getPost('noordertime',"%");//未消费时长

        //省 市 地区
        $findprovince=Yii::app()->request->getPost('province',"%");
        $findcity=Yii::app()->request->getPost('city',"%");
        $findarea=Yii::app()->request->getPost('area',"%");

        $pointfrom = Yii::app()->request->getPost('pointfrom',"0");
        $source = Yii::app()->request->getPost('source',"");//来源
        $foucsfrom = Yii::app()->request->getPost('foucsfrom',"");//关注开始时间
        $foucsto = Yii::app()->request->getPost('foucsto',"");//关注结束时间时间

        if($pointfrom==0)
        {
            $pointfrom=-999999;
        }
        $pointto = Yii::app()->request->getPost('pointto',"9999999999");
        $remainfrom = Yii::app()->request->getPost('remainfrom',"0");
        // if($remainfrom==0)
        // {
        //  $remainfrom=-999999;
        // }
        $remainto = Yii::app()->request->getPost('remainto',"9999999999");

        //时间范围
        $datefrom = Yii::app()->request->getPost('datefrom',"2015-01-01");
        $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

        //总消费额范围
        $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
        // if($consumetotalfrom==0)
        // {
        //  $consumetotalfrom=-999999;
        // }
        $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

        //消费次数
        $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
        $timesto = Yii::app()->request->getPost('timesto',"999999");

        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");//会员卡号  手机号
        if(empty($cardmobile))
        {
            $cardmobile="%";
        }

        //未消费时长数据处理
        if($noordertime!="%"){
            $begintime = date('Y-m-d',strtotime("-".$noordertime." month"));
            $endtime = date('Y-m-d',time());
            $sql = 'select ifnull(k.user_id,0000000000) as user_id from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$companyId.' and k.create_at >="'.$begintime.' 00:00:00" and k.create_at <="'.$endtime.' 23:59:59" group by k.user_id';
            $orders = $db->createCommand($sql)->queryAll();
            $users ='0000000000';
            foreach ($orders as $order){
                $users = $users .','.$order['user_id'];
            }
        }else{
            $users = '0000000000';
        }
        $criteria = new CDbCriteria;
        //var_dump($sql);exit;
        //用sql语句查询出所有会员及消费总额、历史积分、余额、

        //来源店铺条件 省 市 地区
        if($findprovince!="请选择..")
        {
            $sqlp= " and com.province like '".$findprovince."'";
        }else{
            $sqlp='';
        }
        if($findcity!="请选择..")
        {
            $sqlc= " and com.city like '".$findcity."'";
        }else{
            $sqlc='';
        }
        if($findarea!="请选择..")
        {
            $sqla= " and com.county_area like '".$findarea."'";
        }else{
            $sqla='';
        }
        $sql="select t.lid,t.dpid,t.card_id,t.create_at,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
            .",t.province,t.city,t.mobile_num,(t.remain_money+t.remain_back_money) as all_money,com.dpid as companyid,com.company_name"
            . " from nb_brand_user t "
            . " LEFT JOIN  nb_company com on (com.dpid = t.weixin_group )"
            . " LEFT JOIN (select dpid,user_id,sum(reality_total) as consumetotal,count(*) as consumetimes from nb_order"
                    . " where order_type in ('1','2','6') and order_status in ('3','4','8') and update_at>='$datefrom 00:00:00' and update_at <='$dateto 23:59:59'"
                        . " group by dpid,user_id) tct on (t.weixin_group=tct.dpid and t.lid=tct.user_id) "
            . " LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid and tl.delete_flag = 0 and tl.level_type = 1 "
            . " where t.lid not in(".$users.") and (t.dpid=".$companyId." or t.weixin_group =".$companyId.") ".$sqlp.$sqlc.$sqla;
           // echo $sql;exit;


        if($finduserlevel!="0000000000")
        {
            $sql.= " and tl.lid = ".$finduserlevel;
        }
        if($findsex!="%")
        {
            $sql.= "and t.sex like '".$findsex."'";
        }
        if($cardmobile!="%")
        {
            $sql.= " and (t.card_id like '%".$cardmobile."%' or t.mobile_num like '%".$cardmobile."%')";
        }
        if($findweixingroup!="0000000000")
        {
            $sql.= " and t.weixin_group = ".$findweixingroup;
        }
        if($source){
            $sql.= " and com.company_name like '%".$source."%'";
        }

        //关注时间数据处理
        if($foucsfrom){
            $sql .= " and t.create_at >='".$foucsfrom."'";
        }
        if($foucsto){
            $sql .= " and t.create_at <='".$foucsto."'";
        }


        $yearnow=date('Y',time());
        $yearbegin=$yearnow-$ageto;
        $yearend=$yearnow-$agefrom;
        $sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) >= '".$yearbegin."' and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) <= '".$yearend."'";
        $sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) >= '".$birthfrom."' and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) <= '".$birthto."'";
        //$sql.=" and ifnull(tpt.pointvalidtotal,0) >= ".$pointfrom." and ifnull(tpt.pointvalidtotal,0)<=".$pointto;
        //$sql.=" and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) >= "
        //  .$remainfrom." and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) <=".$remainto;
        $sql.=" and ifnull(tct.consumetotal,0) >= ".$consumetotalfrom." and ifnull(tct.consumetotal,0)<=".$consumetotalto;
        $sql.=" and ifnull(tct.consumetimes,0) >= ".$timesfrom." and ifnull(tct.consumetimes,0)<=".$timesto;
        $sql = 'select cf.* from ('.$sql.') cf';
        //$models = $db->createCommand($sql)->queryAll();

        $count = $db->createCommand(str_replace('cf.*','count(*)',$sql))->queryScalar();
        //var_dump($count);exit;
        $pages = new CPagination($count);
        $pages->pageSize = 100;
        $pdata =$db->createCommand($sql." LIMIT :offset,:limit");
        $pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
        $pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
        $models = $pdata->queryAll();

        //检索条件会员等级
        $criteriauserlevel = new CDbCriteria;
        $criteriauserlevel->condition =  ' t.delete_flag=0 and t.dpid='.$companyId;
        $userlevels = BrandUserLevel::model()->findAll($criteriauserlevel);



        $this->render('search',array(
                'models'=>$models,
             	'pages'=>$pages,
                'findsex'=>$findsex,
                'agefrom'=>$agefrom,
                'ageto'=>$ageto,
                'birthfrom'=>$birthfrom,
                'birthto'=>$birthto,
                'userlevels'=>$userlevels,
                'finduserlevel'=>$finduserlevel,


                'noordertime'=>$noordertime,

	            'province'=>$findprovince,
                'city'=>$findcity,
                'area'=>$findarea,

                'pointfrom'=>$pointfrom,
                'consumetotalfrom'=>$consumetotalfrom,
                'consumetotalto'=>$consumetotalto,
                'timesfrom'=>$timesfrom,
                'timesto'=>$timesto,
                'cardmobile'=>$cardmobile,
                'more'=>$more,
        		'source'=>$source,
                'foucsfrom'=>$foucsfrom,
                'foucsto'=>$foucsto,
                'datefrom'=>$datefrom,
                'dateto'=>$dateto,
			));
    }
    public function actionSearchExport(){
        $db=Yii::app()->db;
        $companyId = Yii::app()->request->getParam('companyId',"0000000000");
        $more = Yii::app()->request->getPost('more',"0");
        $findsex = Yii::app()->request->getPost('findsex',"%");//性别
        $agefrom = Yii::app()->request->getPost('agefrom',"0");//起始年龄
        $ageto = Yii::app()->request->getPost('ageto',"100");//终止年龄
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");//起始生日
        $birthto = Yii::app()->request->getPost('birthto',"12-31");//终止生日
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0000000000");//会员等级
        $findweixingroup=Yii::app()->request->getPost('findweixingroup',"0000000000");//会员来源店铺
        $noordertime=Yii::app()->request->getPost('noordertime',"%");//未消费时长

        //省 市 地区
        $findprovince=Yii::app()->request->getPost('province',"%");
        $findcity=Yii::app()->request->getPost('city',"%");
        $findarea=Yii::app()->request->getPost('area',"%");

        $pointfrom = Yii::app()->request->getPost('pointfrom',"0");
        $source = Yii::app()->request->getPost('source',"");//来源
        $foucsfrom = Yii::app()->request->getPost('foucsfrom',"");//关注开始时间
        $foucsto = Yii::app()->request->getPost('foucsto',"");//关注结束时间时间
        // p($_POST);
        if($pointfrom==0)
        {
            $pointfrom=-999999;
        }
        $pointto = Yii::app()->request->getPost('pointto',"9999999999");
        $remainfrom = Yii::app()->request->getPost('remainfrom',"0");
        // if($remainfrom==0)
        // {
        //  $remainfrom=-999999;
        // }
        $remainto = Yii::app()->request->getPost('remainto',"9999999999");

        //时间范围
        $datefrom = Yii::app()->request->getPost('datefrom',"2015-01-01");
        $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

        //总消费额范围
        $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
        // if($consumetotalfrom==0)
        // {
        //  $consumetotalfrom=-999999;
        // }
        $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

        //消费次数
        $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
        $timesto = Yii::app()->request->getPost('timesto',"999999");

        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");//会员卡号  手机号
        if(empty($cardmobile))
        {
            $cardmobile="%";
        }

        //未消费时长数据处理
        if($noordertime!="%"){
            $begintime = date('Y-m-d',strtotime("-".$noordertime." month"));
            $endtime = date('Y-m-d',time());
            $sql = 'select ifnull(k.user_id,0000000000) as user_id from nb_order k where k.order_status in(3,4,8) and k.dpid = '.$companyId.' and k.create_at >="'.$begintime.' 00:00:00" and k.create_at <="'.$endtime.' 23:59:59" group by k.user_id';
            $orders = $db->createCommand($sql)->queryAll();
            $users ='0000000000';
            foreach ($orders as $order){
                $users = $users .','.$order['user_id'];
            }
        }else{
            $users = '0000000000';
        }
        $criteria = new CDbCriteria;
        //var_dump($sql);exit;
        //用sql语句查询出所有会员及消费总额、历史积分、余额、

        //来源店铺条件 省 市 地区
        if($findprovince!="请选择..")
        {
            $sqlp= " and com.province like '".$findprovince."'";
        }else{
            $sqlp='';
        }
        if($findcity!="请选择..")
        {
            $sqlc= " and com.city like '".$findcity."'";
        }else{
            $sqlc='';
        }
        if($findarea!="请选择..")
        {
            $sqla= " and com.county_area like '".$findarea."'";
        }else{
            $sqla='';
        }
        $sql="select t.lid,t.dpid,t.card_id,t.create_at,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
            .",t.province,t.city,t.mobile_num,(t.remain_money+t.remain_back_money) as all_money,com.dpid as companyid,com.company_name"
            . " from nb_brand_user t "
            . " LEFT JOIN  nb_company com on (com.dpid = t.weixin_group )"
            . " LEFT JOIN (select dpid,user_id,sum(reality_total) as consumetotal,count(*) as consumetimes from nb_order"
                    . " where order_type in ('1','2','6') and order_status in ('3','4','8') and update_at>='$datefrom 00:00:00' and update_at <='$dateto 23:59:59'"
                        . " group by dpid,user_id) tct on (t.weixin_group=tct.dpid and t.lid=tct.user_id) "
            . " LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid and tl.delete_flag = 0 and tl.level_type = 1 "
            . " where t.lid not in(".$users.") and (t.dpid=".$companyId." or t.weixin_group =".$companyId.") ".$sqlp.$sqlc.$sqla;
           // echo $sql;exit;


        if($finduserlevel!="0000000000")
        {
            $sql.= " and tl.lid = ".$finduserlevel;
        }
        if($findsex!="%")
        {
            $sql.= "and t.sex like '".$findsex."'";
        }
        if($cardmobile!="%")
        {
            $sql.= " and (t.card_id like '%".$cardmobile."%' or t.mobile_num like '%".$cardmobile."%')";
        }
        if($findweixingroup!="0000000000")
        {
            $sql.= " and t.weixin_group = ".$findweixingroup;
        }
        if($source){
            $sql.= " and com.company_name like '%".$source."%'";
        }

        //关注时间数据处理
        if($foucsfrom){
            $sql .= " and t.create_at >='".$foucsfrom."'";
        }
        if($foucsto){
            $sql .= " and t.create_at <='".$foucsto."'";
        }


        $yearnow=date('Y',time());
        $yearbegin=$yearnow-$ageto;
        $yearend=$yearnow-$agefrom;
        $sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) >= '".$yearbegin."' and substring(ifnull(t.user_birthday,'1917-01-01'),1,4) <= '".$yearend."'";
        $sql.= " and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) >= '".$birthfrom."' and substring(ifnull(t.user_birthday,'1917-01-01'),6,5) <= '".$birthto."'";
        //$sql.=" and ifnull(tpt.pointvalidtotal,0) >= ".$pointfrom." and ifnull(tpt.pointvalidtotal,0)<=".$pointto;
        //$sql.=" and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) >= "
        //  .$remainfrom." and ifnull(trt.rechargetotal,0)+ifnull(tcbt.cashbacktotal,0)-ifnull(twxp.wxpay,0) <=".$remainto;
        $sql.=" and ifnull(tct.consumetotal,0) >= ".$consumetotalfrom." and ifnull(tct.consumetotal,0)<=".$consumetotalto;
        $sql.=" and ifnull(tct.consumetimes,0) >= ".$timesfrom." and ifnull(tct.consumetimes,0)<=".$timesto;
        $sql = 'select cf.* from ('.$sql.') cf';
        $models = $db->createCommand($sql)->queryAll();


// p($models);



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
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃微信会员信息表'))
        ->setCellValue('A2',yii::t('app','注意：该表为所选查询条件的查询结果'))
        ->setCellValue('A3',yii::t('app','卡号'))
        ->setCellValue('B3',yii::t('app','姓名|昵称'))
        ->setCellValue('C3',yii::t('app','性别'))
        ->setCellValue('D3',yii::t('app','手机号'))
        ->setCellValue('E3',yii::t('app','生日'))
        ->setCellValue('F3',yii::t('app','等级'))
        ->setCellValue('G3',yii::t('app','地区(会员)'))
        ->setCellValue('H3',yii::t('app','来源店铺'))
        ->setCellValue('I3',yii::t('app','关注日期'))
        ->setCellValue('J3',yii::t('app','余额'));
        $j=4;
        if($models){

                foreach ($models as $v) {
                    switch ($v['sex']){
                        case 0:$v['sex'] = "未知"; break;
                        case 1:$v['sex'] = "男";break;
                        case 2:$v['sex'] = "女";
                    }
                    if($v['mobile_num']){
                        if(Yii::app()->user->role == 8){
                            $str = substr_replace($v['mobile_num'],'****',3,4);
                        }else{
                            $str = $v['mobile_num'];
                        }
                    }else{
                        $str='';
                    }

                    if($v['user_birthday']){
                        $birth = substr($v['user_birthday'],0,10);
                    }else{
                        $birth = '';
                    }
                    if($v['create_at']){
                        $guanzhuri = substr($v['create_at'],0,10);
                    }else{
                        $guanzhuri = '';
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValueExplicit('A'.$j,$v['card_id'],PHPExcel_Cell_DataType::TYPE_STRING)
                    ->setCellValue('B'.$j,$v['user_name']?$v['user_name']:$v['nickname'])
                    ->setCellValue('C'.$j,$v['sex'])
                    ->setCellValue('D'.$j,$str)
                    ->setCellValue('E'.$j,$birth)
                    ->setCellValue('F'.$j,$v['level_name'])
                    ->setCellValue('G'.$j,$v['country'].$v['province'].$v['city'])
                    ->setCellValue('H'.$j,$v['company_name'])
                    ->setCellValue('I'.$j,$guanzhuri)
                    ->setCellValue('J'.$j,$v['all_money']);
                    $j++;
                }
            }

        //$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$a, $k['listing'],PHPExcel_Cell_DataType::TYPE_STRING)//设置数字的科学计数法显示为文本
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
        //单元格加粗，居中：
        $objPHPExcel->getActiveSheet()->getStyle('A1:J'.$j)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:J3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="微信会员统计表（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    public function actionVip() {
        $criteria = new CDbCriteria;
        $criteria->select = 'MemberWxCardStyle.bg_img as bgimg,t.*';
        $criteria->with = 'MemberWxCardStyle';
		$criteria->addCondition('t.level_type = 1 and t.dpid=:dpid and t.delete_flag=0');
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;

		$pages = new CPagination(BrandUserLevel::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = BrandUserLevel::model()->findAll($criteria);
		//var_dump($models);exit;
		$this->render('vip',array(
				'models'=> $models,
				'pages' => $pages
		));
    }

    public function actionVipCreate() {

    	$model = new BrandUserLevel();
    	$member_wxcard_bgimgs = MemberWxcardStyle::model()->findAll('dpid =:companyId and delete_flag = 0',array(':companyId'=>$this->companyId));

    	if(Yii::app()->request->isPostRequest) {
    		$model->attributes = Yii::app()->request->getPost('BrandUserLevel');
    		$styleid = Yii::app()->request->getParam('style_id');
    		//var_dump($styleid);exit;
    		if($styleid){
    			$styleid = $styleid;
    		}
    		$se=new Sequence("brand_user_level");
    		$lid = $se->nextval();
    		$model->lid = $lid;
    		$model->dpid = $this->companyId;
    		$model->create_at = date('Y-m-d H:i:s',time());
    		$model->update_at = date('Y-m-d H:i:s',time());
    		$model->style_id = $styleid;
    		$model->level_type = '1';
    		//var_dump($model);exit;
    		if($model->save()){
    			Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
    			$this->redirect(array('WechatMember/vip' , 'companyId' => $this->companyId ));
    		}
    	}

    	$this->render("vipCreate",
    			array("model"=>$model,
    					"member_wxcard_bgimgs" => $member_wxcard_bgimgs
    			));

    }
    public function actionVipUpdate() {
        //通过get方法接收要展示的信息的主键。
        $lid = Yii::app()->request->getParam('lid');

        //在数据库查找该主键对应的条目。
        $model = BrandUserLevel::model()->find('lid=:lid' , array(':lid' => $lid)) ;
        $member_wxcard_bgimgs = MemberWxcardStyle::model()->findAll('dpid =:companyId and delete_flag = 0',array(':companyId'=>$this->companyId));

       if(Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('BrandUserLevel');
            $styleid = Yii::app()->request->getParam('style_id');
            //var_dump($styleid);exit;
            if($styleid){
            	$styleid = $styleid;
            	$model->style_id = $styleid;
            }
            $model->update_at = date('Y-m-d H:i:s',time());
            if($model->save()){
                Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
                $this->redirect(array('WechatMember/vip' , 'companyId' => $this->companyId ));
            }
        }
        $this->render('vipUpdate',
				array("model"=>$model,
    					"member_wxcard_bgimgs" => $member_wxcard_bgimgs
    			));
    }
    public function actionVipDelete(){
    	$ids = Yii::app()->request->getPost('vipIds');
    	//var_dump($ids);exit;
    	if(!empty($ids)) {
    		Yii::app()->db->createCommand('update nb_brand_user_level set delete_flag=1 where dpid = '.$this->companyId.' and level_type =1 and lid in ('.implode(',' , $ids).')')
    		->execute();
    		//var_dump($ids);exit;
    		//echo 'update nb_brand_user_level set delete_flag=1 where dpid = '.$this->companyId.' and level_type =1 and lid in ('.implode(',' , $ids).')';exit;
    		Yii::app()->user->setFlash('success' , yii::t('app','删除成功'));
    		$this->redirect(array('WechatMember/vip' , 'companyId' => $this->companyId)) ;
    	}else {
    		Yii::app()->user->setFlash('error' , yii::t('app','请选择要删除的项目'));
    		$this->redirect(array('WechatMember/vip' , 'companyId' => $this->companyId)) ;
    	}

    	//$this->redirect(array('WechatMember/vip','companyId'=>$this->companyId));
    }
    public function actionSource() {
       	$this->render('source');
    }
    public function actionStore() {
       	$this->render('store');
    }
    public function actionPoint(){

        //功能状态信息
        $is_available[0] = "开启";
        $is_available[1] = "关闭";

      $model = new WxPoint();


       if(Yii::app()->request->isPostRequest) {
        $wxPoint = Yii::app()->request->getPost('WxPoint');
        $se=new Sequence("wx_point");
        $lid = $se->nextval();
        $model->lid = $lid;
        //特殊的特权内容字段处理

        $model->is_available = $wxPoint['is_available'];
        $model->award_rule = $wxPoint['award_rule'];
        $model->award_scope = $wxPoint['award_scope'];
        $model->deadline = $wxPoint['deadline'];
        $model->use_point = $wxPoint['use_point'];
        $model->limit_comment = $wxPoint['limit_comment'];
        $model->create_at = date('Y-m-d H:i:s',time());
        $model->update_at = date('Y-m-d H:i:s',time());
        if($model->save()){
            Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
            $this->redirect(array('WechatMember/point' , 'companyId' => $this->companyId ));
        }

       }

        $this->render("point",array(
                    "model" => $model,
                    "is_available"=>$is_available)
                );

    }

      public function actionShop(){
       $this->render('shop');
     }
public function actionAccountDetail(){

        $type = Yii::app()->request->getParam('type',"0");

        $orderid = Yii::app()->request->getParam('orderid',"0");
        $db = Yii::app()->db;
        if($type == 0){
                $sql = 'select sum(t.zhiamount*t.amount) as all_amount,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
        }else{
                $sql = 'select sum(t.zhiamount*t.amount) as all_amount,count(t.zhiamount) as all_zhiamount,sum(t2.retreat_amount) as retreat_num,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t.lid = t2.order_detail_id) where t.dpid='.$this->companyId.' and t.order_id='.$orderid.' group by t.lid';
        }//var_dump($sql);exit;
        $allmoney = Yii::app()->db->createCommand($sql)->queryAll();
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
 public function actionChain(){
 	$dpid = $this->companyId;
    if(Yii::app()->request->isPostRequest) {
           $bindData = Yii::app()->request->getParam('bind');
            foreach($bindData as $key => $val){
                $bind = MemberCardBind::model()->find('dpid=:dpid and membercard_level_id=:member and branduser_level_id=:branduser',array(':dpid'=>$dpid,':member'=>$val['membercard_level_id'],':branduser'=>$val['branduser_level_id']));
                if(!$bind){
                    $bind = new MemberCardBind();
                    $se=new Sequence("member_card_bind");
                    $lid = $se->nextval();
                    $bind->lid = $lid;
                    $bind->dpid = $dpid;
                }
                $bind->membercard_level_id = $val['membercard_level_id'];
                $bind->branduser_level_id =$val['branduser_level_id'];
                $bind->create_at = date('Y-m-d H:i:s',time());
                $bind->update_at = date('Y-m-d H:i:s',time());
                $bind->save();
            }
            Yii::app()->user->setFlash('success',yii::t('app','绑定成功！'));
            $this->redirect(array('WechatMember/list' , 'companyId' => $this->companyId ));
      }

      $entity = BrandUserLevel::model()->with('memberbind')->findALL('t.dpid='.$dpid.' and t.level_type=0 and t.delete_flag=0');
      $weixinAccount = WeixinServiceAccount::model()->find('dpid='.$dpid);
      if(!$weixinAccount){
        	$company = Company::model()->find('dpid='.$dpid);
        	if($company['type'] > 0){
        		$dpid = $company['comp_dpid'];
        	}
       }
       $weixin = BrandUserLevel::model()->findALL('dpid = '.$dpid .' and level_type=1 and delete_flag=0');
       $this->render('chain',array(
                    "entity" => $entity,
                    "weixin" => $weixin
                    )
                );
     }

     public function actionAddcash(){
     	$companyId = Yii::app()->request->getParam('companyId');
     	$dpid = Yii::app()->request->getParam('dpid');
     	$userid = Yii::app()->request->getParam('userid');
     	$money = Yii::app()->request->getParam('money');

     	//****查询公司的产品分类。。。****
     	$db = Yii::app()->db;
     	$user = BrandUser::model()->find('dpid=:companyId and lid=:lid' , array(':companyId'=>$dpid,':lid'=>$userid));
     	if(!empty($user)){
     		$all_money = $user->remain_back_money + $money;
     		$sql = 'update nb_brand_user set update_at ="'.date('Y-m-d H:i:s',time()).'",remain_back_money ="'.$all_money.'" where dpid ='.$dpid.' and lid ='.$userid;
     		$result = $db->createCommand($sql)->execute();
     		if($result){

     			$se = new Sequence("recharge_record");
     			$id = $se->nextval();
     			$data = array(
     					'lid'=>$id,
     					'dpid'=>$dpid,
     					'create_at'=>date('Y-m-d H:i:s',time()),
     					'update_at'=>date('Y-m-d H:i:s',time()),
     					'recharge_lid'=>'0',
     					'recharge_money'=>'0.00',
     					'cashback_num'=>$money,
     					'brand_user_lid'=>$userid,
     					'who_recharge'=>Yii::app()->user->username,
     					'delete_flag'=>'0',
     					'is_sync'=>'11111',
     			);
     			//var_dump($dataprod);exit;
     			$command = $db->createCommand()->insert('nb_recharge_record',$data);

     			Yii::app()->end(json_encode(array("status"=>true,'msg'=>'成功')));
     		}else{
     			Yii::app()->end(json_encode(array("status"=>false,'msg'=>'失败')));
     		}
     	}else{
     		Yii::app()->end(json_encode(array("status"=>false,'msg'=>'失败')));
     	}

     }
}


