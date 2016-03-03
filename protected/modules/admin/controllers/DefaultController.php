<?php

class DefaultController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
                $controllerId = Yii::app()->controller->getId();
		$action = Yii::app()->controller->getAction()->getId();
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
                
		return true;
	}
    
//	public function actionIndex()
//	{
//		$typeId = Yii::app()->request->getParam('typeId');
//                /*$stypeId = Yii::app()->request->getParam('stypeId','0');
//                $sistemp = Yii::app()->request->getParam('sistemp','0');
//                $ssid = Yii::app()->request->getParam('ssid','0');
//                $op = Yii::app()->request->getParam('op','0');
//                $title='请选择餐桌';
//                $geturl='/op/'.$op.'/sistemp/'.$sistemp.'/ssid/'.$ssid.'/stypeId/'.$stypeId;*/
//                //$siteNmae='';
//                $siteTypes = SiteClass::getTypes($this->companyId);
//                if(empty($siteTypes)) {
//			$typeId='tempsite';
//		}
//                //$modelsitet = SiteType::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $typeId,':dpid'=>  $this->companyId));
//                /*if($op=='switch')
//                {
//                    if($sistemp=='0')
//                    {
//                        $title='被换餐桌：'.$siteTypes[$stypeId];
//                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
//                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
//                    }else{
//                        $title='被换餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
//                    }
//                } */               
//		
//                if($typeId != 'tempsite')
//                {
//                    $typeKeys = array_keys($siteTypes);
//                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
//                }
//                /*
//		$criteria = new CDbCriteria;
//		$models=array();
//                if($typeId == 'tempsite'){
//                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$this->companyId ;
//                        $criteria->order = ' t.create_at desc ';
//                        $models = SiteNo::model()->findAll($criteria);
//                }else{
//                        $criteria->with = 'siteType';
//                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
//                        $criteria->order = ' t.create_at desc ';
//                        $models = Site::model()->findAll($criteria);
//                }*/
//                //var_dump($models);exit;
//		$this->render('index',array(
//				'siteTypes' => $siteTypes,
//				//'models'=>$models,
//				'typeId' => $typeId,
//                                //'title' => $title,
//                                //'geturl' => $geturl,
//                                //'ssid' => $ssid,
//                                //'sistemp' => $sistemp
//		));
////	}
        
        public function actionIndex()
	{              
                //           
                $companyId=Yii::app()->request->getParam('companyId','0');
                $typeId = Yii::app()->request->getParam('typeId','0');
                $siteTypes = SiteClass::getTypes($this->companyId);
                
                
                if(empty($siteTypes)) {
			$typeId='tempsite';
		}
                if($typeId != 'tempsite')
                {
                    $typeKeys = array_keys($siteTypes);
                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
                }
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
		$criteria->order = ' pid,lid ';		
		$categories = ProductCategory::model()->findAll($criteria);	
		
		$criteria = new CDbCriteria;
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
		$criteria->addCondition('t.fee_type in(1,2,3)');
		$criteria->order = ' t.fee_type asc,t.lid asc';
		$feeTypes = CompanyBasicFee::model()->findAll($criteria);
//		var_dump($feeTypes);exit;
//                var_dump($categories);exit;
                $criteriaps = new CDbCriteria;
		$criteriaps->condition =  't.delete_flag=0 and t.dpid='.$companyId ;
                $criteriaps->with="productsetdetail";
		$criteriaps->order = ' t.lid asc ';		
		$productSets = ProductSet::model()->findAll($criteriaps);
                $setprice=array();
                foreach ($productSets as $productSet)
                {
                    $sqlsetsum="select sum(price * number) as tprice from nb_product_set_detail where dpid=".$companyId." and set_id=".$productSet->lid." and is_select=1 and delete_flag=0";
                    $nowval= Yii::app()->db->createCommand($sqlsetsum)->queryScalar();
                    $setprice[$productSet->lid]=empty($nowval)?"0.00":$nowval;
                }                
                
                //var_dump($setprice);exit;                
                $criteriap = new CDbCriteria;
		$criteriap->condition =  'delete_flag=0 and t.dpid='.$companyId ;// and is_show=1
		$criteriap->order = ' t.category_id asc,t.lid asc ';
                $products =  Product::model()->findAll($criteriap);
                //var_dump($setprice);exit;                
                $criteriapo = new CDbCriteria;
		$criteriapo->condition =  'delete_flag=0 and t.dpid='.$companyId ;// and is_show=1
		$paymentmethod = PaymentMethod::model()->findAll($criteriapo);
                //var_dump($paymentmethod);exit;
                $productidnameArr=array();
                foreach($products as $product)
                {
                    $productidnameArr[$product->lid]=$product->product_name;
                }
                //var_dump($productidnameArr);exit;
                $this->render('indexall',array(
                                'siteTypes' => $siteTypes,
                                'typeId' => $typeId,
                		'feeTypes'=> $feeTypes,
				"categories"=>$categories,
                                "productSets"=>$productSets,
                                'setprice'=>$setprice,
                                "products"=>$products,
                                "paymentmethod"=>$paymentmethod,
                                "pn"=>$productidnameArr
		));
	}
        
        public function actionError2()
	{
            $title = Yii::app()->request->getParam('title');
            //var_dump($title);exit;
            $this->render('error2',array(
				'title' => $title
				//'typeId' => $typeId,                                
		));
            //exit;
        }
        
        public function actionMsgnum()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $msgnum= OrderFeedback::model()->count(' dpid=:dpid and delete_flag=0 and is_deal=0',array(':dpid'=>$companyId));                
                Yii::app()->end(json_encode(array('status'=>true,'num'=>$msgnum)));
        }
        
        public function actionMessage()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $msgs=  FeedBackClass::getSiteGroupMessage($companyId);
                $msglen=count($msgs,0);
                //var_dump($msgs);exit;
                for($i=0;$i<$msglen;$i++)
                {
                    //var_dump(SiteClass::getSiteNmae($companyId, $msg['site_id'], $msg['is_temp']));
                    $msgs[$i]['name']= SiteClass::getSiteNmae($companyId, $msgs[$i]['site_id'], $msgs[$i]['is_temp']);
                    //var_dump($msg);exit;
                }
                //var_dump($msgs);exit;
                $this->renderPartial('message',array(
				'msgs' => $msgs
				//'typeId' => $typeId,                                
		));
        }
        
        public function actionShiftlogout()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $begin_time = Yii::app()->request->getParam('begin_time','0000-00-00 00:00:00');
                $save= Yii::app()->request->getParam('save',"0");
                $db = Yii::app()->db;
                $userarr=  explode("_", Yii::app()->user->userId);
                $sqllogintime='select create_at from nb_b_login where out_time="0000-00-00 00:00:00" and user_id ='.
                        $userarr[0].' and dpid="'.$userarr[1].'" order by create_at';
                $logintime=$db->createCommand($sqllogintime)->queryAll();
                if(empty($logintime))
                {
                    Yii::app()->user->logout();
                    //$this->redirect('index');
                }
                if($begin_time="0000-00-00 00:00:00")
                {
                    $begin_time=$logintime[0]["create_at"];
                }
                $end_time = date('Y-m-d H:i:s',time());
                //var_dump($begin_time,$end_time);exit;
                $sqlorder='select count(*) as ordernumber, sum(reality_total) as ordermoney from nb_order where order_status in (3,4,8) and dpid = "'.
                        $companyId.'" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'"'; 
                $orderdata=$db->createCommand($sqlorder)->queryRow();
                //var_dump($sqlorder,$orderdata);exit;
                $sqlmembercharge='select sum(reality_money) from nb_member_recharge where dpid="'.$companyId.
                        '" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'" and delete_flag=0';
                $memberCharge=$db->createCommand($sqlmembercharge)->queryScalar();
//                $sqlmemberconsume='select sum(consumer_money) from nb_member_consumer where dpid="'.$companyId.
//                        '" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'" and delete_flag=0';
//                $memberConsume=$db->createCommand($sqlmemberconsume)->queryScalar();
                $sqlmemberconsume='select sum(pay_amount) from nb_order_pay where paytype =4 and dpid = "'.
                        $companyId.'" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'"';
                //var_dump($sqlmemberconsume);exit;
                $memberConsume=$db->createCommand($sqlmemberconsume)->queryScalar();
                $sqlcash='select sum(pay_amount) from nb_order_pay where paytype =0 and dpid = "'.
                        $companyId.'" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'"';
                $cashTotal=$db->createCommand($sqlcash)->queryScalar();
                $sqlunion='select sum(pay_amount) from nb_order_pay where paytype =5 and dpid = "'.
                        $companyId.'" and update_at >="'.$begin_time.'" and update_at <="'.$end_time.'"';
                $unionTotal=$db->createCommand($sqlunion)->queryScalar();
                empty($orderdata['ordermoney'])?0:$orderdata['ordermoney'];
                //($memberCharge,$memberConsume,$cashTotal,$sqlunion,$unionTotal);exit;
                if($save=="1")
                {
                    //insert shift
                    $se=new Sequence("shift_detail");
                    $lid = $se->nextval(); 
                    //$userarray= explode("_",Yii::app()->user->userId);
                    $data = array(
                        'lid'=>$lid,
                        'dpid'=>$companyId,
                        'create_at'=>date('Y-m-d H:i:s',time()),
                        'update_at'=>date('Y-m-d H:i:s',time()),
                        'userid'=>$userarr[0],
                        'begin_time'=>$begin_time,
                        'end_time'=>$end_time,
                        'order_num'=>$orderdata['ordernumber'],
                        'order_money'=>empty($orderdata['ordermoney'])?0:$orderdata['ordermoney'],
                        'member_charge'=>empty($memberCharge)?0:$memberCharge,
                        'member_consume'=>empty($memberConsume)?0:$memberConsume,
                        'cash_total'=>empty($cashTotal)?0:$cashTotal,
                        'union_total'=>empty($unionTotal)?0:$unionTotal,
                        'weixin_total'=>0,
                        'zhifubao_total'=>0,
                        'other_total'=>0,
                        'delete_flag'=>"0"
                    );                            
                    Yii::app()->db->createCommand()->insert('nb_shift_detail',$data);
                    //update loginin
                    $sqlloginup='update nb_b_login set out_time="'.$end_time.'" where out_time="0000-00-00 00:00:00" and user_id ='.
                        $userarr[0].' and dpid='.$userarr[1];
                    $db->createCommand($sqlloginup)->execute();
                    
                    //Yii::app()->user->logout();
                    $this->redirect('/wymenuv2/admin/login');
                }
                $this->render('shiftlogout',array(
                                    'logintime' => $logintime,
                                    'begin_time' => $begin_time,
                                    'end_time' => $end_time,
                                    //'company_name'=>$companyName,
                                    'order_number'=>$orderdata['ordernumber'],
                                    'order_money'=>empty($orderdata['ordermoney'])?0:$orderdata['ordermoney'],
                                    'member_charge'=>empty($memberCharge)?0:$memberCharge,
                                    'member_consume'=>empty($memberConsume)?0:$memberConsume,
                                    'cash_total'=>empty($cashTotal)?0:$cashTotal,
                                    'union_total'=>empty($unionTotal)?0:$unionTotal
//                                    'weixin_total'=>$weixinTotal,
//                                    'zhifubao_total'=>$zhifubaoTotal,
//                                    ''
		));
        }
        
        public function actionMessageli()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $msgs=  FeedBackClass::getSiteGroupMessage($companyId);
                $msglen=count($msgs,0);
                //var_dump($msgs);exit;
                for($i=0;$i<$msglen;$i++)
                {
                    //var_dump(SiteClass::getSiteNmae($companyId, $msg['site_id'], $msg['is_temp']));
                    $msgs[$i]['name']= SiteClass::getSiteNmae($companyId, $msgs[$i]['site_id'], $msgs[$i]['is_temp']);
                    //var_dump($msg);exit;
                }
                //var_dump($msgs);exit;
                $this->renderPartial('messageli',array(
				'msgs' => $msgs
				//'typeId' => $typeId,                                
		));
        }
        
        public function actionMsglist()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                $site_id = Yii::app()->request->getParam('site_id');
                $is_temp = Yii::app()->request->getParam('is_temp');
                
                $criteria = new CDbCriteria;
		$criteria->addCondition(' dpid=:dpid and site_id=:siteid and is_temp=:istemp and is_deal=0 and delete_flag=0');
		$criteria->order = ' create_at ';
		$criteria->params[':dpid']=$companyId;
		$criteria->params[':siteid']=$site_id; 
		$criteria->params[':istemp']=$is_temp;
		$pages = new CPagination(OrderFeedback::model()->count($criteria));
		$pages->applyLimit($criteria);                
                $msgs=  OrderFeedback::model()->findAll($criteria);
                $siteName=SiteClass::getSiteNmae($companyId, $site_id, $is_temp);
                    //$msgs[$i]['name']= SiteClass::getSiteNmae($companyId, $msgs[$i]['site_id'], $msgs[$i]['is_temp']);
                $this->renderPartial('msglist',array(
                                'siteName'=>$siteName,
				'models'=>$msgs,
				'pages' => $pages                                
		));
        }
        
        /*
         * 2015/5/24消息列表更改，取消messageli和msglist方法
         */
        public function actionMessageliall()
	{
		$companyId = Yii::app()->request->getParam('companyId');
                //SELECT TIMESTAMPDIFF(SECOND,update_at,now()) from nb_pad
                /*$criteria = new CDbCriteria;
                $criteria->select='* , TIMESTAMPDIFF(SECOND,update_at,now()) timediff';
		$criteria->order = ' update_at ';
                $criteria->limit = 20;
		$criteria->addCondition(' dpid=:dpid and is_deal=0 and delete_flag=0');
		$criteria->params[':dpid']=$companyId;
                $msgs=  OrderFeedback::model()->findAll($criteria);*/
                $db = Yii::app()->db;
		$sql = "select *, TIMESTAMPDIFF(SECOND,update_at,now()) timediff"
                        . " from nb_order_feedback"
                        . " where dpid=".$companyId.' and is_deal=0 and delete_flag=0';
		$msgs= $db->createCommand($sql)->queryAll();
                //var_dump($msgs);exit;
		    //$msgs[$i]['name']= SiteClass::getSiteNmae($companyId, $msgs[$i]['site_id'], $msgs[$i]['is_temp']);
                $this->renderPartial('messageliall',array(
                                'msgs'=>$msgs                                
		));
        }
        
        public function actionReadfeedback(){
                $orderfeedbackid = Yii::app()->request->getParam('orderfeedbackid',0);
		$companyId = Yii::app()->request->getParam('companyId');
                $orderfeedback = OrderFeedback::model()->find('t.lid=:id and t.dpid=:dpid' , array(':id'=>$orderfeedbackid,':dpid'=>$companyId));
                $orderfeedback->is_deal='1';
                if($orderfeedback->save())
                {
                    Yii::app()->end(json_encode(array('status'=>true,'msg'=>yii::t('app','已读'))));
                }else{
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>yii::t('app','读取失败'))));
                }
        }
        
        public function actionGateway(){
                //var_dump( Yii::app()->request->baseUrl.'/protected/components/Config/Store.php');exit;
                
                var_dump(Gateway::getOnlineStatus());
                $store = Store::instance('wymenu');
                $printData = $store->get('0000000012');
                var_dump($printData);exit;
                $ret = $store->set('0000000012','上海滩的愛している222');
                echo "ddd";
                //Gateway::sendToAll(json_encode(array(a=>"上海滩的愛している",b=>"ddddd11111:")));
                Gateway::sendToAll('{"a":"上海滩的愛している","b":"ddddd11111:"}');  
                echo "eee";
        }
}