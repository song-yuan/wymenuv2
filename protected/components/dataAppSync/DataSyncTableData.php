<?php
/**
 * 
 * 
 * 获取该表同步数据类
 * 
 */
class DataSyncTableData
{
	public $tableArr = array('nb_local_company','nb_local_activity','nb_close_account','nb_close_account_detail','nb_order','nb_order_pay');
    public function __construct($dpid,$tableName){
    	$this->dpid = $dpid;
    	$this->tableName = $tableName;
    }
    public function getInitData(){
    	$sql = 'select * from ' . $this->tableName . ' where dpid in (:dpid)';
    	if(!in_array($this->tableName,$this->tableArr)){
    		$sql .= ' and delete_flag = 0';
    	}
    	if($this->tableName=='nb_member_card'||$this->tableName=='nb_brand_user_level'){
    		$this->dpid = WxCompany::getDpids($this->dpid);
    	}
    	$data = Yii::app()->db->createCommand($sql)
    							->bindValue(':dpid',$this->dpid)
    							->queryAll();
    	return $data;
    }
   
}