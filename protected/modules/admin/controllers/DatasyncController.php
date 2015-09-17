<?php
class DatasyncController extends Controller
{	
        //图片上传只让他们从云端上传
        //本地下载图片
	public function actionIndex(){
//            $se=new Sequence("sqlcmd_sync");
//            var_dump($se->nextval());exit;
                        
//            $filesnames1 = scandir("uploads/company_0000000001");
//            $fnj=  json_encode($filesnames1);
//            var_dump($fnj);
//            $fna=  json_decode($fnj);
//            var_dump($fna);
//            //exit;
//            $filesnames2 = scandir("uploads/company_0000000011");
//            $filesnames3=array_diff($fna, $filesnames2);
//            var_dump($filesnames3);exit;
//            
//            //图片同步，主要是uploads/company_nnnnn的文件夹下，其他的不用，产品详细的文本框将来删除           
//            $imgfile="uploads/company_0000000007/DBAED346-6C35-46CF-8632-DDBE81C2352E.jpg";
//            
//            $img=Helper::GrabImage(Yii::app()->params->masterdomain,$imgfile); 
//            if($img){ 
//                echo '<img src="'.$img.'">'; 
//            }else{ 
//                echo "false"; 
//            } 
	}
        
        public function actionServerImglist(){
            $company_id = Yii::app()->request->getParam('companyId',0);
            $filesnames1 = scandir("uploads/company_".$company_id);
            $fnj=  json_encode($filesnames1);
            Yii::app()->end($fnj);
        }
        
        private function clientDownImg($company_id){
            //$company_id = Yii::app()->request->getParam('companyId',0);
            ob_start(); 
            readfile(Yii::app()->params['masterdomain'].'admin/datasync/serverImglist/companyId/'.$company_id); 
            $serverimgs = ob_get_contents(); 
            ob_end_clean(); 
            $fna=  json_decode($serverimgs);
            //var_dump($fna);exit;
            $filesnames2 = scandir("uploads/company_".$company_id);
            //var_dump($filesnames2);exit;                        
            $filesnames3=array_diff($fna, $filesnames2);
            foreach($filesnames3 as $akey=>$avalue)
            {
                Helper::GrabImage(Yii::app()->params->masterdomain,"uploads/company_".$company_id."/".$avalue);
            }
        }
        
        /*
         * 服务器端5分钟执行一次,执行5000条
         * 100个店铺和其通讯时怎么办？还是5000条？
         */
        public function serverExecClientSql(){
            //$dbcloud=Yii::app()->dbcloud;
            $dblocal="";
            try {
                $dblocal=Yii::app()->dblocal;
            } catch (Exception $ex) {
                echo 'empty';exit;
            }
            
            //exec client sql
            $db = Yii::app()->dbcloud;
            //$sqlcmds=$db->createCommand("select * from nb_sqlcmd_sync where lid%2=0 order by lid"." LIMIT 0,5000");
            $sqlcmds=$db->createCommand("select * from nb_sqlcmd_sync where lid%2=1 order by lid limit 0,5000")->queryAll();
            //var_dump($sqlcmds);exit;
            //开始事务
            foreach ($sqlcmds as $sql)
            {
                //var_dump($sql);exit;
                $transaction = $db->beginTransaction();
                try{
                    $db->createCommand($sql['sqlcmd'])->execute();
                    $delsql="delete from nb_sqlcmd_sync where lid=".$sql['lid']." and dpid=".$sql['dpid'];
                    //var_dump($delsql);exit;
                    $db->createCommand($delsql)->execute();
                    $transaction->commit();
                } catch (Exception $ex) {
                    $transaction->rollback();
                    continue;
                }
            }           
           
        }
        
//         /*
//         * 服务器端根据client的请求，发送500条
//         */
//        public function actionServerSendClientSql(){
//            //send server sql 
//            
//        }
        
        /*
         * 客户端5分钟执行一次，
         * 从服务器获取（DB），
         * 自己存入服务器（DB），
         * 自己执行
         * 从服务器端下载图片
         */
        public function execClientSql($companyId){
            $dbcloud=Yii::app()->dbcloud;
            $dblocal=Yii::app()->dblocal;
            //get sql from server lid%2==0
            $serversql=$dbcloud->createCommand("select * from nb_sqlcmd_sync where lid%2=0 and dpid=".$companyId." order by lid limit 0,5000")->queryAll();
            $dblocal->createCommand()->insert('nb_sqlcmd_sync',$serversql);
            //send sql to server lid%2==1
            $serversql=$dblocal->createCommand("select * from nb_sqlcmd_sync where lid%2=1 and dpid=".$companyId." order by lid limit 0,5000")->queryAll();
            $serversql->createCommand()->insert('nb_sqlcmd_sync',$serversql);           
            //exec server sql
            $sqlcmds=$dblocal->createCommand("select * from nb_sqlcmd_sync where lid%2=0 order by lid limit 0,5000")->queryAll();
            //var_dump($sqlcmds);exit;
            //开始事务
            foreach ($sqlcmds as $sql)
            {
                //var_dump($sql);exit;
                $transaction = $dblocal->beginTransaction();
                try{
                    $dblocal->createCommand($sql['sqlcmd'])->execute();
                    $delsql="delete from nb_sqlcmd_sync where lid=".$sql['lid']." and dpid=".$sql['dpid'];
                    //var_dump($delsql);exit;
                    $dblocal->createCommand($delsql)->execute();
                    $transaction->commit();
                } catch (Exception $ex) {
                    $transaction->rollback();
                    continue;
                }
            } 
            //downimagefile
            $this->clientDownImg();
        }
	
        /*
         * 利用sql语句的同步不合理，太麻烦
         */
        public function actionSqlcmdSync(){
            if(Yii::app()->params['cloud_local']=='c')
            {
                $this->serverExecClientSql();
            }else{
                $this->execClientSql();
            }
        }
        
        /*
         * 利用数据记录和比较的方法同步，因为lid不重复，所以可以
         * 查询出最后的更新时间时上一次成功开始的数据，然后更新。
         */
        public function actionInsertSync(){
            //一份钟的随机时间，防止高并发
            //$now = new DateTime(date('Y-m-d H:i:s',time()));
            //echo $now->format('Y-m-d H-i-s');
            //$now->modify("-1 minute");
            //echo $now->format('Y-m-d H-i-s');
            //exit;
            $randtime=rand(1,60);
            echo $randtime;
            sleep($randtime);
            //启用本地模式时，才存在同步，云端不存在同步
            //等于L是，本地系统知道连接到本地系统，云端根据该company的is2_cloud
            //判断是否使用本地服务器，如果是云端开台，点单等功能不能操作，否则冲突。
            //云端更新云端数据，本地更新本地数据，数据更新时要检查，不能随便更新。
            //图片下载，图片上传（phpcurl 和 move_upload_file上传，暂时不做）
            $dpid = Yii::app()->request->getParam('companyId',0);
            $typeId = Yii::app()->request->getParam('typeId',0);
            $date = Yii::app()->request->getParam('date','2015-08-15 19:00:00');
            if(Yii::app()->params['cloud_local']=='l')
            {
                $dbcloud;
                $dblocal;
                try
                {
                    //echo "1";exit;
                    $dbcloud=Yii::app()->dbcloud;
                    //echo "0";exit;
                    $dblocal=Yii::app()->dblocal;
                    //echo "2";exit;
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                    return;
                }
                //$dbcloud=Yii::app()->dbcloud;
                echo "dbcloud <br>";
                                
                //云端和本地插入dataSync同步记录，但不设成功标志
                $se=new Sequence("data_sync");
                $lid = $se->nextval();
                $data = array(
                    'lid'=>$lid,
                    'dpid'=>$dpid,
                    'cmd_code'=>'2cloud',
                    'create_at'=>date('Y-m-d H:i:s',time()),
                    'update_at'=>date('Y-m-d H:i:s',time()),
                    'cmd_data'=>'',
                    'sync_result'=>'0',//0未执行，1成功，2失败
                    'is_interface'=>'0'//0云端
                ); 
                //var_dump($data);exit;
                $dbcloud->createCommand()->insert('nb_data_sync',$data);
                //var_dump($data);exit;
                $dblocal->createCommand()->insert('nb_data_sync',$data); 
                echo "insert nb_data_sync <br>";
                //获取最后一次成功的dataSync的时间
                $sql = "select create_at from nb_data_sync where dpid=".$dpid." and sync_result=1 order by create_at desc limit 1";
                $cloudtime= $dbcloud->createCommand($sql)->queryScalar();
                $localtime= $dblocal->createCommand($sql)->queryScalar();
                //$sqlsuccess = "update nb_data_sync set sync_result='1' where dpid=".$dpid." and lid=".$lid;
                if(empty($cloudtime))
                {
                    $cloudtime="2015-08-15 19:00:00";
                }else{
                    //echo $cloudtime;
                    $tempnow = new DateTime($cloudtime);
                    //$tempnow->modify("-120 minute");
                    $tempnow->modify("-2 hour");
                    $cloudtime=$tempnow->format('Y-m-d H:i:s');
                    //echo $cloudtime;       exit;             
                }
                if(empty($localtime))
                {
                    $localtime="2015-08-15 19:00:00";
                }else{
                    $tempnow = new DateTime($localtime);
                    //$tempnow->modify("-120 minute");hour
                    $tempnow->modify("-2 hour");
                    $localtime=$tempnow->format('Y-m-d H:i:s');
                }
                echo "typeId:".$typeId."<br>";
                if($typeId=="1")
                {
                    $cloudtime="2015-08-15 19:00:00";
                    $localtime="2015-08-15 19:00:00";
                }
                if($date!="2015-08-15 19:00:00")
                {
                    $cloudtime="$date";
                    $localtime="$date";
                }
                echo "get cloud tiem:".$cloudtime." and local time:".$localtime." <br>";
                //exit;
                //var_dump($cloudtime,$localtime);exit;
                //轮询以下表(云端和本地都会操作的，)
                //云端生成，本地操作的表， 如：site，member_card等被本地平凡修改的的有吗？
                $synctalbe=array(
                    "nb_b_login",
                    "nb_close_account",
                    "nb_close_account_detail",
                    //"nb_company_wifi",
                    "nb_feedback",
                    "nb_floor",
                    //"nb_guest_message",
                    //"nb_local_company",
                    "nb_member_card", // ！！！云端添加，充值时，等改变
                    "nb_member_consumer", 
                    "nb_member_recharge", 
                    "nb_online_pay",
                    "nb_order",
                    //"nb_order_feedback",
                    "nb_order_pay",
                    "nb_order_product",
                    "nb_order_retreat",
                    "nb_order_taste",
                    "nb_pad",
                    "nb_pad_printerlist",
                    "nb_payment_method",
                    "nb_printer",
                    "nb_printer_way",
                    "nb_printer_way_detail",
                    "nb_product",
                    "nb_product_addition",
                    "nb_product_category",
                    "nb_product_discount",
                    "nb_product_out",
                    "nb_product_picture",
                    "nb_product_printerway",
                    "nb_product_set",
                    "nb_product_set_detail",
                    "nb_product_special",
                    "nb_product_taste",
                    "nb_product_tempprice",
                    "nb_retreat",
                    //"nb_squence",
                    "nb_site",// ！！！云端添加，充值时，等改变
                    "nb_site_no",
                    "nb_site_type",
                    "nb_taste",
                    "nb_taste_group",
                    "nb_user",
                    "nb_user_company"
                ); 
                ////特殊的更新
                $syncSpecialTalbe=array(
                    "nb_site"=>array("status","number"),  //本地状态同步过去
                    "nb_member_card"=>array("all_money"), //本地金额同步过去
                    "nb_product"=>array("store_number","order_number","favourite_number") //本地库存产品下单数量，人气同步过去
                );
                $isalllocalsuccess=1;
                $isallcloudsuccess=1;
                foreach ($synctalbe as $t)
                {
                    
                    $deletelist1="";
                    $clouddataarr=array();
                    $deletelist2="";
                    $localspecialdataarr=array();
                    $deletelist3="";
                    $localdataarr=array();
                    //将这个时间点开始的云端数据取出
                    $sql1 = "select * from ".$t." where lid%2=0 and dpid=".$dpid." and update_at >= '".$cloudtime."'";
                    //var_dump($sql1);exit;
                    $clouddata=$dbcloud->createCommand($sql1)->queryAll();  
                    echo "cloud -> local:".$t.":".count($clouddata)."<br>";
                    if(!empty($clouddata))
                    {
                        $deletelist1="(";
                        foreach ($clouddata as $cdata)
                        {
                            $deletelist1=$deletelist1.$cdata['lid'].",";
                            $clouddataarr[$cdata['lid']]=$cdata;
                        }
                        $deletelist1=$deletelist1."0000000000".")";
                    }
                    //特殊的更新内容，先将云端的取出来，然后取出本地的双号，更新云端的数据，及取出的数组。
                    //用这个数据更新本地的
                    //本地的正常更新
                    
                    if(!empty($syncSpecialTalbe[$t]))
                    {
                        $specialfield=$syncSpecialTalbe[$t];
                        $localspecialdata=$dblocal->createCommand($sql1)->queryAll();
                         echo "cloud -> local(special):".$t.":".count($localspecialdata)."<br>";
                        if(!empty($localspecialdata))
                        {
                            $deletelist2="(";
                            foreach ($localspecialdata as $sdata)
                            {
                                $deletelist2=$deletelist2.$sdata['lid'].",";
                                $localspecialdataarr[$sdata['lid']]=$sdata;
                            }
                            $deletelist2=$deletelist2."0000000000".")";
                            //更新$localspecialdata
                            //更新$clouddata
                            foreach($localspecialdataarr as $sadata)
                            {
                                if(!empty($clouddataarr[$sadata['lid']]))
                                {
                                    foreach ($specialfield as $sfield)
                                    {
                                        $clouddataarr[$sadata['lid']][$sfield]=$sadata[$sfield];
                                    }
                                    $localspecialdataarr[$sadata['lid']]=$clouddataarr[$sadata['lid']];
                                }
                            }                          
                            //云端删除$localspecialdata
                            //云端插入$localspecialdata
                            
                            //$dbcloud->createCommand($sqlsuccess)->execute();
                            $transactionspecial = $dblocal->beginTransaction();
                            try {
                                $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                                //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                                foreach($localspecialdataarr as $lsd)
                                {
                                    $dbcloud->createCommand()->insert($t,$lsd);
                                }                                
                                $transactionspecial->commit();
                            } catch (Exception $ex) {
                                echo $ex->getMessage();
                                $transactionspecial->rollback();
                                $isallcloudsuccess=0;
                                //break;
                                //continue;
                                //exit;
                            }
                        }
                    }
                    //$sql2 = "select * from ".$t." where lid%2=0 and dpid=".$dpid." and create_at >= '".$cloudtime."'";
                    //$cloudlocal=$dblocal->createCommand($sql2)->queryAll();
                    //对比数据，删除并插入和对方不一样的数据，要设置事务，
                    //$cloudupdate=  array_diff($clouddata, $cloudlocal);
                    //$dblocal->begainTransaction();
                    //var_dump($clouddata,$cloudlocal);exit;
                    
                    if(!empty($clouddataarr))
                    {
                        $transactionlocal = $dblocal->beginTransaction();
                        try {
                            $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist1)->execute();
                            //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                            foreach($clouddataarr as $cd)
                            {
                                $dblocal->createCommand()->insert($t,$cd);
                            }
                            //$dbcloud->createCommand($sqlsuccess)->execute();
                            $transactionlocal->commit();
                        } catch (Exception $ex) {
                            echo $ex->getMessage();
                            $transactionlocal->rollback();
                            $isallcloudsuccess=0;
                            //break;
                            //continue;
                            //exit;
                        }
                    }
                    //将这个时间点开始的本地数据取出///云端不可能修改本地，只有本地修改云端。
                    $sql3 = "select * from ".$t." where lid%2=1 and dpid=".$dpid." and update_at >= '".$localtime."'";
                    $localdata=$dblocal->createCommand($sql3)->queryAll();
                    echo "local->cloud".$t.":".count($localdata)."<br>";
                    //var_dump($localdata);
                    //$sql4 = "select * from ".$t." where lid%2=1 and dpid=".$dpid." and create_at >= ".$localtime;
                    //$localcoud=$dbcloud->createCommand($sql4)->queryAll();
                    //对比数据，删除并插入和对方不一样的数据，要设置事务，
                    //$localupdate=  array_diff($localdata, $localcoud);
                    //$dblocal->begainTransaction();
                    if(!empty($localdata))
                    {
                        $transactioncloud = $dbcloud->beginTransaction();
                        try {
                            $deletelist3="(";
                            foreach ($localdata as $ldata)
                            {
                                $deletelist3=$deletelist3.$ldata['lid'].",";
                                $localdataarr[$ldata['lid']]=$ldata;
                            }
                            $deletelist3=$deletelist3."0000000000".")";
                            //var_dump($deletelist2);exit;
                            //$deletelist2=  "(".implode(",",array_column($localdata, 'lid')).")";
                            //var_dump("delete from ".$t." where dpid=".$dpid." and lid in".$deletelist2);exit;
                            $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in".$deletelist3)->execute();
                            foreach($localdata as $ld)
                            {
                                $dbcloud->createCommand()->insert($t,$ld);
                            }
                            //$dblocal->createCommand($sqlsuccess)->execute();
                            $transactioncloud->commit();
                        } catch (Exception $ex) {
                            $transactioncloud->rollback();
                            $isalllocalsuccess=0;
                            //break;
                            //continue;
                        }
                    }
                }
                
               //删除同步时间之前的所有的打印记录//删除1天谴的消息记录
               $sqldeleteprintjobs = "delete from nb_order_printjobs where dpid=".$dpid." and create_at <= '".$localtime."'";  
               $dblocal->createCommand($sqldeleteprintjobs)->execute(); 
               $sqldeleteorderfeed = "delete from nb_order_feedback where dpid=".$dpid." and create_at <= '".$localtime."'";  
               $dblocal->createCommand($sqldeleteorderfeed)->execute(); 
                //更新dataSync同步记录为成功状态，
                echo "all success;"."<br>";
                $sqlsuccess = "update nb_data_sync set sync_result='1' where dpid=".$dpid." and lid=".$lid;                    
                if($isalllocalsuccess==1)
                {
                    $localtime= $dblocal->createCommand($sqlsuccess)->execute();
                }
                if($isallcloudsuccess==1)
                {
                    $cloudtime= $dbcloud->createCommand($sqlsuccess)->execute();                    
                }
                //downimagefile
                echo "download image"."<br>";
                $this->clientDownImg($dpid);
            }
        }
}