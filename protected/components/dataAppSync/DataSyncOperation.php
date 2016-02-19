<?php
/**
 * 云端和本地营业数据实时同步！！！！！！！！！！
 * 本地获得云端新的订单数据，只取完成的，
 * 本地将自己的新的订单数据及会员和优惠数据发送给云端，只发送完成的
 * ///////////////////////////
 * 根据具体的需求添加
 * 目前有：
 * 1获得云端订单，2更改云端订单（状态、支付及口味等信息）
 * 2发送本地订单、支付、结单到云端、
 * 3发送本地交接班、日结、等信息到云端
 * 4发送本地活动信息到云端。
 * 5发送本地会员信息到云端。
 */
class DataSyncOperation
{
	/**
	 * 
	 * 获取基础数据表
	 * 
	 */
	 public static function getDataSyncBaseTable()
	 {
	 	$dataBase = new DataSyncTables();
        $allTables = $dataBase->getBaseTableList();
	 }
	 /**
	  * 
	  * 
	  * 获取所有的表
	  * 
	  */
	  public static function getDataSyncAllTable()
	 {
	 	$dataBase = new DataSyncTables();
        $allTables = $dataBase->getAllTableList();
	 }
	/**
	 * 
	 * 获取初始化数据
	 * 
	 */
	 public static function getDataSyncInit($dpid)
	 {
	 	$dataBase = new DataSyncTables();
        $allTables = $dataBase->getAllTableList();
        
	 }
     /**
     * 获取需要到本地执行的sql，每次仅限1000条
     */
    public static function getCloudSqlData1000($dpid)
    {
        $allflag=substr("11111111111111111111111111111111111111111111111111111", 
                0, Yii::app()->params['sync_maxlocal']);  
        return substr_replace($allflag, "0", Yii::app()->params['sync_localnum']-1,1);
    }
    
    public static function execLocalSql($dpid,$sqldata)
    {
        
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