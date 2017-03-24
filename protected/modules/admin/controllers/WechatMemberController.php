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
	public function actionSearch(){
	   $brand_user_model='';
	   $cupon_model = '';
	   
	   $orderPay='';
	   $cashback=0;
	   $userLid = 0;
	   if(Yii::app()->request->isPostRequest){
	       $num = Yii::app()->request->getPost('num');
	        if($num !=''){
	          $card_id = $num;
	        //查找主要信息。
	          $brand_user_model = BrandUser::model()->with(array('point','level','cupon_branduser'))->find("t.dpid='".$this->companyId ."'and (t.card_id like '%".$card_id."%' or t.mobile_num='".$num."')");          
	          
	          if($brand_user_model){
	              $userLid = $brand_user_model->lid;
                      $card_id = $brand_user_model->card_id;
	          }else{
			Yii::app()->user->setFlash('error' ,yii::t('app', '没有查询到该会员'));                
		  }
	        $now = date('Y-m-d H:i:s',time());
               
	        $db = Yii::app()->db; 
	        $sql = 'select sum(remain_cashback_num) as total from nb_cashback_record where brand_user_lid = '.$userLid.' and dpid='.$this->companyId.' and delete_flag=0 and ((point_type=0 and begin_timestamp < "'.$now.'" and end_timestamp > "'.$now.'") or point_type=1)';
	        $back = Yii::app()->db->createCommand($sql)->queryRow();
	        $cashback= $back['total'];
	          $orderPay = OrderPay::model()->with('order4')->findAll("t.paytype in (8,9,10) and t.remark='".$card_id."' and t.dpid='".$this->companyId."'");
	         }   
	        $cupon_model =  Cupon::model()->findAll("t.delete_flag<1 and t.is_available<1 and t.dpid=".$this->companyId);            
	          }
	       
	        $this->render('search',array( "brand_user_model"=> $brand_user_model,
	                                        "cupon_model"=> $cupon_model,
	                                        'orderPay'=>$orderPay,
	                                        'cashback'=>$cashback
	                    )
	                    );
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
        $entity = BrandUserLevel::model()->findALL('dpid='.$this->companyId.' and level_type=0 and delete_flag=0  order by level_discount desc');
        $company = Company::model()->find('dpid='.$this->companyId);
        if($company['type'] > 0){
            $dpid = $company['comp_dpid'];
        }
        $weixin = BrandUserLevel::model()->findALL('dpid = '.$dpid .' and level_type=1 and delete_flag=0  order by level_discount desc ');
        $binds = MemberCardBind::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$this->companyId));
       
        if(Yii::app()->request->isPostRequest) {
           $test = Yii::app()->request->getParam('bind');
         
            
            foreach($test as $key => $val){
                $bind = MemberCardBind::model()->find('lid=:lid and dpid=:dpid',array());
                if(!$bind){
                    $bind = new MemberCardBind();
                    $se=new Sequence("member_card_bind");
                    $lid = $se->nextval();
                    $bind->lid = $lid;
                }  
                $bind->membercard_level_id = $key;
                $bind->branduser_level_id =$val;
                $bind->dpid = $this->companyId;
                $bind->create_at = date('Y-m-d H:i:s',time());
                $bind->update_at = date('Y-m-d H:i:s',time());
                $bind->save();
            }
              Yii::app()->user->setFlash('success',yii::t('app','绑定成功！'));
            $this->redirect(array('WechatMember/list' , 'companyId' => $this->companyId ));
        }
       // var_dump($bind->attributes);exit;
        $this->render('chain',array(
                    "entity" => $entity,
                    "weixin" => $weixin
                    )  
                );
     }
}


