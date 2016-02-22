<?php
/**
 * 
 * 
 * 获取该表同步数据类
 * 
 */
class DataSyncTableData
{
    public function __construct($dpid,$tableName){
    	$this->dpid = $dpid;
    	$this->tableName = $tableName;
    }
    public function getInitData(){
    	$sql = 'select * from ' . $this->tableName . ' where dapid=:dpid and delete_flag = 0';
    	$data = Yii::app()->db->createCommand($sql)
    							->bindValue(':dpid',$this->dpid)
    							->queryAll();
    	return $data;
    }
   
}