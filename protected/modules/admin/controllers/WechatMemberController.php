<?php

class WechatMemberController extends BackendController {
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
        $companyId = Yii::app()->request->getParam('companyId'); 
        $brand_user_model = '';
        $cupon_model = '';

        $orderPay = '';
        $cashback = 0;
       
        
        $criteria = new CDbCriteria;
        $criteria->with = array('point','level','cupon_branduser');
        $criteria->addCondition("t.dpid=".$this->companyId ." or t.weixin_group = ".$this->companyId);
        $criteria->addCondition("t.lid=".$num);        
       
        $brand_user_model = BrandUser::model()->find($criteria);          
       
         
        $now = date('Y-m-d H:i:s',time());

        $db = Yii::app()->db; 
        $sql = 'select sum(remain_cashback_num) as total from nb_cashback_record where brand_user_lid = '.$num.' and dpid='.$this->companyId.' and delete_flag=0 and ((point_type=0 and begin_timestamp < "'.$now.'" and end_timestamp > "'.$now.'") or point_type=1)';
        $back = Yii::app()->db->createCommand($sql)->queryRow();
        if($back){
            $cashback= $back['total'];           
        }

        $orderPay = OrderPay::model()->with('order4')->findAll("t.paytype in (8,9,10) and t.remark='".$card_id."' and t.dpid='".$this->companyId."'");
          
        $cupon_model =  Cupon::model()->findAll("t.delete_flag<1 and t.is_available<1 and t.dpid=".$this->companyId);            

         // var_dump(BrandUser::model()->findAll($criteria));exit; 
        $this->render('searchdetail',array( 'brand_user_model'=> $brand_user_model,
                                       
                                        'cupon_model'=> $cupon_model,
                                        'orderPay'=>$orderPay,
                                        'cashback'=>$cashback
                    )
                    );
    }
    public function actionSearch(){
        $db=Yii::app()->db;
        $companyId = Yii::app()->request->getParam('companyId',"0000000000");       
        $more = Yii::app()->request->getPost('more',"0");
        $findsex = Yii::app()->request->getPost('findsex',"%");
        $agefrom = Yii::app()->request->getPost('agefrom',"0");
        $ageto = Yii::app()->request->getPost('ageto',"100");
        $birthfrom = Yii::app()->request->getPost('birthfrom',"01-01");
        $birthto = Yii::app()->request->getPost('birthto',"12-31");
        $finduserlevel=Yii::app()->request->getPost('finduserlevel',"0000000000");
      
        $noordertime=Yii::app()->request->getPost('noordertime',"%");
        $findcountry=Yii::app()->request->getPost('findcountry',"%");
        $findprovince=Yii::app()->request->getPost('findprovince',"%");
        $findcity=Yii::app()->request->getPost('findcity',"%");
        $pointfrom = Yii::app()->request->getPost('pointfrom',"0");       
        $cardmobile = Yii::app()->request->getPost('cardmobile',"%");
        if(empty($cardmobile))
        {
                $cardmobile="%";
        }
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
        $sql="select t.lid,t.dpid,t.card_id,t.user_name,t.nickname,t.sex,t.user_birthday,tl.level_name,t.weixin_group,t.country "
            .",t.province,t.city,t.mobile_num,com.dpid,com.company_name"				
            . " from nb_brand_user t "
            . " LEFT JOIN  nb_company com on com.dpid = t.weixin_group "  
            . " LEFT JOIN (select dpid,user_id from nb_order"
            . " where order_type in ('1','2','6') and order_status in ('3','4','8')"
            . " group by dpid,user_id) tct on t.dpid = tct.dpid and t.lid = tct.user_id "
            . " LEFT JOIN nb_brand_user_level tl on tl.dpid = t.dpid and tl.lid = t.user_level_lid and tl.delete_flag = 0 and tl.level_type = 1 "            
            . " where t.lid not in(".$users.") and t.dpid = ".$companyId." ";
           // echo $sql;exit;    
        if($finduserlevel!="0000000000")
        {
              $sql.= " and tl.lid = ".$finduserlevel;
        }
        if($findsex!="%")
        {
               $sql.= "and t.sex like '".$findsex."'";
        }
        if($findcountry!="%")
        {
               $sql.= " and t.country like '".$findcountry."'";
        }
        if($findprovince!="%")
        {
               $sql.= " and t.province like '".$findprovince."'";
        }
        if($findcity!="%")
        {
               $sql.= " and t.city like '".$findcity."'";
        }
        if($cardmobile!="%")
        {
               $sql.= " and (t.card_id like '%".$cardmobile."%' or t.mobile_num like '%".$cardmobile."%')";
        }
      
        
        $yearnow=date('Y',time());
        $yearbegin=$yearnow-$ageto;
        $yearend=$yearnow-$agefrom;
        $sql.= " and substring(ifnull(t.user_birthday,'1919-06-26'),1,4) >= '".$yearbegin."' and substring(ifnull(t.user_birthday,'1919-06-26'),1,4) <= '".$yearend."'";
        $sql.= " and substring(ifnull(t.user_birthday,'1919-06-26'),6,5) >= '".$birthfrom."' and substring(ifnull(t.user_birthday,'1919-06-26'),6,5) <= '".$birthto."'";

        $models = $pdata =$db->createCommand($sql)->queryAll();
        $pages = new CPagination(count($models));  
        $pages->pageSize = 10;
        $pages->applylimit($criteria);
        $models=Yii::app()->db->createCommand($sql." LIMIT :offset,:limit");
        $models->bindValue(':offset', $pages->currentPage*$pages->pageSize);
        $models->bindValue(':limit', $pages->pageSize);
        $models=$models->queryAll();


        //检索条件会员等级
        $criteriauserlevel = new CDbCriteria;
        $criteriauserlevel->condition =  ' t.delete_flag=0 and t.dpid='.$companyId;
        $userlevels = BrandUserLevel::model()->findAll($criteriauserlevel);

        //获取国家、省、市
        $sqlcountry="select distinct country from nb_brand_user where dpid=".$companyId;
        $modelcountrys=$db->createCommand($sqlcountry)->queryAll();
        //$findcountry="中国";

        $sqlprovince="select distinct country,province from nb_brand_user where dpid=".$companyId;
        $modelprovinces=$db->createCommand($sqlprovince)->queryAll();
        //$findprovince="上海市";

        $sqlcity="select distinct country,province,city from nb_brand_user where dpid=".$companyId;
        $modelcitys=$db->createCommand($sqlcity)->queryAll();
        //$findcity="杨浦区";

       
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
		       
	        'modelcountrys'=>$modelcountrys,
                'modelprovinces'=>$modelprovinces,
	        'modelcitys'=>$modelcitys,
                'noordertime'=>$noordertime,
	        'findcountry'=>$findcountry,
	        'findprovince'=>$findprovince,
                'findcity'=>$findcity,
                'pointfrom'=>$pointfrom,
                'cardmobile'=>$cardmobile,
                'more'=>$more,
			));
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
}


