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
    public function __construct($data){
    	$this->dpid = $data['dpid'];
    	$this->tableName = $data['tn'];
    	$this->begain = isset($data['begain'])?$data['begain']:'';
    	$this->end = isset($data['end'])?$data['end']:'';
    }
    public function getInitData(){
    	$item = 500;
    	$dataArr = array('page'=>0, 'item'=>$item, 'msg'=>array());
    	if($this->tableName=='nb_member_card'||$this->tableName=='nb_brand_user_level'){
    		$this->dpid = WxCompany::getDpids($this->dpid);
    	}
    	$sql = 'select count(*) from ' . $this->tableName . ' where dpid in ('.$this->dpid.')';
    	if($this->begain!=''){
    		$begain = date('Y-m-d H:i:s',strtotime($this->begain));
    		$sql .= ' and create_at >= "'.$begain.'"';
    	}
    	if($this->end!=''){
    		$end = date('Y-m-d H:i:s',strtotime($this->end)+24*60*60);
    		$sql .= ' and create_at <= "'.$end.'"';
    	}
    	if($this->end==''&&$this->begain==''){
    		$time = date('Y-m-d H:i:s',time()-7*24*60*60);
    		$sql .= ' and create_at >= "'.$time.'"';
    	}
    	if(!in_array($this->tableName,$this->tableArr)){
    		$sql .= ' and delete_flag = 0';
    	}
    	$dataCount = Yii::app()->db->createCommand($sql)->queryRow();
    	
    	$sql = str_replace('count(*)', '*', $sql);
    	
    	$page = ceil($dataCount['count(*)']/$item);
    	$dataArr['page'] = $page;
    	
    	$tempArr = array();
    	for($i=0;$i<$page;$i++){
    		$newSql = $sql.' limit '.$i*$item.','.$item;
    		$data = Yii::app()->db->createCommand($newSql)->queryAll();
    		$tempArr = array_merge($tempArr,$data);
    	}
    	$dataArr['msg'] = $tempArr;
    	return $dataArr;
    }
   
}