<?php
class DataSync
{
    
    /**
     * 获取is_sync需要同步的初始状态值
     */
    public static function getInitSync()
    {
        return substr("11111111111111111111111111111111111111111111111111111", 
                0, Yii::app()->params['sync_maxlocal']);        
    }


    /**
     * 严格按照is_sync来同步
     * @param type $synctalbe 云端同步到本地，本地同步到云端
     * @param type $syncSpecialTalbe 特殊表特殊字段，如果云端有，本地也有，这几个字段以本地为准
     * @param type $isnow 是否立刻同步，还是随机延迟几十秒再同步，防止高并发
     */
    public static function FlagSync($dpid,$synctalbe,$syncSpecialTalbe,$isnow=true){
        //云端则不需要同步
        if(Yii::app()->params['cloud_local']=='c')
        {
            return;
        }
        //是否立刻同步
        if(!$isnow)
        {
            $randtime=rand(1,60);
            echo $randtime;
            sleep($randtime);
        }
        //db
        $dbcloud;
        $dblocal;
        try
        {
            $dbcloud=Yii::app()->dbcloud;
            $dblocal=Yii::app()->dblocal;            
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return;
        }
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
            $localnum=Yii::app()->params['sync_localnum'];
            //将这个时间点开始的云端数据取出
            $sql1 = "select * from ".$t." where lid%2=0 and dpid=".$dpid." and substring(is_sync,".
                    $localnum.",1) = '1'";
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
                    $transactionspecial = $dbcloud->beginTransaction();
                    try {
                        $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                        //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                        foreach($localspecialdataarr as $lsd)
                        {
                            $dbcloud->createCommand()->insert($t,$lsd);
                        }
                        //dblocal更新is_sync标志
                        $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                                $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                        
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
                    //dbcloud更新is_sync标志
                    $dbcloud->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                            $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist1)->execute();
                                       
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
            $sql3 = "select * from ".$t." where lid%2=1 and dpid=".$dpid." and substring(is_sync,".
                    Yii::app()->params['sync_localnum'].",1) = '1'";
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
                    //dbcloud更新is_sync标志
                    $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                            $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist3)->execute();
                    
                    $transactioncloud->commit();
                } catch (Exception $ex) {
                    $transactioncloud->rollback();
                    $isalllocalsuccess=0;
                    //break;
                    //continue;
                }
            }
        }       
    }
    
    /**
     * 按照某个时间来强制同步
     * @param type $specialTime "yyyy-mm-dd hh:mm:ss"
     * @param type $synctalbe
     * @param type $syncSpecialTalbe
     * @param type $isnow 是否立刻同步，还是随机延迟几十秒再同步，防止高并发
     */
    public static function timeSync($dpid,$specialTime,$synctalbe,$syncSpecialTalbe,$isnow=true)
    {
        //云端则不需要同步
        if(Yii::app()->params['cloud_local']=='c')
        {
            return;
        }
        //是否立刻同步
        if(!$isnow)
        {
            $randtime=rand(1,60);
            echo $randtime;
            sleep($randtime);
        }
        if(empty($specialTime))
        {
            $specialTime="2015-08-15 19:00:00";
        }
        $ret = strtotime($dateTime);
        if(!$ret || $ret == -1)
        {
            return;
        }
        $cloudtime= $specialTime;
        $localtime= $specialTime;
        $isalllocalsuccess=1;
        $isallcloudsuccess=1;
        foreach ($synctalbe as $t)
        {

            $deletelist1="";
            $clouddataarr=array();//云端lid和记录对应数组
            $deletelist2="";
            $localspecialdataarr=array();//special的lid和记录对应数组，云端的数据在本地更新过了，要反向更新云端，不一定和上述重合
            $deletelist3="";
            $localdataarr=array();//本地lid和记录对应数组
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
                    $transactionspecial = $dbcloud->beginTransaction();
                    try {
                        $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                        //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                        foreach($localspecialdataarr as $lsd)
                        {
                            $dbcloud->createCommand()->insert($t,$lsd);
                        }
                        //dbcloud更新is_sync标志
                        $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                            $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                    
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
                    $dbcloud->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                            $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist1)->execute();
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
                    $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,x".
                            $localnum."1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist3)->execute();
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
    }
    
    /**
     * 下载最新的图片文件
     * @param type $company_id
     */
    public static function clientDownImg($company_id){
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
            GrabImage(Yii::app()->params->masterdomain,"uploads/company_".$company_id."/".$avalue);
        }
    }
    
    public static function  GrabImage($baseurl,$filename="") { 
        if($baseurl=="") return false; 
        if(file_exists($filename))
        {
            return "";
        }
        $basedir="";
        if($filename=="") { 
            $ext=strrchr($url,"."); 
            if($ext!=".gif" && $ext!=".jpg" && $ext!=".png") return false; 
            $filename=date("YmdHis").$ext; 
        } else{
            $basedir=substr($filename, 0,strrpos($filename,"/"));
        }

        try{
            if (!is_dir($basedir)){  		
                //第三个参数是“true”表示能创建多级目录，iconv防止中文目录乱码
                $res=mkdir(iconv("UTF-8", "GBK", $basedir),0777,true); 
                if (!$res){
                    return "";    
                }
            }
            ob_start(); 
            readfile($baseurl.$filename); 
            $img = ob_get_contents(); 
            ob_end_clean(); 
            $size = strlen($img); 

            $fp2=@fopen($filename, "a"); 
            fwrite($fp2,$img); 
            fclose($fp2); 
        }catch (Exception $e) {
            return "";
        }
        return $filename; 
    }
    
    /**
     * 有些更新必须先同步到云端，如产品的库存数量，必须各个客户端都更新云端一个地方，然后同步下来
     * 采用的策略是用sql语句更新云端，然后从云端同步到本地，
     * 所以更新时限更新云端，然后更新本地，如果云端失败，保存sql，同步前先调用这个更新sql，再同步云端数据
     * 具体功能是：
     * 先操作云端，如果成功则返回，如果失败则存储到nb_sqlcmd_sync
     * @param type $sql
     */
    public static function cloudFirt($dpid,$sql)
    {
        try
        {
            $dbcloud=Yii::app()->dbcloud;
            $dbcloud->createCommand($sql)->execute();
            return true;
        }  catch (Exception $e)
        {
            $dblocal=Yii::app()->dblocal;
            $se=new Sequence("sqlcmd_sync");
            $lid = $se->nextval();
            $data = array(
                'lid'=>$lid,
                'dpid'=>$dpid,
                'create_at'=>date('Y-m-d H:i:s',time()),
                'sqlcmd'=>$sql,                
                'is_sync'=>'10000' //10000表示需要先在服务器端更新！
            );
            $dblocal->createCommand()->insert('nb_sqlcmd_sync',$data);
            return false;
        }
    }
    
    /**
     * 同步云端的数据之前，将本地需要先更新云端的数据查询出来，
     * 然后更新云端，再同步，如果失败直接返回false
     * 操作：
     * 查出所有的sqlcmd_sync，然后执行
     * @return boolean
     */
    public static function cloudFirstSync($dpid)
    {
        $dbcloud=Yii::app()->dbcloud;
        $dblocal=Yii::app()->dblocal;
        $cloudexec=$dblocal->createCommand("select lid,sqlcmd from nb_sqlcmd_sync where is_sync='10000' and dpid=".$dpid)->queryAll();
        if(empty($cloudexec))
        {
            return true;
        } 
        $dellist="(";
        $transactioncloud = $dbcloud->beginTransaction();
        try {
            foreach ($cloudexec as $row)
            {
                $dbcloud->createCommand($row['sqlcmd'])->execute();
                $dellist=$dellist.$row['lid'].",";
            }
            $dellist=$dellist."0000000000".")";
            $dblocal->createCommand("delete from nb_sqlcmd_sync where dpid=".$dpid." and lid in ".$dellist)->execute();            
            $transactioncloud->commit();
        } catch (Exception $ex) {
            echo $ex->getMessage();
            $transactioncloud->rollback();            
        }
        return true;
    }
}