<?php

class QueueController extends Controller
{
	public $layout = '/layouts/queue';
        
	/**
	 * 
	 * setting the companyId and padId
	 */
	public function actionIndex()
	{
            $companyId=Yii::app()->request->getParam('companyId','0');
            
            $sql = 'select distinct t.dpid as dpid,t.splid as splid,t.type_id as typeid,st.name as name,'
                    . 'sp.min_persons as min,sp.max_persons as max, tq.queuepersons as queuepersons, sf.sitenum as sitefree'
                    . '  from nb_site t'
                    . ' LEFT JOIN nb_site_type st on t.dpid=st.dpid and t.type_id=st.lid'
                    . ' LEFT JOIN nb_site_persons sp on t.dpid=sp.dpid and t.splid=sp.lid'
                    . ' LEFT JOIN (select distinct qp.dpid as dpid,qp.stlid as stlid,qp.splid as splid, count(qp.lid) as queuepersons'
                    . '  from nb_queue_persons qp where qp.delete_flag=0 and qp.status=0 '
                    . ' and qp.create_at >"'.date('Y-m-d',time()).' 00:00:00"' .' and qp.create_at<"'.date('Y-m-d',time()).' 23:59:59"'
                    . ' group by dpid,stlid,splid) tq'
                    . ' on t.dpid=tq.dpid and t.type_id=tq.stlid and t.splid=tq.splid'
                    . ' LEFT JOIN (select distinct subt.dpid as dpid,subt.splid as splid,subt.type_id as typeid,count(*) as sitenum '
                    . 'from nb_site subt where subt.status not in(1,2,3) and subt.delete_flag=0'
                    . ' group by dpid,splid,typeid) sf'
                    . ' on sf.dpid=t.dpid and sf.splid=t.splid and sf.typeid=t.type_id'
                    . ' where t.delete_flag=0 and t.dpid= '.$companyId
                    . ' group by dpid,splid,typeid,name,min,max'
                    . ' order by typeid,min';
            $connect = Yii::app()->db->createCommand($sql);
            $queueModels = $connect->queryAll();
//            var_dump($sitePersons);exit;
            $this->render('index',array(
                "companyId"=>$companyId,                
                'queueModels'=>$queueModels
            ));	
	}
        
        /**
	 * 
	 * setting the companyId and padId
	 */
	public function actionSetQueueStatus()
	{
            $companyId=Yii::app()->request->getParam('companyId','0');
            $status=Yii::app()->request->getParam('status','0');
            $lid=Yii::app()->request->getParam('lid','0');
            $sql = "update nb_queue_persons set status=".$status
                    ." where dpid=".$companyId." and lid=".$lid;
            $connect = Yii::app()->db->createCommand($sql);
            $connect->execute();
            Yii::app()->end(json_encode(array('status'=>true)));	
	}
        
        /**
	 * 
	 * setting the companyId and padId
	 */
	public function actionCall()
	{
            $companyId=Yii::app()->request->getParam('companyId','0');
            $sql = 'select distinct t.dpid as dpid,t.splid as splid,t.type_id as stlid,st.name as name,'
                    . 'sp.min_persons as min,sp.max_persons as max, tq.queuepersons as queuepersons, sf.sitenum as sitefree'
                    . '  from nb_site t'
                    . ' LEFT JOIN nb_site_type st on t.dpid=st.dpid and t.type_id=st.lid'
                    . ' LEFT JOIN nb_site_persons sp on t.dpid=sp.dpid and t.splid=sp.lid'
                    . ' LEFT JOIN (select distinct qp.dpid as dpid,qp.stlid as stlid,qp.splid as splid, count(qp.lid) as queuepersons'
                    . '  from nb_queue_persons qp where qp.delete_flag=0 and qp.status=0 '
                    . ' and qp.create_at >"'.date('Y-m-d',time()).' 00:00:00"' .' and qp.create_at<"'.date('Y-m-d',time()).' 23:59:59"'
                    . ' group by dpid,stlid,splid) tq'
                    . ' on t.dpid=tq.dpid and t.type_id=tq.stlid and t.splid=tq.splid'
                    . ' LEFT JOIN (select distinct subt.dpid as dpid,subt.splid as splid,subt.type_id as typeid,count(*) as sitenum '
                    . 'from nb_site subt where subt.status not in(1,2,3) and subt.delete_flag=0'
                    . ' group by dpid,splid,typeid) sf'
                    . ' on sf.dpid=t.dpid and sf.splid=t.splid and sf.typeid=t.type_id'
                    . ' where t.delete_flag=0 and t.dpid= '.$companyId
                    . ' group by dpid,splid,typeid,name,min,max'
                    . ' order by stlid,min';
            $connect = Yii::app()->db->createCommand($sql);
            $queueModels = $connect->queryAll();
            $this->render('call',array(
                "companyId"=>$companyId,
                'queueModels' => $queueModels
            ));	
	}
        
        public function actionNextPerson()
	{
		$companyId=Yii::app()->request->getParam('companyId');
                $splid = Yii::app()->request->getParam('splid','0');
                $stlid = Yii::app()->request->getParam('stlid','0');
                $callno = Yii::app()->request->getParam('callno','0');
                $queueno="000000";
                $queuelid="0000000000";
                $sitefree=0;
                $queueNum=0;
                //Yii::app()->end(json_encode(array("status"=>true,"callno"=>$callno)));
//                $criteria = new CDbCriteria;
//                $criteria->condition =  't.status=0 and t.dpid='.$companyId.' and t.stlid='.$stlid.' and t.splid='.$splid.' and queue_no="'.$callno.'"'
//                        .' and create_at <="'.date('Y-m-d',time()).' 23:59:59" and create_at >= "'.date('Y-m-d',time()).' 00:00:00"' ;
//                $criteria->order = ' t.lid ';
//                $queue = QueuePersons::model()->find($criteria);
//                if(!empty($queue))
//                {
//                    $queue->status=2;
//                    if($queue->save())
//                    {
                        $criteria2 = new CDbCriteria;
                        $criteria2->condition =  't.status=0 and t.dpid='.$companyId.' and t.stlid='.$stlid.' and t.splid='.$splid
                                .' and t.create_at <="'.date('Y-m-d',time()).' 23:59:59" and t.create_at >= "'.date('Y-m-d',time()).' 00:00:00"' ;
                        $criteria2->order = ' t.lid ';
                        $queue2 = QueuePersons::model()->find($criteria2);
                        if(!empty($queue2))
                        {
                            $queueno=$queue2->queue_no;
                            $queuelid=$queue2->lid;
                        }
//                    }
//                }
                $sqlfree='select count(*) as sitenum '
                            . 'from nb_site subt where subt.status not in(1,2,3) and subt.delete_flag=0'
                            . ' and subt.dpid='.$companyId.' and subt.splid='.$splid.' and subt.type_id='.$stlid;
                $connectfree = Yii::app()->db->createCommand($sqlfree);
                $sitefree = $connectfree->queryScalar();
                
                $sqlqueue='select count(qp.lid) as queuepersons'
                            . '  from nb_queue_persons qp where qp.delete_flag=0 and qp.status=0 '
                            . ' and qp.create_at >"'.date('Y-m-d',time()).' 00:00:00"' .' and qp.create_at<"'.date('Y-m-d',time()).' 23:59:59"'
                            . ' and qp.dpid='.$companyId.' and qp.splid='.$splid.' and qp.stlid='.$stlid;
                $connectqueue = Yii::app()->db->createCommand($sqlqueue);
                $queueNum = $connectqueue->queryScalar();
                
                Yii::app()->end(json_encode(array("status"=>true,"callno"=>$queueno,"queuelid"=>$queuelid,"sitefree"=>$sitefree,"queuenum"=>$queueNum)));
        }
        
        /**
	 * 
	 * setting the companyId and padId
	 */
	public function actionGetPassCall()
	{
            $companyId=Yii::app()->request->getParam('companyId','0');
            $sql = 'select qp.lid as lid,qp.dpid as dpid,qp.queue_no as queue_no'
                    . '  from nb_queue_persons qp where qp.delete_flag=0 and qp.status=3 '
                    . ' and qp.create_at >"'.date('Y-m-d',time()).' 00:00:00"' .' and qp.create_at<"'.date('Y-m-d',time()).' 23:59:59"'
                    . ' and qp.dpid='.$companyId
                    . ' order by qp.queue_no';
                    
            $connect = Yii::app()->db->createCommand($sql);
            $queueModels = $connect->queryAll();
            $this->renderpartial('callpass',array(
                "companyId"=>$companyId,
                'queueModels' => $queueModels
            ));	
	}
        
        /**
	 * 
	 * setting the companyId and padId
	 */
	public function actionGetSitePersonsAll()
	{
            $companyId=Yii::app()->request->getParam('companyid','0');
            $sitePersons= SiteClass::getSitePersonsAll($companyId);
            
            Yii::app()->end(json_encode($sitePersons));	
	}
        
        public function actionGetSitePersons(){
		$companyid = Yii::app()->request->getParam('companyid',0);
                $padid = Yii::app()->request->getParam('padid',0);
                $stlid = Yii::app()->request->getParam('stlid',0);
                $splid = Yii::app()->request->getParam('splid',0);
                $mobileno = Yii::app()->request->getParam('mobileno',0);
                $ret=array();
                $nowqueueno="000";
                $queueno="A001";
                $waitingno=0;
                if(empty($companyid)||empty($padid))
                {
                    Yii::app()->end(json_encode(array('status'=>false,'msg'=>'店铺或设备不存在！')));
                }
                $pad=Pad::model()->with("printer")->find(' t.dpid=:companyId and t.lid=:padid', array(':companyId'=>$companyid,':padid'=>$padid));
                //var_dump($pad);exit;
                if(!empty($pad))
                {
                    //生成新的排队号
                    $siteType=  SiteType::model()->find(" dpid=:dpid and lid=:lid",array(":dpid"=>$companyid,":lid"=>$stlid));
                    $sitePersons= SitePersons::model()->find(" dpid=:dpid and lid=:lid",array(":dpid"=>$companyid,":lid"=>$splid));
                    $criteria = new CDbCriteria;
                    $criteria->condition =  " dpid=".$companyid." and stlid=".$stlid." and splid=".$splid
                            ." and create_at >='".date('Y-m-d',time())." 00:00:00' and create_at <='"
                            .date('Y-m-d',time())." 23:59:59'" ;
                    $criteria->order = ' lid ';		
                    $queuePerson= QueuePersons::model()->findAll($criteria);
                    if(empty($siteType))
                    {
                        Yii::app()->end(json_encode(array('status'=>false,'msg'=>'座位类型不存在！')));
                    }
                    //var_dump($queuePerson);exit;
                    if(!empty($queuePerson))
                    {
                        $countsp=count($queuePerson);
                        $queueno=$siteType->simplecode.$sitePersons->min_persons.substr("000".(string)($countsp+1),-3);
                        
                        for($sti=$countsp-1;$sti>=0;$sti--)
                        {
                            if($queuePerson[$sti]->status=="0")
                            {
                                $waitingno++;
                            }else{
                                break;
                            }
                        }
                    }else{
                        $queueno=$siteType->simplecode.$sitePersons->min_persons."001";
                        $waitingno=0;
                    }
                    $se=new Sequence("queue_persons");
                    $queuelid = $se->nextval();
                    $data = array(
                        'lid'=>$queuelid,
                        'dpid'=>$companyid,
                        'create_at'=>date('Y-m-d H:i:s',time()),
                        'update_at'=>date('Y-m-d H:i:s',time()),
                        'stlid'=>$stlid,
                        'splid'=>$splid,
                        'queue_no'=>$queueno,
                        'mobile_no'=>$mobileno,
                        'status'=>'0',
                        'slid'=>"0000000000",
                        'delete_flag'=>'0'
                    );
                    Yii::app()->db->createCommand()->insert('nb_queue_persons',$data);
                    $waitingno++;
                    //返回现有的等待人数
                    $precode="";
                    $printserver="0";//
                    //$memo="排队号：".$queueno."，（还有".$waitingno."组在等待）";                    
                    $ret=Helper::printQueue($pad,$precode,$printserver,$queueno,$waitingno,$mobileno,$siteType->name,$sitePersons->min_persons,$sitePersons->max_persons);
                    if($ret['status'])
                    {
                        $ret['waitingnum']=$waitingno;
                    }
                }else{
                    $ret = array('status'=>false,'msg'=>'没有找到PAD');
                }
                Yii::app()->end(json_encode($ret));  
	}
}