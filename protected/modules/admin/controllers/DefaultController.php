<?php

class DefaultController extends BackendController
{
        public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
    
	public function actionIndex()
	{
		$typeId = Yii::app()->request->getParam('typeId');
                /*$stypeId = Yii::app()->request->getParam('stypeId','0');
                $sistemp = Yii::app()->request->getParam('sistemp','0');
                $ssid = Yii::app()->request->getParam('ssid','0');
                $op = Yii::app()->request->getParam('op','0');
                $title='请选择餐桌';
                $geturl='/op/'.$op.'/sistemp/'.$sistemp.'/ssid/'.$ssid.'/stypeId/'.$stypeId;*/
                //$siteNmae='';
                $siteTypes = SiteClass::getTypes($this->companyId);
                if(empty($siteTypes)) {
			$typeId='tempsite';
		}
                //$modelsitet = SiteType::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $typeId,':dpid'=>  $this->companyId));
                /*if($op=='switch')
                {
                    if($sistemp=='0')
                    {
                        $title='被换餐桌：'.$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
                    }else{
                        $title='被换餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
                    }
                } */               
		
                if($typeId != 'tempsite')
                {
                    $typeKeys = array_keys($siteTypes);
                    $typeId = array_search($typeId, $typeKeys) ? $typeId : $typeKeys[0] ;
                }
                /*
		$criteria = new CDbCriteria;
		$models=array();
                if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$this->companyId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = Site::model()->findAll($criteria);
                }*/
                //var_dump($models);exit;
		$this->render('index',array(
				'siteTypes' => $siteTypes,
				//'models'=>$models,
				'typeId' => $typeId,
                                //'title' => $title,
                                //'geturl' => $geturl,
                                //'ssid' => $ssid,
                                //'sistemp' => $sistemp
		));
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