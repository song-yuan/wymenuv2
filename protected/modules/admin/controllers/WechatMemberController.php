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

		$sql = 'select t.*,t1.level_name,t1.level_discount,t1.birthday_discount from nb_brand_user t left join nb_brand_user_level t1 on t.user_level_lid=t1.lid and t.dpid=t1.dpid where (t.dpid='.$this->companyId.' or t.weixin_group='.$this->companyId.') and t.lid='.$num;
		$brandUser = Yii::app()->db->createCommand($sql)->queryRow();
		
		$companyArrs = array();
		if($this->comptype==0){
			$companys = WxCompany::getCompanyChildren($this->companyId);
		}else{
			$companys = WxCompany::getCompanyChildren($this->company_dpid);
		}
		foreach ($companys as $company){
			$companyArrs[$company['dpid']] = $company;
		}
        
		$orders = array();
		$sql = 'select lid,dpid,create_at,account_no,reality_total,should_total from nb_order where lid in(select lid from nb_order where create_at > "2016-01-01 00:00:00" and user_id='.$brandUser['lid'].' and account_no > 0) and order_type in(1,2,3,5,6) and order_status in(3,4,8) order by lid desc';
		$orders = Yii::app()->db->createCommand($sql)->queryAll();
		
		$sql = 'select cb.*,c.cupon_title,c.cupon_money,c.min_consumer from nb_cupon_branduser cb,nb_cupon c where cb.cupon_id=c.lid and cb.dpid=c.dpid and cb.brand_user_lid='.$num.' and c.is_available=0 and cb.delete_flag=0 and c.delete_flag=0 order by cb.lid desc limit 50';
		$userCupons = Yii::app()->db->createCommand($sql)->queryAll();
		
		$sql = 'select * from nb_recharge_record where brand_user_lid='.$num.' order by lid desc limit 50';
		$recharges = Yii::app()->db->createCommand($sql)->queryAll();
       
        $this->render('searchdetail',array( 'brandUser'=> $brandUser,
                                        'userCupons'=> $userCupons,
                                        'orders'=>$orders,
        								'recharges'=>$recharges,
        								'companys'=>$companyArrs,
                    			)
                    );
    }
    public function actionSearch(){
        $db = Yii::app()->db;
        $more = Yii::app()->request->getPost('more',"0");
        //性别
        $findsex = Yii::app()->request->getPost('findsex',"%");
        //年龄范围
        $agefrom = Yii::app()->request->getPost('agefrom',"0");
        $ageto = Yii::app()->request->getPost('ageto',"150");
        //生日范围
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");
        $birthto = Yii::app()->request->getPost('birthto',"12-31");
        //会员等级
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0");
        //未消费时长
        $noordertime=Yii::app()->request->getPost('noordertime',"%");

        //省 市 地区
        $findprovince=Yii::app()->request->getPost('province',"请选择..");
        $findcity=Yii::app()->request->getPost('city',"请选择..");
        
        //来源门店名称
        $source = Yii::app()->request->getPost('source',"");
        //关注时间
        $foucsfrom = Yii::app()->request->getPost('foucsfrom',"");
        $foucsto = Yii::app()->request->getPost('foucsto',"");

        //下订单时间范围
        $datefrom = Yii::app()->request->getPost('datefrom',"2014-01-01");
        $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

        //总消费额范围
        $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
        $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

        //消费次数
        $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
        $timesto = Yii::app()->request->getPost('timesto',"999999");
        
        //会员卡号  手机号
        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");

        $sql = 'select t.lid,t.dpid,t.card_id,t.create_at,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country,t.province,t.city,t.mobile_num,(t.remain_money+t.remain_back_money) as all_money,com.dpid as companyid,com.company_name';
        $sql .=' from nb_brand_user t LEFT JOIN  nb_company com on t.weixin_group = com.dpid ';
        $sql .=' LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid ';
        if($this->comptype==0){
        	$companyDpid = $this->companyId;
        	$companyId = WxCompany::getAllDpids($companyDpid);
        	$sql .=' where t.dpid='.$this->companyId.' and tl.level_type = 1 and tl.delete_flag = 0';
        }else{
        	$companyDpid = $this->company_dpid;
        	$companyId = $this->companyId;
        	$sql .=' where t.dpid='.$this->company_dpid.' and weixin_group='.$this->companyId.' and tl.level_type = 1 and tl.delete_flag = 0';
        }
    	
    	// 卡号或手机号
    	if($cardmobile!="%"&&$cardmobile!="")
        {
            $sql.= ' and (t.card_id like "%'.$cardmobile.'%" or t.mobile_num like "%'.$cardmobile.'%")';
        }
        // 性别
        if($findsex!="%")
        {
        	$sql.= ' and t.sex = "'.$findsex.'"';
        }
        // 年龄
        if($agefrom!=0){
        	 $sql .= ' and t.user_birthday<="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        if($ageto!=150){
        	$sql .= ' and t.user_birthday>="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        //生日
        if($birthfrom!='01-01'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")>="'.$birthfrom.'"';
        }
        if($birthto!='12-31'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")<="'.$birthto.'"';
        }
        //等级
        if($finduserlevel!="0")
        {
        	$sql.= ' and t.user_level_lid = '.$finduserlevel;
        }
        
        // 省市
        if($findprovince!="请选择..")
        {
        	$findprovincenew = str_replace(array('省','市'),'', $findprovince);
        	$sql .= ' and com.province like "%'.$findprovincenew.'%"';
        }
        if($findcity!="请选择..")
        {
        	$findcitynew = str_replace(array('市','区','县'),'', $findcity);
        	$sql .= ' and com.city like "'.$findcitynew.'"';
        }
        // 来源店铺
        if($source){
        	$sql.= ' and com.company_name like "%'.$source.'%"';
        }
        
        //关注时间数据处理
        if($foucsfrom){
        	$sql .= ' and t.create_at >="'.$foucsfrom.' 00:00:00"';
        }
        if($foucsto){
        	$sql .= ' and t.create_at <="'.$foucsto.' 23:59:59"';
        }
        // 订单信息条件
        if($datefrom!='2014-01-01'||
        	$dateto!=date('Y-m-d',time())||
        	$noordertime!='%'||
        	$consumetotalfrom!='0'||
        	$consumetotalto!='9999999999'||
        	$timesfrom!='0'||
        	$timesto!='999999'){
        	
        	$where = '';
        	$having = '';
        	$osql = 'select user_id from nb_order where dpid in('.$companyId.')';
        	
        	if($noordertime!='%'){
        		$noorderdate = date('Y-m-d',strtotime('-'.$noordertime.' month'));
        		$where .= ' and create_at<="'.$noorderdate.' 00:00:00"';
        	}else{
        		//下单时间
        		if($datefrom!='2014-01-01'){
        			$where .= ' and create_at>="'.$datefrom.' 00:00:00"';
        		}
        		if($dateto!=date('Y-m-d',time())){
        			$where .= ' and create_at<="'.$dateto.' 23:59:59"';
        		}	
        	}
        	$osql .=$where.' group by user_id';
        	if($consumetotalfrom!='0'){
        		$having .=' and sum(should_total)>='.$consumetotalfrom;
        	}
        	if($consumetotalto!='9999999999'){
        		$having .=' and sum(should_total)<='.$consumetotalto;
        	}
        	if($timesfrom!='0'){
        		$having .=' and count(lid)>='.$timesfrom;
        	}
        	if($timesto!='999999'){
        		$having .=' and count(lid)<='.$timesto;
        	}
        	if($having!=''){
        		$having = ltrim($having, ' and');
        		$osql .=' having '.$having;
        	}
        	$userIds = $db->createCommand($osql)->queryColumn();
        	$userStr = join($userIds, ',');
	        if($userStr!=''){
	        	$userStr = ltrim($userStr, ',');
	        	$sql .= ' and t.lid in('.$userStr.')';
	        }
        }
        $sql = 'select * from ('.$sql.')m';
        $count = $db->createCommand(str_replace('*','count(*)',$sql))->queryScalar();
        $pages = new CPagination($count);
        $pages->pageSize = 100;
        $pdata =$db->createCommand($sql." LIMIT :offset,:limit");
        $pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
        $pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
        $models = $pdata->queryAll();

        //检索条件会员等级
        $sql = 'select lid,level_name from nb_brand_user_level where dpid='.$companyDpid.' and level_type=1 and delete_flag=0';
        $userlevels = $db->createCommand($sql)->queryAll();
		
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
        $companyId = $this->companyId;
        $more = Yii::app()->request->getPost('more',"0");
        //性别
        $findsex = Yii::app()->request->getPost('findsex',"%");
        //年龄范围
        $agefrom = Yii::app()->request->getPost('agefrom',"0");
        $ageto = Yii::app()->request->getPost('ageto',"150");
        //生日范围
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");
        $birthto = Yii::app()->request->getPost('birthto',"12-31");
        //会员等级
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0");
        //未消费时长
        $noordertime=Yii::app()->request->getPost('noordertime',"%");

        //省 市 地区
        $findprovince=Yii::app()->request->getPost('province',"请选择..");
        $findcity=Yii::app()->request->getPost('city',"请选择..");
        
        //来源门店名称
        $source = Yii::app()->request->getPost('source',"");
        //关注时间
        $foucsfrom = Yii::app()->request->getPost('foucsfrom',"");
        $foucsto = Yii::app()->request->getPost('foucsto',"");

        //下订单时间范围
        $datefrom = Yii::app()->request->getPost('datefrom',"2014-01-01");
        $dateto = Yii::app()->request->getPost('dateto',date('Y-m-d',time()));

        //总消费额范围
        $consumetotalfrom = Yii::app()->request->getPost('consumetotalfrom',"0");
        $consumetotalto = Yii::app()->request->getPost('consumetotalto',"9999999999");

        //消费次数
        $timesfrom = Yii::app()->request->getPost('timesfrom',"0");
        $timesto = Yii::app()->request->getPost('timesto',"999999");
        
        //会员卡号  手机号
        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");

        $sql = 'select t.lid,t.dpid,t.card_id,t.create_at,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country,t.province,t.city,t.mobile_num,t.remain_money,t.remain_back_money,com.dpid as companyid,com.company_name';
        $sql .=' from nb_brand_user t LEFT JOIN  nb_company com on com.dpid = t.weixin_group ';
        $sql .=' LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid ';
        $sql .=' where t.dpid='.$companyId.' and tl.level_type = 1 and tl.delete_flag = 0 and com.delete_flag = 0';
    	
    	// 卡号或手机号
    	if($cardmobile!="%"&&$cardmobile!="")
        {
            $sql.= ' and (t.card_id like "%'.$cardmobile.'%" or t.mobile_num like "%'.$cardmobile.'%")';
        }
        // 性别
        if($findsex!="%")
        {
        	$sql.= ' and t.sex = "'.$findsex.'"';
        }
        // 年龄
        if($agefrom!=0){
        	 $sql .= ' and t.user_birthday<="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        if($ageto!=150){
        	$sql .= ' and t.user_birthday>="'.date('Y-01-01',strtotime('-'.$agefrom.' year')).'"';
        }
        //生日
        if($birthfrom!='01-01'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")>="'.$birthfrom.'"';
        }
        if($birthto!='12-31'){
        	$sql .=' and DATE_FORMAT(t.user_birthday,"%m-%d")<="'.$birthto.'"';
        }
        //等级
        if($finduserlevel!="0")
        {
        	$sql.= ' and t.user_level_lid = '.$finduserlevel;
        }
        
        // 省市
        if($findprovince!="请选择..")
        {
        	$findprovincenew = str_replace(array('省','市'),'', $findprovince);
        	$sql .= ' and t.province like "%'.$findprovincenew.'%"';
        }
        if($findcity!="请选择..")
        {
        	$findcitynew = str_replace(array('市','区','县'),'', $findcity);
        	$sql .= ' and t.city like "'.$findcitynew.'"';
        }
        // 来源店铺
        if($source){
        	$sql.= ' and com.company_name like "%'.$source.'%"';
        }
        
        //关注时间数据处理
        if($foucsfrom){
        	$sql .= ' and t.create_at >="'.$foucsfrom.' 00:00:00"';
        }
        if($foucsto){
        	$sql .= ' and t.create_at <="'.$foucsto.' 23:59:59"';
        }
        // 订单信息条件
        if($datefrom!='2014-01-01'||
        	$dateto!=date('Y-m-d',time())||
        	$noordertime!='%'||
        	$consumetotalfrom!='0'||
        	$consumetotalto!='9999999999'||
        	$timesfrom!='0'||
        	$timesto!='999999'){
        	
        	$where = '';
        	$having = '';
        	$osql = 'select user_id from nb_order where dpid = '.$companyId;
        	
        	if($noordertime!='%'){
        		$noorderdate = date('Y-m-d',strtotime('-'.$noordertime.' month'));
        		$where .= ' and create_at<="'.$noorderdate.' 00:00:00"';
        	}else{
        		//下单时间
        		if($datefrom!='2014-01-01'){
        			$where .= ' and create_at>="'.$datefrom.' 00:00:00"';
        		}
        		if($dateto!=date('Y-m-d',time())){
        			$where .= ' and create_at<="'.$dateto.' 23:59:59"';
        		}	
        	}
        	$osql .=$where.' group by user_id';
        	if($consumetotalfrom!='0'){
        		$having .=' and sum(should_total)>='.$consumetotalfrom;
        	}
        	if($consumetotalto!='9999999999'){
        		$having .=' and sum(should_total)<='.$consumetotalto;
        	}
        	if($timesfrom!='0'){
        		$having .=' and count(lid)>='.$timesfrom;
        	}
        	if($timesto!='999999'){
        		$having .=' and count(lid)<='.$timesto;
        	}
        	if($having!=''){
        		$osql .=' having by '.$having;
        	}
        	$userIds = $db->createCommand($osql)->queryColumn();
        	$userStr = join($userIds, ',');
	        if($userStr!=''){
	        	$sql .= ' and t.user_id in('.$userStr.')';
	        }
        }
        $sql = 'select m.* from ('.$sql.')m';
        $models = $db->createCommand($sql)->queryAll();
		
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
        ->setCellValue('B3',yii::t('app','姓名'))
        ->setCellValue('C3',yii::t('app','性别'))
        ->setCellValue('D3',yii::t('app','手机号'))
        ->setCellValue('E3',yii::t('app','生日'))
        ->setCellValue('F3',yii::t('app','等级'))
        ->setCellValue('G3',yii::t('app','地区(会员)'))
        ->setCellValue('H3',yii::t('app','来源店铺'))
        ->setCellValue('I3',yii::t('app','关注日期'))
        ->setCellValue('J3',yii::t('app','充值金额'))
        ->setCellValue('K3',yii::t('app','返现金额'));
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
                    ->setCellValue('B'.$j,$v['user_name'])
                    ->setCellValue('C'.$j,$v['sex'])
                    ->setCellValue('D'.$j,$str)
                    ->setCellValue('E'.$j,$birth)
                    ->setCellValue('F'.$j,$v['level_name'])
                    ->setCellValue('G'.$j,$v['country'].$v['province'].$v['city'])
                    ->setCellValue('H'.$j,$v['company_name'])
                    ->setCellValue('I'.$j,$guanzhuri)
                    ->setCellValue('J'.$j,$v['remain_money'])
                    ->setCellValue('K'.$j,$v['remain_back_money']);
                    $j++;
                }
            }

        //$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('A'.$a, $k['listing'],PHPExcel_Cell_DataType::TYPE_STRING)//设置数字的科学计数法显示为文本
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:K2');
        //单元格加粗，居中：
        $objPHPExcel->getActiveSheet()->getStyle('A1:K'.$j)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
        //输出
        $filename="微信会员统计表（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
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
        //if($type == 0){
          //      $sql = 'select sum(t.zhiamount*t.amount) as all_amount,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) where t.order_id='.$orderid.' group by t.lid';
        //}else{
                $sql = 'select sum(t.amount) as all_amount,count(t.zhiamount) as all_zhiamount,sum(t2.retreat_amount) as retreat_num,t1.set_name,t.* from nb_order_product t left join nb_product_set t1 on(t.dpid = t1.dpid and t.set_id = t1.lid) left join nb_order_retreat t2 on(t.dpid = t2.dpid and t.lid = t2.order_detail_id) where t.order_id='.$orderid.' group by t.lid';
        //}//var_dump($sql);exit;
        $allmoney = Yii::app()->db->createCommand($sql)->queryAll();
        //var_dump($allmoney);exit;
        $sql1 = 'select t.pay_amount from nb_order_pay t where t.paytype =11 and t.order_id ='.$orderid;
        $model = Yii::app()->db->createCommand($sql1)->queryRow();
        $change = $model['pay_amount']?$model['pay_amount']:0;
        //var_dump($models);exit;
        $sql2 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.paytype in(0,11) and t.pay_amount >0 and  t.order_id ='.$orderid;
        $models = Yii::app()->db->createCommand($sql2)->queryRow();
        $money = $models['all_money']?$models['all_money']:0;

        $sql4 = 'select sum(t.pay_amount) as all_money from nb_order_pay t where t.pay_amount <0 and t.order_id ='.$orderid;
        $models = Yii::app()->db->createCommand($sql4)->queryRow();
        $retreat = $models['all_money']?$models['all_money']:0;

        $sql3 = 'select t1.name,t.* from nb_order_pay t left join nb_payment_method t1 on(t.dpid = t1.dpid and t.payment_method_id = t1.lid) where t.paytype not in (0,11) and t.order_id='.$orderid.' group by t.payment_method_id,t.paytype';
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
     		if($money < 0){
     			if($user->remain_money > -$money){
     				$all_money = $user->remain_money + $money;
     				$sql = 'update nb_brand_user set update_at ="'.date('Y-m-d H:i:s',time()).'",remain_money ='.$all_money.' where dpid ='.$dpid.' and lid ='.$userid;
     			}else{
     				$all_money = 0;
     				$all_bmoney = $user->remain_money + $user->remain_back_money + $money;
     				$sql = 'update nb_brand_user set update_at ="'.date('Y-m-d H:i:s',time()).'",remain_money ='.$all_money.',remain_back_money ='.$all_bmoney.' where dpid ='.$dpid.' and lid ='.$userid;
     			}
     		}else{
     			$all_money = $user->remain_back_money + $money;
     			$sql = 'update nb_brand_user set update_at ="'.date('Y-m-d H:i:s',time()).'",remain_back_money ='.$all_money.' where dpid ='.$dpid.' and lid ='.$userid;
     		}
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
     
     public function actionCreatedp(){
     	$provinces = Yii::app()->request->getParam('province',0);
     	$citys = Yii::app()->request->getParam('city',0);
     	$areas = Yii::app()->request->getParam('area',0);
     	$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
     	$num =  Yii::app()->request->getParam('num');
     	$criteria = new CDbCriteria;
     	$criteria->with = 'property';
     	$criteria->condition =' t.delete_flag=0 and t.dpid in (select tt.dpid from nb_company tt where tt.comp_dpid='.$companyId.' and tt.delete_flag=0 and tt.type=1)';
     	// echo $criteria->condition;exit();
     	$province = $provinces;
     	$city = $citys;
     	$area = $areas;
     
     	if($citys == '市辖区'|| $citys == '省直辖县级行政区划' || $citys == '市辖县'){
     		$city = '0';
     	}
     	if($areas == '市辖区'){
     		$area = '0';
     	}
     	if($province){
     		$criteria->addCondition('t.province like "'.$province.'"');
     	}
     	if($city){
     		$criteria->addCondition('t.city like "'.$city.'"');
     	}
     	if($area){
     		$criteria->addCondition('t.county_area like "'.$area.'"');
     	}
     	$criteria->order = 't.dpid asc';
     	// var_dump($criteria);exit;
     	$pages = new CPagination(Company::model()->count($criteria));
     	//      $pages->setPageSize(1);
     	$pages->applyLimit($criteria);
     	$models = Company::model()->findAll($criteria);
     	// var_dump($models);exit;
     	$this->render('createdp',array(
     			'models'=> $models,
     			'pages'=>$pages,
     			'province'=>$provinces,
     			'city'=>$citys,
     			'area'=>$areas,
     			'num'=>$num
     	));
     }
     public function actionDp(){
     	$num =  Yii::app()->request->getParam('num');
     	// var_dump($checkbox_names);exit;
     	$sql = "select b.*,c.company_name from nb_brand_user_admin b,nb_company c where b.brand_user_id=".$num." and c.dpid=b.admin_dpid and b.delete_flag=0";
     	$models = Yii::app()->db->createCommand($sql)->queryAll();
     	// var_dump($models);exit;
     	$this->render('dp',array(
     			'num'=>$num,
     			'models'=>$models
     	));
     
     }
     public function actionDpcreate(){
     	$db = Yii::app()->db;
     	$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
     	$companyIds = Yii::app()->request->getParam('companyIds');
     	$num = Yii::app()->request->getParam('num');
     	if(!empty($companyIds)){
     		foreach ($companyIds as $admin_dpid) {
     			$sql = "select * from nb_brand_user_admin where brand_user_id=".$num." and admin_dpid=".$admin_dpid." and delete_flag=0";
     			// echo $sql;exit();
     			$user_admin = Yii::app()->db->createCommand($sql)->queryRow();
     			// var_dump($user_admin);exit;
     			if(empty($user_admin)){
     				$userid = new Sequence("brand_user_admin");
     				$id = $userid->nextval();
     				$data = array(
     						'lid'=>$id,
     						'dpid'=>$companyId,
     						'create_at'=>date('Y-m-d H:i:s',time()),
     						'update_at'=>date('Y-m-d H:i:s',time()),
     						'brand_user_id'=>$num,
     						'admin_dpid'=>$admin_dpid,
     				);
     				$command = $db->createCommand()->insert('nb_brand_user_admin',$data);
     			}
     		}
     		Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
     		$this->redirect(array('WechatMember/createdp','num'=>$num , 'companyId' =>$this->companyId ));
     	}else{
     		Yii::app()->user->setFlash('error',yii::t('app','请选择店铺！'));
     		$this->redirect(array('WechatMember/createdp','num'=>$num,'companyId' => $this->companyId ));
     	}
     }
     public function actionDeletedp(){
     	$num =  Yii::app()->request->getParam('num');
     	$checkbox_names = Yii::app()->request->getParam('checkbox_name');
     	$checkbox_names = json_encode($checkbox_names);
     	$checkbox_names = str_replace('[', '', $checkbox_names);
     	$checkbox_names = str_replace(']', '', $checkbox_names);
     	$checkbox_names = str_replace('"', '', $checkbox_names);
     	// var_dump($checkbox_names); exit();
     	$sql = "update nb_brand_user_admin set delete_flag=1 where lid in (".$checkbox_names.")";
     	$res = Yii::app()->db->createCommand($sql)->execute();
     	// var_dump($res);exit();
     	if(empty($res)){
     		Yii::app()->user->setFlash('error',yii::t('app','删除失败'));
     		$this->redirect(array('WechatMember/dp','num'=>$num,'companyId' => $this->companyId));
     	}else{
     		Yii::app()->user->setFlash('success',yii::t('app','删除成功'));
     		$this->redirect(array('WechatMember/dp','num'=>$num,'companyId' => $this->companyId));
     	}
     }
}