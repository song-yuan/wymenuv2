<?php
class DataSync
{
    public static $synctalbe=array(
        //'nb_b_login',
        'nb_brand_user',
        'nb_brand_user_level',
        //'nb_c_login',
        //'nb_cart',
        'nb_cashback_record',
        'nb_close_account',
        'nb_close_account_detail',
        //('nb_company'),
        //('nb_company_wifi'),
        'nb_consumer_cash_proportion',
        'nb_consumer_points_proportion',
        'nb_cupon',
        'nb_cupon_branduser',
        //'nb_data_sync'),
        'nb_feedback',
        'nb_floor',
        //('nb_guest_message'),
        //('nb_local_company'),
        'nb_member_card',
        //'nb_member_consumer',
        'nb_member_recharge',
        'nb_menu',
        'nb_normal_branduser',
        'nb_normal_promotion',
        'nb_normal_promotion_detail',
        'nb_notify',
        'nb_online_pay',
        'nb_order',
        //'nb_order_feedback',
        'nb_order_pay',
        //('nb_order_printjobs'),
        'nb_order_product',
        'nb_order_retreat',
        'nb_order_taste',
        'nb_pad',
        'nb_pad_printerlist',
        'nb_payment_method',
        'nb_point_record',
        'nb_points_valid',
        'nb_printer',
        'nb_printer_way',
        'nb_printer_way_detail',
        'nb_private_branduser',
        'nb_private_promotion',
        'nb_private_promotion_detail',
        'nb_product',
        'nb_product_addition',
        'nb_product_category',
//        //'nb_product_discount',
//        //'nb_product_out',
        'nb_product_picture',
        'nb_product_printerway',
        'nb_product_set',
        'nb_product_set_detail',
        //'nb_product_special',
        'nb_product_taste',
        //'nb_product_tempprice',
        'nb_promotion_activity',
        'nb_promotion_activity_detail',
        //'nb_promotion_total',
        'nb_queue_persons',
        'nb_recharge_record',
        'nb_redpacket',
        'nb_redpacket_detail',
        'nb_redpacket_send_strategy',
        'nb_retreat',
        'nb_scene',
        'nb_scene_scan_log',
        //'nb_sequence',
        'nb_shift_detail',
        'nb_site',
        'nb_site_no',
        'nb_site_persons',//座位人数分类
        'nb_site_type',
        //'nb_sqlcmd_sync',
        'nb_taste',
        'nb_taste_group',
        'nb_total_promotion',//优惠整体效果
        'nb_user',
        'nb_user_company',
        'nb_weixin_recharge',
        'nb_weixin_service_account',                
    ); 
    
    ////特殊的更新,云端的数据，但是在本地更新了，以下内容要传递到云端
    public static $syncSpecialTalbe=array(
        "nb_member_card"=>array("all_money"), //本地金额同步过去
        "nb_product"=>array("status"), //本地库存产品下单数量，人气同步过去
        "nb_queue_persons"=>array("update_at","status"), //排队的状态，云端微信的排队，本地修改后，状态和更新日期都上传
        "nb_order_product"=>array("is_retreat","price","is_giving","is_print","delete_flag","product_order_status")
    );
    
    //nb_order_taste nb_product_printerway每次修改都是删除就得插入新的，所以同步时也应该删除所有旧的，插入新的。
    public static $syncCloudDel=array(
        //'nb_order_taste',
        'nb_product_printerway',
        'nb_product_taste',
        'nb_product_picture'
    );
    
    //nb_product 的库存数量、历史数量等属于增量数据，需要执行累加sql,同步时这些数据不同步；
    //remain_money暂时不去管他，因为remain_money也必须在云端操作，即时普通转成微信的也要云端操作
    //order_number,favourite_number从订单表中去，应该。库存将来要有库存表
    //这些数据，必须云端一致，即无论啥时候更新，从云端传递到本地。
    public static $syncDataSql=array(
        "nb_product"=>array("store_number","order_number","favourite_number") //本地库存产品下单数量，人气同步过去
        //"nb_brand_user"=>array('remain_money')
    );
    
    //status;;;;
    ///site site_no order的status和number有点特殊,一个座位云端开台了，本地也开台怎么办
    //以最新大的状态数据为准 ，如果二个值相等，以本地为准   
    //nb_order的 should_total reality_total的，如果是云端的这二个数据可能本地产生，也可能云端产生
    //云端的订单一定在云端产生，但是可能产生退菜或某个菜折扣，所以reality_total的就变化
    //如果状态是2则是，这二个数据还没有产生，或可以修改。
    //状态3、4为准，谁大这二个数据就跟谁。
    //////////////////////////////////////////////////////////
    //比如订单的口味，当只有本地或云端一个地方修改时，都有效。
    //但是本地和云端同时修改时，且状态相等，那个的单子那个有效
    //本地和云端同时修改，但是有个状态大，则谁大谁有效
    ////////////////////////////////////////////////////////////
    public static $syncStatusCompare=array(
        "nb_site"=>array("status"=>"123"),  //云端只能产生1、3状态
        "nb_site_no"=>array("status"=>"123"),  //云端只能产生1、3状态
        "nb_order"=>array("order_status"=>"123") //云端只能产生1、3状态
    );

    
    
    /**
     * 获取is_sync需要同步的初始状态值
     */
    public static function getInitSync()
    {
        return substr("11111111111111111111111111111111111111111111111111111", 
                0, Yii::app()->params['sync_maxlocal']);        
    }
    
    /**
     * 获取is_sync需要同步后的状态值
     */
    public static function getAfterSync()
    {
        $allflag=substr("11111111111111111111111111111111111111111111111111111", 
                0, Yii::app()->params['sync_maxlocal']);  
        return substr_replace($allflag, "0", Yii::app()->params['sync_localnum']-1,1);
    }

    public static function execSync($dpid,$synctalbev,$sql_1,$sql_2,$isnow)
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
            //osy//echo $randtime;
            sleep($randtime);
        }
        //db
        $dbcloud;
        $dblocal;
        //echo 'checkdb';
        try
        {
            $dbcloud=Yii::app()->dbcloud;
            $dblocal=Yii::app()->dblocal;            
        } catch (Exception $ex) {
            //osy//echo $ex->getMessage();
            return false;//数据库连接异常会直接返回
        }
        //先同步云端必须要更新的sql
        //echo 'cloudfirstsync';exit;
        //var_dump(DataSync::cloudFirstSync($dpid));exit;
        if(!DataSync::cloudFirstSync($dpid))
        {
            return false;
        }
//        $synctalbeinstance=DataSync::$synctalbe;
//        if(!empty($synctalbev))
//        {
//            $synctalbeinstance=$synctalbev;
//        }
        //osy//echo 'beginsync<br>';
        //var_dump($synctalbev);exit;
        foreach ($synctalbev as $t)
        {
            //osy//echo $t."<br>";
            $deletelist1="";
            $deletelist2="";
            $clouddataarr=array();
            $cloudlocaldataarr=array();
            $deletelist3="";
            $deletelist4="";
            $localdataarr=array();
            $localclouddataarr=array();
            $localnum=Yii::app()->params['sync_localnum'];
            
             //////////*******开始操作syncCloudDel内表*************///////////
            $sql1=str_replace("#tablename#",$t,$sql_1);
            //osy//echo "sql1:".$sql1."<br>";
            if(in_array($t,DataSync::$syncCloudDel))
            {
                //osy//echo 'syncclouddel'."<br>";
                $modifyrows=$dbcloud->createCommand($sql1)->queryScalar();
                if($modifyrows==0)
                {
                    //osy//echo $t."syncCloudDel no modify!<br>";
                    continue;
                }
                //osy//echo $t."syncCloudDel has modify!<br>";
                $clouddata=$dbcloud->createCommand("select * from ".$t." where dpid=".$dpid)->queryAll();
                $transactionlocal = $dblocal->beginTransaction();
                try {
                    $dblocal->createCommand("delete from ".$t." where dpid=".$dpid)->execute();
                    $deletelist1="(";
                    foreach($clouddata as $cd)
                    {
                        $deletelist1=$deletelist1.$cd['lid'].",";
                        $cd["is_sync"]=  DataSync::getAfterSync();
                        $dblocal->createCommand()->insert($t,$cd);
                    }
                    $deletelist1=$deletelist1."0000000000".")";
                    //dbcloud更新is_sync标志
                    $dbcloud->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,".
                            $localnum."+1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist1)->execute();
                    $transactionlocal->commit();
                } catch (Exception $ex) {
                    //echo $ex->getMessage();
                    $transactionlocal->rollback();
                    //return false;
                    throw $ex;
                    return false;
                }
                //删除完，然后下载，执行下一个表
                continue;
            } 
            //////////*******结束操作syncCloudDel内表*************///////////
            
             //////////*******开始获取数据*************///////////
            //根据is_sync取远端要更新的数据数据            
            $clouddata=$dbcloud->createCommand($sql1)->queryAll();  
            //osy//echo "cloud -> local:".$t.":".count($clouddata)."<br>";
            if(!empty($clouddata))
            {
                $deletelist1="(";
                foreach ($clouddata as $cdata)
                {
                    $deletelist1=$deletelist1.$cdata['lid'].",";
                    $clouddataarr[$cdata['lid']]=$cdata;
                }
                $deletelist1=$deletelist1."0000000000".")";
                //如果DataSync::$syncSpecialTalbe[$t]统一从本地取               
                if(!empty(DataSync::$syncSpecialTalbe[$t]))
                {
                   $sqldatasql="select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist1;
                   $clouddataforlocal=$dblocal->createCommand("select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist1)->queryAll();
                   if(!empty($clouddataforlocal))
                   {
                        foreach ($clouddataforlocal as $clcd) 
                        {
                            foreach (DataSync::$syncSpecialTalbe[$t] as $field)
                            {
                                if(!empty($clouddataarr[$clcd["lid"]]))
                                {
                                      $clouddataarr[$clcd["lid"]][$field]=$clcd[$field];
                                }
                            }
                         }
                   }                   
                }            
            }            
            
            //云端的数据在本地被更新过的取出来
            $cloudlocaldata=$dblocal->createCommand($sql1)->queryAll();
            //osy//echo "cloud local-> cloud:".$t.":".count($cloudlocaldata)."<br>";            
           //本地要保留的，并且更新云端的
           if(!empty($cloudlocaldata))
           {               
               $deletelist2="(";
               foreach ($cloudlocaldata as $sdata)
               {
                   $deletelist2=$deletelist2.$sdata['lid'].",";
                   $cloudlocaldataarr[$sdata['lid']]=$sdata;
               }
               $deletelist2=$deletelist2."0000000000".")";               
               //如果DataSync::$syncDataSql[$t]统一从云端取               
               if(!empty(DataSync::$syncDataSql[$t]))
                {
                   $sqldatasql="select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist2;
                   $cloudlocalclouddata=$dbcloud->createCommand("select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist2)->queryAll();
                   if(!empty($cloudlocalclouddata))
                   {
                        foreach ($cloudlocalclouddata as $clcd) 
                        {
                            foreach (DataSync::$syncDataSql[$t] as $field)
                            {
                                if(!empty($cloudlocaldataarr[$clcd["lid"]]))
                                {
                                      $cloudlocaldataarr[$clcd["lid"]][$field]=$clcd[$field];
                                }
                            }
                         }
                   }                   
                }
           }
            //////////*******结束获取数据*************///////////
           
           //////////*******开始处理数据冲突时候的特殊字段*************///////////
           foreach($cloudlocaldataarr as $clda)
           {
               if(empty($clouddataarr[$clda['lid']]))
               {                   
                   continue;
               }
                $copyfrom="cloud";//数据copy方向，默认是cloud,即除了特殊字段，都是云端覆盖本地，如果是local
                //就是本地覆盖云端,equal表示二个值相等，不做任何处理
                //判断当前表是否在$syncStatusCompare
                if(!empty(DataSync::$syncStatusCompare[$t]))
                {
                    //osy//echo "syncStatusCompare"."<br>";
                    foreach (DataSync::$syncStatusCompare[$t] as $key => $value) {
                        if($clda[$key]>$clouddataarr[$clda['lid']][$key]){
                                $copyfrom="local";
                        } 
//                        if(stripos($value,$clda[$key])!==false)
//                        {
//                            if(stripos($value,$clouddataarr[$clda['lid']][$key])!==false)
//                            {
//                                
//                            }
//                            if($clda[$key]==$clouddataarr[$clda['lid']][$key])
//                            {
//                                //相等时，按照默认，如果是云端的数据，就从cloud，本地的就是local
//                                //$copyfrom="equal";
//                            }elseif($clda[$key]>$clouddataarr[$clda['lid']][$key]){
//                                $copyfrom="local";
//                            } 
//                        }
                    }
                }  
                //osy//echo "copyfrom:".$copyfrom."<br>";
                if($copyfrom=="cloud")
                {
                    $tempdata=$cloudlocaldataarr[$clda["lid"]];
                    $cloudlocaldataarr[$clda["lid"]]=$clouddataarr[$clda["lid"]];
                    //本地数据为准，要更新到云端的
//                    if(!empty(DataSync::$syncSpecialTalbe[$t]))
//                    {                    
//                        foreach (DataSync::$syncSpecialTalbe[$t] as $field)
//                        {
//                            $cloudlocaldataarr[$clda["lid"]][$field]=$tempdata[$field];
//                            $clouddataarr[$clda["lid"]][$field]=$tempdata[$field];
//                        }
//                    }
                    //云端数据为准，要更新到本地
                }elseif ($copyfrom=="local") {
                    $tempdata=$clouddataarr[$clda["lid"]];
                    $clouddataarr[$clda["lid"]]=$cloudlocaldataarr[$clda["lid"]];
                    //本地要更新到云端的
                    //云端数据为准，要更新到本地
                }else{//相等，就不更新，相互调换一下，执行更新后相当于没有更新
                    $tempdata=$clouddataarr[$clda["lid"]];
                    $clouddataarr[$clda["lid"]]=$cloudlocaldataarr[$clda["lid"]];
                    $cloudlocaldataarr[$clda["lid"]]=$tempdata;
                }           
                //////////*******结束处理特殊字段*************///////////                
            }
            
            //////////*******开始更新*************///////////
            if(!empty($cloudlocaldataarr))
            {
                //osy//echo "begin copy cloudlocal to cloud<br>";
                $transactioncloud = $dbcloud->beginTransaction();
                try {
                    $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                    //如果有必须和云端一直的数据，要回写
                   //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                    //var_dump($cloudlocaldataarr);exit;
                    foreach($cloudlocaldataarr as $lsd)
                    {
                        $lsd["is_sync"]=  DataSync::getAfterSync();
                        $dbcloud->createCommand()->insert($t,$lsd);
                        //回写本地
                        if(!empty(DataSync::$syncDataSql[$t]))
                        {
                            //不是同时更新的，要回写
                            if(empty($clouddataarr[$lsd["lid"]]))
                            {
                                $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in (".$lsd["lid"].")")->execute();
                                $dblocal->createCommand()->insert($t,$lsd);
                            }
                        }
                    }
                    
                    //dblocal更新is_sync标志
                    $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,".
                            $localnum."+1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist2)->execute();
                    $transactioncloud->commit();
                } catch (Exception $ex) {
                    //echo $ex->getMessage();
                    $transactioncloud->rollback();
                    //return false;
                    throw $ex;
                    return false;
                }            
            }
            if(!empty($clouddataarr))
            {
                //osy//echo "begin copy cloud to local<br>";
                $transactionlocal = $dblocal->beginTransaction();
                try {
                    $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist1)->execute();
                    //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                    foreach($clouddataarr as $cd)
                    {
                        $cd["is_sync"]=  DataSync::getAfterSync();
                        $dblocal->createCommand()->insert($t,$cd);
                        //回写云端
                        if(!empty(DataSync::$syncSpecialTalbe[$t]))
                        {
                            //不是同时更新的，要回写
                            if(empty($cloudlocaldataarr[$cd["lid"]]))
                            {
                                $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in (".$cd["lid"].")")->execute();
                                $dbcloud->createCommand()->insert($t,$cd);
                            }
                        }
                    }
                    //dbcloud更新is_sync标志
                    $dbcloud->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,".
                            $localnum."+1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist1)->execute();
                                       
                    $transactionlocal->commit();
                } catch (Exception $ex) {
                    //echo $ex->getMessage();
                    $transactionlocal->rollback(); 
                    //return false;
                    throw $ex;
                    return false;
                }
            }
             //////////*******结束更新*************///////////
            //////////*******开始执行更新sql*************///////////
            
            //////////*******结束执行更新sql*************///////////
            
            //////////////////////
            //开始本地的同步到云端
            /////////////////////
            //将这个时间点开始的本地数据取出///云端不可能修改本地，只有本地修改云端。
             //////////*******开始获取数据*************///////////
            //根据is_sync取远端要更新的数据数据
//            $sql3 = "select * from ".$t." where lid%2=1 and dpid=".$dpid." and substring(is_sync,".
//                    Yii::app()->params['sync_localnum'].",1) = '1'";
            $sql2=str_replace("#tablename#",$t,$sql_2);
            //osy//echo "sql2".$sql2."<br>";
            $localdata=$dblocal->createCommand($sql2)->queryAll(); 
            //osy//echo "local->cloud data num is :".count($localdata)."<br>";
            if(!empty($localdata))
            {                
                $deletelist3="(";
                foreach ($localdata as $cdata)
                {
                    $deletelist3=$deletelist3.$cdata['lid'].",";
                    $localdataarr[$cdata['lid']]=$cdata;
                }
                $deletelist3=$deletelist3."0000000000".")";
                //如果DataSync::$syncDataSql[$t]统一从云端取               
               if(!empty(DataSync::$syncDataSql[$t]))
                {
                   $sqlall="select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist3;                   
                   $localclouddataall=$dbcloud->createCommand($sqlall)->queryAll();
                   
                   if(!empty($localclouddataall))
                   {
                        foreach ($localclouddataall as $clcd) 
                        {
                             foreach (DataSync::$syncDataSql[$t] as $field)
                              {
                                  if(!empty($localdataarr[$clcd["lid"]]))
                                  {
                                        $localdataarr[$clcd["lid"]][$field]=$clcd[$field];
                                  }
                              }
                         }
                   }
                   //var_dump($localclouddataall);exit;
                }
            }            
            
            //本地的数据在云端被更新过的取出来
            $localclouddata=$dbcloud->createCommand($sql2)->queryAll();
            //osy//echo "localcloud->local data num is :".count($localclouddata)."<br>";
            //本地要保留的，并且更新云端的
           if(!empty($localclouddata))
           {               
               $deletelist4="(";
               foreach ($localclouddata as $sdata)
               {
                   $deletelist4=$deletelist4.$sdata['lid'].",";
                   $localclouddataarr[$sdata['lid']]=$sdata;
               }
               $deletelist4=$deletelist4."0000000000".")";
               if(!empty(DataSync::$syncSpecialTalbe[$t]))
                {
                   $sqldatasql="select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist4;
                   $localclouddataforlocal=$dblocal->createCommand("select * from ".$t." where dpid=".$dpid." and lid in ".$deletelist4)->queryAll();
                   if(!empty($localclouddataforlocal))
                   {
                        foreach ($localclouddataforlocal as $clcd) 
                        {
                            foreach (DataSync::$syncSpecialTalbe[$t] as $field)
                            {
                                if(!empty($localclouddataarr[$clcd["lid"]]))
                                {
                                      $localclouddataarr[$clcd["lid"]][$field]=$clcd[$field];
                                }
                            }
                         }
                   }                   
                }
           }
            //////////*******结束获取数据*************///////////
           
           //////////*******开始处理数据冲突时候的特殊字段*************///////////
           foreach($localclouddataarr as $clda)
           {
               if(empty($localdataarr[$clda['lid']]))
               {
                   continue;
               }
                $copyfrom="local";//数据copy方向，默认是local,即除了特殊字段，都是本地覆盖云端，如果是cloud
                //就是云端覆盖本地,equal表示二个值相等，不做任何处理
                //判断当前表是否在$syncStatusCompare
                if(!empty(DataSync::$syncStatusCompare[$t]))
                {
                    foreach (DataSync::$syncStatusCompare[$t] as $key => $value) {
                        if($clda[$key]>$localdataarr[$clda['lid']][$key]){
                            $copyfrom="cloud";
                        }
//                        if((stripos($value,$clda[$key])!==false)
//                                && (stripos($value,$localdataarr[$clda['lid']][$key])!==false))
//                        {
//                            if($clda[$key]==$localdataarr[$clda['lid']][$key])
//                            {
//                                //相等时，按照默认，如果是云端的数据，就从cloud，本地的就是local
//                                //$copyfrom="equal";
//                            }elseif($clda[$key]>$localdataarr[$clda['lid']][$key]){
//                                $copyfrom="cloud";
//                            } 
//                        }
                    }
                }
                //osy//echo "copyfrom is ;".$copyfrom."<br>";                
                if($copyfrom=="cloud")
                {
                    $tempdata=$localdataarr[$clda["lid"]];
                    $localdataarr[$clda["lid"]]=$localclouddataarr[$clda["lid"]];
                    //本地数据为准，要更新到云端的
//                    if(!empty(DataSync::$syncSpecialTalbe[$t]))
//                    {                    
//                        foreach (DataSync::$syncSpecialTalbe[$t] as $field)
//                        {
//                            $localdataarr[$clda["lid"]][$field]=$tempdata[$field];
//                            $localclouddataarr[$clda["lid"]][$field]=$tempdata[$field];
//                        }
//                    }
//                    if(!empty(DataSync::$syncDataSql[$t]))
//                    {
//                        foreach (DataSync::$syncDataSql[$t] as $field)
//                        {
//                            //$localclouddataarr[$clda["lid"]][$field]=$tempdata[$field];
//                            $localdataarr[$clda["lid"]][$field]=$tempdata[$field];
//                        }
//                    }                    
                }elseif ($copyfrom=="local") {
                    $tempdata=$localclouddataarr[$clda["lid"]];
                    $localclouddataarr[$clda["lid"]]=$localdataarr[$clda["lid"]];
                    //本地要更新到云端的
                    //云端数据为准，要更新到本地
//                    if(!empty(DataSync::$syncDataSql[$t]))
//                    {
//                        foreach (DataSync::$syncDataSql[$t] as $field)
//                        {
//                            $localclouddataarr[$clda["lid"]][$field]=$tempdata[$field];
//                            //$localdataarr[$clda["lid"]][$field]=$tempdata[$field];
//                        }
//                    }
                }else{//相等，就不更新，相互调换一下，执行更新后相当于没有更新
                    $tempdata=$localclouddataarr[$clda["lid"]];
                    $localclouddataarr[$clda["lid"]]=$localdataarr[$clda["lid"]];
                    $localdataarr[$clda["lid"]]=$tempdata;
                }
                //////////*******结束处理特殊字段*************///////////                
            }
            
            //////////*******开始更新*************///////////
            if(!empty($localclouddataarr))
            {
                //osy//echo "copy localcloud->local:".count($localclouddata)."<br>";
                $transactionlocal = $dblocal->beginTransaction();
                try {
                    $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist4)->execute();
                    //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                    foreach($localclouddataarr as $lsd)
                    {
                        $lsd["is_sync"]=  DataSync::getAfterSync();
                        $dblocal->createCommand()->insert($t,$lsd);
                        //回写云端
                        if(!empty(DataSync::$syncSpecialTalbe[$t]))
                        {
                            //不是同时更新的，要回写
                            if(empty($localdataarr[$lsd["lid"]]))
                            {
                                $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in (".$lsd["lid"].")")->execute();
                                $dbcloud->createCommand()->insert($t,$lsd);
                            }
                        }
                    }
                    //dblocal更新is_sync标志
                    $dbcloud->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,".
                            $localnum."+1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist4)->execute();
                    $transactionlocal->commit();
                } catch (Exception $ex) {
                    //echo $ex->getMessage();
                    $transactionlocal->rollback();
                    throw $ex;
                    return false;
                }            
            }
            if(!empty($localdataarr))
            {
                //osy//echo "copy local->cloud:".count($localdataarr)."<br>";
                $transactioncloud = $dbcloud->beginTransaction();
                try {
                    $dbcloud->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist3)->execute();
                    //如果有必须和云端一直的数据，要回写
//                    if(!empty(DataSync::$syncDataSql[$t]))
//                    {
//                        $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in ".$deletelist3)->execute();
//                    }                    
                    //$dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid%2=0 and create_at>='".$cloudtime."'")->execute();
                    foreach($localdataarr as $cd)
                    {
                        $cd["is_sync"]=  DataSync::getAfterSync();
                        $dbcloud->createCommand()->insert($t,$cd);
                        //回写本地
                        if(!empty(DataSync::$syncDataSql[$t]))
                        {
                            //不是同时更新的，要回写
                            if(empty($localclouddataarr[$cd["lid"]]))
                            {
                                $dblocal->createCommand("delete from ".$t." where dpid=".$dpid." and lid in (".$cd["lid"].")")->execute();
                                $dblocal->createCommand()->insert($t,$cd);
                            }
                        }
                    }
                    //dbcloud更新is_sync标志
                    $dblocal->createCommand("update ".$t." set is_sync=CONCAT(substring(is_sync,1,".$localnum."-1),0,substring(is_sync,".
                            $localnum."+1,length(is_sync)-".$localnum.")) where dpid=".$dpid." and lid in ".$deletelist3)->execute();                   
                    $transactioncloud->commit();
                } catch (Exception $ex) {
                    //echo $ex->getMessage();
                    $transactioncloud->rollback(); 
                    //return false;
                    throw $ex;
                    return false;
                }
            } 
        }
       return true;
    }

    /**
     * 严格按照is_sync来同步
     * @param type $synctalbe 云端同步到本地，本地同步到云端
     * @param type $syncSpecialTalbe 特殊表特殊字段，如果云端有，本地也有，这几个字段以本地为准
     * @param type $isnow 是否立刻同步，还是随机延迟几十秒再同步，防止高并发
     */
    public static function FlagSync($dpid,$synctalbe=array(),$isnow=true){
        //osy//echo "flagsync";
        $localnum=Yii::app()->params['sync_localnum'];
        $sql1 = "select * from #tablename# where lid%2=0 and dpid=".$dpid." and substring(is_sync,".
             $localnum.",1) = '1'";
        $sql2 = "select * from #tablename# where lid%2=1 and dpid=".$dpid." and substring(is_sync,".
             $localnum.",1) = '1'";
        return DataSync::execSync($dpid,$synctalbe, $sql1, $sql2,$isnow);
    }
    
    /**
     * 按照某个时间来强制同步
     * @param type $specialTime "yyyy-mm-dd hh:mm:ss"
     * @param type $synctalbe
     * @param type $syncSpecialTalbe
     * @param type $isnow 是否立刻同步，还是随机延迟几十秒再同步，防止高并发
     */
    public static function timeSync($dpid,$specialTime,$isnow=true)
    {     
        if(empty($specialTime))
        {
            $specialTime="2015-08-15 19:00:00";
        }
        $ret = strtotime($specialTime);
        if(!$ret || $ret == -1)
        {
            return;
        }
        $cloudtime= $specialTime;
        $localtime= $specialTime;
        $sql1 = "select * from ".$t." where lid%2=0 and dpid=".$dpid." and update_at >= '".$cloudtime."'";
        $sql2 = "select * from ".$t." where lid%2=1 and dpid=".$dpid." and update_at >= '".$localtime."'";
        DataSync::execSync($dpid,  DataSync::$synctalbe, $sql1, $sql2,$isnow);                   
    }
    
    /**
     * 下载最新的图片文件
     * @param type $company_id
     */
    public static function clientDownImg($company_id){
        //$company_id = Yii::app()->request->getParam('companyId',0);
        //云端则不需要同步
        if(Yii::app()->params['cloud_local']=='c')
        {
            return;
        }
        try{           
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
                DataSync::GrabImage(Yii::app()->params->masterdomain,"uploads/company_".$company_id."/".$avalue);
            }
        } catch (Exception $ex) {
            //osy//echo "下载文件异常";
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
     * 
     * 这些数据，必须云端一致！！！
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
            return true;
        } catch (Exception $ex) {
            //osy//echo $ex->getMessage();
            $transactioncloud->rollback();
            return false;
        }
    }
}