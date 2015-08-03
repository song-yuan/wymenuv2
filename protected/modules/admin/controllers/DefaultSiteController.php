<?php

class DefaultSiteController extends BackendController
{
        
        public function actionShowSite()
	{
		$typeId = Yii::app()->request->getParam('typeId');
                $compayId=Yii::app()->request->getParam('companyId');
                $stypeId = Yii::app()->request->getParam('stypeId','0');
                $sistemp = Yii::app()->request->getParam('sistemp','0');
                $ssid = Yii::app()->request->getParam('ssid','0');
                $op = Yii::app()->request->getParam('op','0');
                $title=yii::t('app','请选择餐桌');
                $criteria = new CDbCriteria;
		$models=array();
                if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$compayId ;
                        $criteria->order = ' t.site_id asc ';
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$compayId ;
                        $criteria->order = ' t.serial asc ';
                        $models = Site::model()->findAll($criteria);
                }
                if($op=='switch')
                {
                    if($sistemp=='0')
                    {
                        $siteTypes = SiteClass::getTypes($this->companyId);
                        $title=yii::t('app','被换餐桌：').$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.yii::t('app','::请选择目标餐桌');
                    }else{
                        $title=yii::t('app','被换餐桌：临时台/排队-->').($ssid%1000).yii::t('app','：：请选择目标餐桌');
                    }
                }
                elseif($op=='union')
                {
                    if($sistemp=='0')
                    {
                        $siteTypes = SiteClass::getTypes($this->companyId);
                        $title=yii::t('app','被并餐桌：').$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.yii::t('app','：：请选择目标餐桌');
                    }else{
                        $title=yii::t('app','被并餐桌：临时台/排队-->').($ssid%1000).yii::t('app','：：请选择目标餐桌');
                    }
                }
		$this->renderPartial('indexsite',array(
				'models'=>$models,
				'typeId' => $typeId,
                                'title' => $title,
                                'ssid' => $ssid,
                                'sistemp' => $sistemp,
                                'stypeId'=>$stypeId,
                                'op'=>$op
		));
	}
                
        public function actionButton() {
		$sid = Yii::app()->request->getParam('sid','0');
                $status = Yii::app()->request->getParam('status','0');
                $istemp = Yii::app()->request->getParam('istemp','0');
                $typeId = Yii::app()->request->getParam('typeId','0');
                $criteria2 = new CDbCriteria;
                $criteria2->condition =  't.status in ("1","2","3") and t.dpid='.$this->companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                $criteria2->order = ' t.lid desc ';
                $siteNo = SiteNo::model()->find($criteria2);
//                
 //               var_dump($siteNo);exit;
                if(empty($siteNo))
                {
                    $status="0";
                }else{
                    $status=$siteNo->status;
                }
		$model=array();
		$this->renderPartial('button' , array(
				'model' => $model,
				'sid' => $sid,
                                'status' => $status,                                
                                'istemp' => $istemp,
                                'typeId' => $typeId
		));
	}
        
        public function actionOpensite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $siteNumber = Yii::app()->request->getPost('siteNumber');
                        $companyId = Yii::app()->request->getPost('companyId');
                        //$sid = Yii::app()->request->getPost('sid');
                        $istemp = Yii::app()->request->getPost('istemp','0');                        
                        echo json_encode(SiteClass::openSite($companyId,$siteNumber,$istemp,$sid));
                        return true;
		}
	}
        
        public function actionClosesite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $companyId = Yii::app()->request->getPost('companyId');
                        $istemp = Yii::app()->request->getPost('istemp','0');
                        
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {  
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status='7' where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }

                            $sqlsiteno="update nb_site_no set status='7' where site_id=:sid and is_temp=:istemp and dpid=:companyId and status in ('1','2')";
                            $commandsiteno=$db->createCommand($sqlsiteno);
                            $commandsiteno->bindValue(":sid" , $sid);
                            $commandsiteno->bindValue(":istemp" , $istemp);
                            $commandsiteno->bindValue(":companyId" , $companyId);
                            $commandsiteno->execute();
                            
                            $sqlorder="update nb_order set order_status='7' where site_id=:sid and is_temp=:istemp and dpid=:companyId and order_status in ('1','2')";
                            $commandorder=$db->createCommand($sqlorder);
                            $commandorder->bindValue(":sid" , $sid);
                            $commandorder->bindValue(":istemp" , $istemp);
                            $commandorder->bindValue(":companyId" , $companyId);
                            $commandorder->execute();
                            
                            $sqlfeedback = "update nb_order_feedback set is_deal='1' where dpid=:companyId and site_id=:siteId and is_temp=:istemp";
                            $commandfeedback = $db->createCommand($sqlfeedback);
                            $commandfeedback->bindValue(":companyId",$companyId);
                            $commandfeedback->bindValue(":siteId",$sid);
                            $commandfeedback->bindValue(":istemp",$istemp);
                            //var_dump($sqlsite);exit;
                            $commandfeedback->execute();
                            
                            //FeedBackClass::cancelAllOrderMsg($sid,$istemp,"0000000000",$companyId);
                            
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            //
                            $criteria = new CDbCriteria;
                            $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                            $criteria->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria);
                            SiteClass::deleteCode($siteNo->dpid,$siteNo->code);
                            //var_dump($sid);exit;
                            
                            //apc_delete($siteNo->dpid.$siteNo->code);
                            echo json_encode(array('status'=>1,'message'=>yii::t('app','撤台成功')));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>yii::t('app','撤台失败')));
                            return false;
                    }
		}
	}
        
        public function actionSwitchsite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $companyId = Yii::app()->request->getPost('companyId');
                        $istemp = Yii::app()->request->getPost('istemp','0');
                        $ssid = Yii::app()->request->getPost('ssid',0);
                        $sistemp = Yii::app()->request->getPost('sistemp','0');
                        //echo json_encode(array('status'=>0,'message'=>$sid.'dd'.$companyId.'dd'.$istemp.'dd'.$ssid.'dd'.$sistemp));exit;
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {
                            $number=0;
                            $status='1';
                            
                            $smodelsn = SiteNo::model()->find('dpid=:companyId and delete_flag=0 and site_id=:lid and is_temp=:istemp and status in ("1","2","3")' , array(':companyId' => $companyId,':lid'=>$ssid,':istemp'=>$sistemp)) ;
                            
                            if($sistemp=='0')
                            {
                                $smodel = Site::model()->find('dpid=:companyId and delete_flag=0 and lid=:lid' , array(':companyId' => $companyId,':lid'=>$ssid)) ;
                                $number=$smodel->number;
                                $status=$smodel->status;
                            }else{
                                $number=$smodelsn->number;
                                $status=$smodelsn->status;
                            }
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status=:status,number=:number where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":number" , $number);
                                $commandsite->bindValue(":status" , $status);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            if($sistemp=="0")
                            {
                                $sqlsite="update nb_site set status='6' where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":sid" , $ssid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            $se=new Sequence("site_no");
                            $lid = $se->nextval();
                            $data = array(
                                'lid'=>$lid,
                                'dpid'=>$companyId,
                                'create_at'=>date('Y-m-d H:i:s',time()),
                                'is_temp'=>$istemp,
                                'site_id'=>$sid,
                                'status'=>$status,
                                'code'=>$smodelsn->code,
                                'number'=>$smodelsn->number,
                                'delete_flag'=>'0'
                            );
                            $db->createCommand()->insert('nb_site_no',$data);
                            
                            $smodelsn->status='6';
                            $smodelsn->save();
                            
                            $sqlorder="update nb_order set is_temp=:istemp,site_id=:sid where site_id=:ssid and is_temp=:sistemp and dpid=:companyId and order_status in ('1','2','3')";
                            $commandorder=$db->createCommand($sqlorder);
                            $commandorder->bindValue(":sid" , $sid);
                            $commandorder->bindValue(":istemp" , $istemp);
                            $commandorder->bindValue(":ssid" , $ssid);
                            $commandorder->bindValue(":sistemp" , $sistemp);
                            $commandorder->bindValue(":companyId" , $companyId);
                            $commandorder->execute();
                                                 
                            
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            echo json_encode(array('status'=>1,'message'=>yii::t('app','换台成功')));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>yii::t('app','换台失败')));
                            return false;
                    }
		}                
	}
        
        public function actionUnionsite() {
		if(Yii::app()->request->isPostRequest) {
			$sid = Yii::app()->request->getPost('sid');
                        $companyId = Yii::app()->request->getPost('companyId');
                        $istemp = Yii::app()->request->getPost('istemp','0');
                        $ssid = Yii::app()->request->getPost('ssid',0);
                        $sistemp = Yii::app()->request->getPost('sistemp','0');
                        //echo json_encode(array('status'=>0,'message'=>$sid.'dd'.$companyId.'dd'.$istemp.'dd'.$ssid.'dd'.$sistemp));exit;
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {
                            $number=0;
                            $status='1';                            
                            $smodelsn = SiteNo::model()->find('dpid=:companyId and delete_flag=0 and site_id=:lid and is_temp=:istemp and status in ("1","2")' , array(':companyId' => $companyId,':lid'=>$ssid,':istemp'=>$sistemp)) ;
                            
                            if($sistemp=='0')
                            {
                                $smodel = Site::model()->find('dpid=:companyId and delete_flag=0 and lid=:lid' , array(':companyId' => $companyId,':lid'=>$ssid)) ;
                                $number=$smodel->number;
                                $status=$smodel->status;
                            }else{
                                $number=$smodelsn->number;
                                $status=$smodelsn->status;
                            }
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status=IF(:sstatus>status,:sstatus,status),number=number+:snumber where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":snumber" , $number);
                                $commandsite->bindValue(":sstatus" , $status);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            if($sistemp=="0")
                            {
                                $sqlsite="update nb_site set status='5' where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":sid" , $ssid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            //更新目标site_no人数和状态
                            $modelsn=SiteNo::model()->find('dpid=:companyId and delete_flag=0 and site_id=:lid and is_temp=:istemp and status in ("1","2")' , array(':companyId' => $companyId,':lid'=>$sid,':istemp'=>$istemp)) ;
                            if($status > $modelsn->status)
                            {
                                $modelsn->status=$status;
                            }
                            $modelsn->number=$modelsn->number+$number;
                            $modelsn->save();
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            //更新源site_no，让上网密码code 指向目标订单
                            $smodelsn->status='5';
                            $smodelsn->site_id=$modelsn->site_id;
                            $smodelsn->is_temp=$modelsn->is_temp;
                            $smodelsn->save();
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            //更新目标订单状态和人数
                            $tocriteria = new CDbCriteria;
                            $tocriteria->condition =  ' t.order_status in ("1","2") and  t.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                            $tocriteria->order = ' t.lid desc ';
                            $torder = Order::model()->find($tocriteria);
                            if($status > $torder->order_status)
                            {
                                $torder->order_status=$status;
                            }
                            $torder->number=$torder->number+$number;
                            $torder->save();
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            //...
                            //更新源订单状态
                            $socriteria = new CDbCriteria;
                            $socriteria->condition =  ' t.order_status in ("1","2") and  t.dpid='.$companyId.' and t.site_id='.$ssid.' and t.is_temp='.$sistemp ;
                            $socriteria->order = ' t.lid desc ';
                            $sorder = Order::model()->find($socriteria);
                            $sorder->order_status="5";
                            $sorder->save();
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
                            //...
                            //更新源订单明细，指向目标订单。
                            $sqlorder="update nb_order_product set order_id=:torderid where dpid=:companyId and order_id=:sorderid";
                            $commandorder=$db->createCommand($sqlorder);
                            $commandorder->bindValue(":torderid" , $torder->lid);
                            $commandorder->bindValue(":sorderid" , $sorder->lid);
                            $commandorder->bindValue(":companyId" , $companyId);
                            $commandorder->execute();                           
                            //echo json_encode(array('status'=>0,'message'=>$number.'dd'.$status));exit;
//                            $sqlorder="update nb_order set is_temp=:istemp,site_id=:sid where site_id=:ssid and is_temp=:sistemp and dpid=:companyId and order_status in ('1','2','3')";
//                            $commandorder=$db->createCommand($sqlorder);
//                            $commandorder->bindValue(":sid" , $sid);
//                            $commandorder->bindValue(":istemp" , $istemp);
//                            $commandorder->bindValue(":ssid" , $ssid);
//                            $commandorder->bindValue(":sistemp" , $sistemp);
//                            $commandorder->bindValue(":companyId" , $companyId);
//                            $commandorder->execute();
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            echo json_encode(array('status'=>1,'message'=>yii::t('app','并台成功')));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>yii::t('app','并台失败')));
                            //echo json_encode(array('status'=>0,'message'=>  var_dump($e)));
                            return false;
                    }
		}                
	}
        
}