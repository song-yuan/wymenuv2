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
            $siteTypelid=Yii::app()->request->getParam('siteTypelid','0');
            $siteTypes = SiteType::model()->findAll('dpid=:companyId and delete_flag=0' , array(':companyId' => $companyId)) ;
            if($siteTypelid=='0')
            {
                $siteTypelid=empty($siteTypes)?0:$siteTypes[0]->lid;                
            }
            //$sitePersons= SiteClass::getSitePersons($companyId, $siteTypelid);
            $sitePersons= SiteClass::getSitePersonsAll($companyId);
            //var_dump($sitePersons);exit;
            $this->render('index',array(
                "companyId"=>$companyId,
                "siteTypes"=>$siteTypes,
                'siteTypelid'=>$siteTypelid,
                "sitePersons"=>$sitePersons
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
                    //var_dump($siteType);exit;
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
                        $queueno=$siteType->simplecode.substr("000".(string)($countsp+1),-3);
                        
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
                        $queueno=$siteType->simplecode."001";
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
                        'status'=>'0',
                        'slid'=>"0000000000",
                        'delete_flag'=>'0'
                    );
                    Yii::app()->db->createCommand()->insert('nb_queue_persons',$data);
                    $waitingno++;
                    //返回现有的等待人数
                    $precode="";
                    $printserver="0";//
                    $memo="排队号：".$queueno."，（还有".$waitingno."组在等待）";
                    $ret=Helper::printQueue($pad,$precode,$printserver,$memo);
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