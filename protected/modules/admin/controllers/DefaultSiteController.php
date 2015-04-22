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
                $title='请选择餐桌';
                $criteria = new CDbCriteria;
		$models=array();
                if($typeId == 'tempsite'){
                        $criteria->condition =  't.delete_flag = 0 and t.status in ("1","2","3") and t.is_temp = 1 and t.dpid='.$compayId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = SiteNo::model()->findAll($criteria);
                }else{
                        $criteria->with = 'siteType';
                        $criteria->condition =  't.delete_flag = 0 and t.type_id = '.$typeId.' and t.dpid='.$compayId ;
                        $criteria->order = ' t.create_at desc ';
                        $models = Site::model()->findAll($criteria);
                }
                if($op=='switch')
                {
                    if($sistemp=='0')
                    {
                        $siteTypes = SiteClass::getTypes($this->companyId);
                        $title='被换餐桌：'.$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
                    }else{
                        $title='被换餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
                    }
                }
                elseif($op=='union')
                {
                    if($sistemp=='0')
                    {
                        $siteTypes = SiteClass::getTypes($this->companyId);
                        $title='被并餐桌：'.$siteTypes[$stypeId];
                        $modelsite = Site::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $ssid,':dpid'=>  $this->companyId));
                        $title=$title.'-->'.$modelsite->serial.'('.$modelsite->site_level.')'.'::请选择目标餐桌';
                    }else{
                        $title='被并餐桌：临时台/排队-->'.($ssid%1000).'：：请选择目标餐桌';
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
                        $db = Yii::app()->db;
                        $transaction = $db->beginTransaction();
                        try {                          
                            if($istemp=="0")
                            {
                                $sqlsite="update nb_site set status=1,number=:number where lid=:sid and dpid=:companyId";
                                $commandsite=$db->createCommand($sqlsite);
                                $commandsite->bindValue(":number" , $siteNumber);
                                $commandsite->bindValue(":sid" , $sid);
                                $commandsite->bindValue(":companyId" , $companyId);
                                $commandsite->execute();
                            }
                            
                            $se=new Sequence("site_no");
                            $lid = $se->nextval();
                            $site_id=$sid;
                            if($istemp!=0)
                            {
                                $se=new Sequence("temp_site");
                                $site_id = $se->nextval();                            
                            }
                            $code = SiteClass::getCode($companyId);
                            $data = array(
                                'lid'=>$lid,
                                'dpid'=>$companyId,
                                'create_at'=>date('Y-m-d H:i:s',time()),
                                'is_temp'=>$istemp,
                                'site_id'=>$site_id,
                                'status'=>'1',
                                'code'=>$code,
                                'number'=>$siteNumber,
                                'delete_flag'=>'0'
                            );
                            $db->createCommand()->insert('nb_site_no',$data);                            
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            echo json_encode(array('status'=>1,'message'=>'开台成功','siteid'=>$site_id));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'开台失败','siteid'=>$site_id)); 
                            return false;
                    }
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
                            $transaction->commit(); //提交事务会真正的执行数据库操作
                            //
                            $criteria = new CDbCriteria;
                            $criteria->condition =  't.dpid='.$companyId.' and t.site_id='.$sid.' and t.is_temp='.$istemp ;
                            $criteria->order = ' t.lid desc ';
                            $siteNo = SiteNo::model()->find($criteria);
                            SiteClass::deleteCode($siteNo->dpid,$siteNo->code);
                            //apc_delete($siteNo->dpid.$siteNo->code);
                            echo json_encode(array('status'=>1,'message'=>'撤台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'撤台失败'));
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
                            echo json_encode(array('status'=>1,'message'=>'换台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'换台失败'));
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
                            //更新目标site_no人数和状态
                            $modelsn=SiteNo::model()->find('dpid=:companyId and delete_flag=0 and site_id=:lid and is_temp=:istemp and status in ("1","2")' , array(':companyId' => $companyId,':lid'=>$sid,':istemp'=>$istemp)) ;
                            if($status > $modelsn->status)
                            {
                                $modelsn->status=$status;
                            }
                            $modelsn->number=$modelsn->number+$number;
                            $modelsn->save();
                            
                            //更新目标订单状态和人数
                            
                            //更新源订单状态
                            
                            //更新源订单明细，指向目标订单。
                            
                            //更新源site_no，让上网密码code 指向目标订单
                            $smodelsn->status='5';
                            $smodelsn->site_id=$modelsn->site_id;
                            $smodelsn->is_temp=$modelsn->is_temp;
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
                            echo json_encode(array('status'=>1,'message'=>'换台成功'));  
                            return true;
                    } catch (Exception $e) {
                            $transaction->rollback(); //如果操作失败, 数据回滚
                            echo json_encode(array('status'=>0,'message'=>'换台失败'));
                            return false;
                    }
		}                
	}
        
}